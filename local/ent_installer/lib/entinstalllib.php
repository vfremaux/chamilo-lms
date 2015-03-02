<?php

require_once $_configuration['root_sys'].'local/classes/database.class.php';
global $DB;
$DB = new DatabaseManager;


/**
 * Syncronizes user from external LDAP server to moodle user table
 *
 * Sync is now using username attribute.
 *
 * Syncing users removes or suspends users that dont exists anymore in external LDAP.
 * Creates new users and updates coursecreator status of users.
 *
 * @param bool $do_updates will do pull in data updates from LDAP if relevant
 */
function local_ent_installer_sync_users($ldapauth, $options) {
    global $CFG, $DB;

    ctrace('');
    $enable = get_config('ent_installer', 'enable_sync', true);
    if (!$enable){
        ctrace(get_string('syncdisabled', 'local_ent_installer'));
        return;
    }

    $USERFIELDS = local_ent_installer_load_user_fields();

    $lastrun = get_config('ent_installer', 'last_sync_date', true);
    ctrace(get_string('lastrun', 'local_ent_installer', make_tms($lastrun)));
    ctrace(get_string('connectingldap', 'local_ent_installer'));
    $ldapconnection = $ldapauth->ldap_connect();

    list($usec, $sec) = explode(' ',microtime()); 
    $starttick = (float)$sec + (float)$usec;

/// Define table user to be created
    /*
    $table = new xmldb_table('tmp_extuser');
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('username', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
    $table->add_field('usertype', XMLDB_TYPE_CHAR, '16', null, null, null, null);
    $table->add_field('lastmodified', XMLDB_TYPE_INTEGER, '11', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null);
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
    $table->add_index('userprofile', XMLDB_INDEX_UNIQUE, array('username', 'usertype'));
    */
    
    $table = 'tmp_extuser';
    $tmptablename = Database::get_main_table($table);

    $sql = "DROP TABLE IF EXISTS $tmptablename ";
    Database::query($sql);
    
    $sql = "CREATE TABLE $tmptablename (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `username` varchar(100) NOT NULL,
      `usertype` varchar(16) NOT NULL,
      `lastmodified` int(11),
        
      PRIMARY KEY (`id`),
      INDEX unique_user (username, usertype)
    ) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
    ";
    Database::query($sql);

    ctrace(get_string('creatingtemptable', 'local_ent_installer', 'tmp_extuser'));

    // Ensure stats table is created in local site DB
    $table = 'local_ent_installer';
    $tablename = Database::get_main_table($table);
    $sql = "CREATE TABLE IF NOT EXISTS $tablename (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `timestart` int(11) NOT NULL,
      `timerun` int(11) NOT NULL,
      `added` int(11) NOT NULL,
      `updated` int(11) NOT NULL,
      `inserterror` int(11) NOT NULL,
      `updateerror` int(11) NOT NULL,
    
      PRIMARY KEY (`id`)
    ) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
    ";
    Database::query($sql);

    ////
    //// get user's list from ldap to sql in a scalable fashion from different user profiles
    //// defined as LDAP filters
    ////
    // prepare some data we'll need
    $filters = array();

    $institutionid = get_config('ent_installer', 'institution_id');

    // students
    if (empty($options['role']) || preg_match('/eleve/', $options['role'])){
        $filterdef = new StdClass();
        $filterdef->institution = '(ENTEleveClasses=*='.$institutionid.',*)';
        $filterdef->usertype = '(objectClass=ENTEleve)';
        $filterdef->userfield = 'eleve';
        $filters[] = $filterdef;
    }

    // teaching staff
    if (empty($options['role']) || preg_match('/enseignant/', $options['role'])){
        $filterdef = new StdClass();
        $filterdef->institution = '(ENTPersonFonctions=*='.$institutionid.',*)';
        $filterdef->usertype = '(objectClass=ENTAuxEnseignant)';
        $filterdef->userfield = 'enseignant';
        $filters[] = $filterdef;
    }

    // non teaching staff
    if (empty($options['role']) || preg_match('/administration/', $options['role'])){
        $filterdef = new StdClass();
        $filterdef->institution = '(ENTPersonFonctions=*='.$institutionid.',*)';
        $filterdef->usertype = '(objectClass=ENTAuxNonEnsEtab)';
        $filterdef->userfield = 'administration';
        $filters[] = $filterdef;
    }

    $contexts = explode(';', $ldapauth->config->contexts);

    if (!empty($ldapauth->config->create_context)) {
        array_push($contexts, $ldapauth->config->create_context);
    }

    $ldap_pagedresults = ldap_paged_results_supported($ldapauth->config->ldap_version);
    $ldap_cookie = '';
    foreach ($filters as $filterdef) {

        $filter = '(&('.$ldapauth->config->user_attribute.'=*)'.$filterdef->usertype.$filterdef->institution.')';

        foreach ($contexts as $context) {
            $context = trim($context);
            if (empty($context)) {
                continue;
            }

            do {
                if ($ldap_pagedresults) {
                    ldap_control_paged_result($ldapconnection, $ldapauth->config->pagesize, true, $ldap_cookie);
                }
                if ($ldapauth->config->search_sub) {
                    // Use ldap_search to find first user from subtree.
                    ctrace("ldapsearch $context, $filter for ".$ldapauth->config->user_attribute);
                    $ldap_result = ldap_search($ldapconnection, $context, $filter, array($ldapauth->config->user_attribute, 'modifyTimestamp'));
                } else {
                    // Search only in this context.
                    ctrace("ldaplist $context, $filter for ".$ldapauth->config->user_attribute);
                    $ldap_result = ldap_list($ldapconnection, $context, $filter, array($ldapauth->config->user_attribute, 'modifyTimestamp'));
                }
                if(!$ldap_result) {
                    continue;
                }
                if ($ldap_pagedresults) {
                    ldap_control_paged_result_response($ldapconnection, $ldap_result, $ldap_cookie);
                }
                if ($entry = @ldap_first_entry($ldapconnection, $ldap_result)) {
                    do {
                        $value = ldap_get_values_len($ldapconnection, $entry, $ldapauth->config->user_attribute);
                        $value = textlib::convert($value[0], $ldapauth->config->ldapencoding, 'utf-8');

                        $modify = ldap_get_values_len($ldapconnection, $entry, 'modifyTimestamp');
                        $modify = strtotime($modify[0]);

                        local_ent_installer_ldap_bulk_insert($value, $filterdef->userfield, $modify);
                    } while ($entry = ldap_next_entry($ldapconnection, $entry));
                }
                unset($ldap_result); // Free mem.
            } while ($ldap_pagedresults && !empty($ldap_cookie));
        }
    }

    // If LDAP paged results were used, the current connection must be completely
    // closed and a new one created, to work without paged results from here on.
    if ($ldap_pagedresults) {
        $ldapauth->ldap_close(true);
        $ldapconnection = $ldapauth->ldap_connect();
    }

    /// preserve our user database
    /// if the temp table is empty, it probably means that something went wrong, exit
    /// so as to avoid mass deletion of users; which is hard to undo
    $count = $DB->count_records_sql('SELECT COUNT(username) AS count, 1 FROM {tmp_extuser}');
    ctrace('');
    if ($count < 1) {
        ctrace(get_string('didntgetusersfromldap', 'local_ent_installer'));
        
        $sql = " DROP TABLE $table ";
        Database::query($sql);
        $ldapauth->ldap_close(true);
        exit;
    } else {
        ctrace(get_string('gotcountrecordsfromldap', 'local_ent_installer', $count));
    }


/// User removal
    // Find users in DB that aren't in ldap -- to be removed!
    // this is still not as scalable (but how often do we mass delete?)
    if ($ldapauth->config->removeuser != AUTH_REMOVEUSER_KEEP) {
        $sql = 'SELECT u.*
                  FROM {user} u
                  LEFT JOIN {tmp_extuser} e ON (u.username = e.username)
                 WHERE u.auth_source = \''.$ldapauth->authtype.'\'
                       AND u.active = 1
                       AND e.username IS NULL';
        $remove_users = $DB->get_records_sql($sql);

        if (!empty($remove_users)) {
            ctrace('userentriestoremove', 'local_ent_installer', count($remove_users));

            foreach ($remove_users as $user) {
                if ($ldapauth->config->removeuser == AUTH_REMOVEUSER_FULLDELETE) {
                    $user->active = 0;
                    if ($DB->update_record('user', $user, 'user_id')) {
                        echo "\t"; 
                        ctrace(get_string('auth_dbdeleteuser', 'auth_db', array('name' => $user->username, 'id' => $user->user_id))); 
                    } else {
                        echo "\t"; 
                        ctrace(get_string('auth_dbdeleteusererror', 'auth_db', $user->username)); 
                    }
                } else if ($ldapauth->config->removeuser == AUTH_REMOVEUSER_SUSPEND) {
                    $updateuser = new stdClass();
                    $updateuser->user_id = $user->id;
                    $updateuser->auth_source = 'nologin';
                    $DB->update_record('user', $updateuser, 'user_id');
                    echo "\t"; 
                    ctrace(get_string('auth_dbsuspenduser', 'auth_db', array('name' => $user->username, 'id' => $user->id)));
                    $euser = $DB->get_record('user', array('user_id' => $user->id));
                }
            }
        } else {
            ctrace(get_string('nouserentriestoremove', 'local_ent_installer'));
        }
        unset($remove_users); // free mem!
    }

/// Revive suspended users
    if (!empty($ldapauth->config->removeuser) and $ldapauth->config->removeuser == AUTH_REMOVEUSER_SUSPEND) {
        $sql = "SELECT u.user_id, u.username
                  FROM {user} u
                  JOIN {tmp_extuser} e ON (u.username = e.username)
                 WHERE u.auth = 'nologin' AND u.active = 1";
        $revive_users = $DB->get_records_sql($sql);

        if (!empty($revive_users)) {
            ctrace(get_string('userentriestorevive', 'local_ent_installer', count($revive_users)));

            foreach ($revive_users as $user) {
                $updateuser = new stdClass();
                $updateuser->user_id = $user->user_id;
                $updateuser->auth_source = $ldapauth->authtype;
                $DB->update_record('user', $updateuser, 'user_id');
                echo "\t"; 
                ctrace(get_string('auth_dbreviveduser', 'auth_db', array('name' => $user->username, 'id' => $user->user_id))); 
                $euser = $DB->get_record('user', array('user_id' => $user->user_id));
            }
        } else {
            ctrace(get_string('nouserentriestorevive', 'local_ent_installer'));
        }

        unset($revive_users);
    }


/// User Updates - time-consuming (optional)
    if (!empty($options['doupdates'])) {
        // Narrow down what fields we need to update

        $all_keys = array_keys(get_object_vars($ldapauth->config));
        $updatekeys = array();
        foreach ($all_keys as $key) {
            if (preg_match('/^field_updatelocal_(.+)$/', $key, $match)) {
                // If we have a field to update it from
                // and it must be updated 'onlogin' we
                // update it on cron
                if (!empty($ldapauth->config->{'field_map_'.$match[1]})
                     and $ldapauth->config->{$match[0]} === 'onlogin') {
                    array_push($updatekeys, $match[1]); // the actual key name
                }
            }
        }
        unset($all_keys); 
        unset($key);

    } else {
        ctrace(get_string('noupdatestobedone', 'local_ent_installer'));
    }

    if (@$options['doupdates']){
        if(!empty($updatekeys)) { // run updates only if relevant
            $users = $DB->get_records_sql("SELECT u.username, u.user_id
                                             FROM {user} u
                                            WHERE u.active = 1 AND u.auth_source = '{$ldapauth->authtype}' ");
            if (!empty($users)) {
                ctrace(get_string('userentriestoupdate', 'local_ent_installer', count($users)));
    
                $xcount = 0;
                $maxxcount = 100;
    
                foreach ($users as $user) {
                    echo "\t";
                    $tracestr = get_string('auth_dbupdatinguser', 'auth_db', array('name' => $user->username, 'id' => $user->user_id)); 
                    /*
                    if (!$ldapauth->update_user_record($user->username, $updatekeys)) {
                        $tracestr .= ' - '.get_string('skipped');
                    }
                    */
                    ctrace($tracestr);
                    $xcount++;
                }
                unset($users); // free mem
            }
        } else { // end do updates
            ctrace(get_string('nouserstoupdate', 'local_ent_installer'));
        }
    }

/// User Additions or full profile update
    // Find users missing in DB that are in LDAP or users that have been modified since last run
    // and gives me a nifty object I don't want.
    // note: we do not care about deleted accounts anymore, this feature was replaced by suspending to nologin auth plugin
    if (empty($options['force'])){
        $sql = "SELECT e.id, e.username, e.usertype
                  FROM {tmp_extuser} e
                  LEFT JOIN {user} u ON (e.username = u.username)
                 WHERE u.user_id IS NULL OR (
                 e.lastmodified > $lastrun ) ORDER BY e.username";
    } else {
        $sql = 'SELECT e.id, e.username, e.usertype
                  FROM {tmp_extuser} e ORDER BY e.username';
    }

    $add_users = $DB->get_records_sql($sql);

    if (!empty($add_users)) {

        $usercount = 0 + count(array_keys($add_users));
        // ctrace(get_string('userentriestoadd', 'local_ent_installer', $usercount));

        foreach ($add_users as $user) {

            // save usertype
            $usertype = $user->usertype;

            $user = local_ent_installer_get_userinfo_asobj($ldapauth, $user->username, $options);
            // restore usertype in user
            $user->usertype = $usertype;

            $user->official_code = $user->ENTPersonJointure;

            // Prep a few params.
            $user->modified = time();
            $user->confirmed = 1;
            // Authentication is the ldap plugin or a real auth plugin defined in setup.
            $realauth = get_config('ent_installer', 'real_used_auth');
            $user->auth_source = (empty($realauth)) ? $ldapauth->authtype : $realauth ;

            if (empty($user->email)) $user->email = local_ent_installer_generate_email($user);

            // get_userinfo_asobj() might have replaced $user->username with the value
            // from the LDAP server (which can be mixed-case). Make sure it's lowercase
            $user->username = trim(textlib::strtolower($user->username));

            // process additional info for student : 
            // extra information fields transport and regime
            if ($user->usertype == 'eleve') {

                // Transport.
                $user->profile_field_transport = ('Y' == @$user->ENTEleveTransport) ? '1' : 0 ;

                // Regime.
                $user->profile_field_regime = @$user->ENTEleveRegime;

                // Cohort (must have).
                $user->profile_field_cohort = $user->ENTEleveClasses;
                $user->status = STUDENT;
            } else {
                $user->status = COURSEMANAGER;
            }

            $personfunction = @$user->ENTPersonFonctions;
            unset($user->ENTPersonFonctions);
            mtrace('Firstname: '.$user->firstname."\n");
            mtrace('Lastname: '.$user->lastname."\n");

            // get the last term of personfunction and set it as department
            if (!empty($personfunction)) {
                preg_match('/\$([^\$]+)$/', $personfunction, $matches);
                $user->profile_field_department = $matches[1];
            }

            if (empty($options['simulate'])) {
                $a = clone($user);
                $a->function = $personfunction;
                if ($oldrec = $DB->get_record('user', array('username' => $user->username))) {
                    $user_id = $user->user_id = $oldrec->user_id;
                    if ($DB->update_record('user', $user, 'user_id')) {
                        $updatecount++;
                    } else {
                        $updateerrorcount++;
                    }
                    ctrace(get_string('dbupdateuser', 'local_ent_installer', $a)); 
                } else {
                    if ($user_id = $DB->insert_record('user', $user)) {
                        ctrace(get_string('dbinsertuser', 'local_ent_installer', $a));
                        $insertcount++;
                    } else {
                        ctrace('Failed insert '.$user->username);
                        // print_object($user);
                        $inserterrorcount++;
                    }
                }
                
                // check user access to access url 1
                if (!$DB->record_exists('access_url_rel_user', array('user_id' => $user_id, 'access_url_id' => 1), 'user_id')){
                    $accessurlrec = new StdClass();
                    $accessurlrec->user_id = $user_id;
                    $accessurlrec->access_url_id = 1;
                    $DB->insert_record('access_url_rel_user', $accessurlrec);
                }

            } else {
                $a = clone($user);
                $a->function = $personfunction;
                if (!$oldrec = $DB->get_record('user', array('username' => $user->username))) {
                    ctrace(get_string('dbinsertusersimul', 'local_ent_installer', $a)); 
                } else {
                    ctrace(get_string('dbupdateusersimul', 'local_ent_installer', $a)); 
                }
            }

            if (empty($options['simulate'])) {
                $euser = $DB->get_record('user', array('user_id' => $user_id));

                assert(!empty($euser));

                // cohort information / create/update cohorts
                if ($user->usertype == 'eleve') {

                    // adds user to cohort and create cohort if missing
                    $cohortshort = local_ent_installer_check_cohort($euser->user_id, $user->profile_field_cohort);

                    local_ent_installer_update_info_data($euser->user_id, $USERFIELDS['transport'], $user->profile_field_transport);
                    local_ent_installer_update_info_data($euser->user_id, $USERFIELDS['regime'], $user->profile_field_regime);
                    local_ent_installer_update_info_data($euser->user_id, $USERFIELDS['cohort'], $cohortshort);
                }

                // process user_fields setup

                if (preg_match('#\\$CTR\\$#', $personfunction)) {
                    // special case
                    local_ent_installer_update_info_data($euser->user_id, $USERFIELDS['cdt'], 1);
                } else {
                    // Other user types
                    local_ent_installer_update_info_data($euser->user_id, $USERFIELDS[$user->usertype], 1);
                }

                // Add a workplace to teachers
                if ($user->usertype == 'enseignant') {
                    if (get_config('ent_installer', 'build_teacher_category')) {
                        local_ent_installer_make_teacher_category($euser);
                    }
                }

                // Identify school deans and give them administrator role role.
                if (preg_match('#\\$DIR\\$#', $personfunction)) {
                    mtrace("Status: Director\n");
                    // set a new record in admin table for this userid
                    if (!$rec = $DB->get_record('admin', array('user_id' => $id))) {
                        $rec = new StdClass();
                        $rec->user_id = $id;
                        $DB->insert_record('admin', $rec);
                    }
                }

                /*
                // identify librarians and give library enabled role at system level
                if (preg_match('#\\$DOC\\$#', $personfunction)){
                    if($role = $DB->get_record('role', array('shortname' => 'librarian'))){
                        $systemcontext = context_system::instance();
                        role_assign($role->id, $id, $systemcontext->id);
                    }
                }
                */
            }
        }
        unset($add_users); // free mem
    } else {
        ctrace(get_string('nouserstobeadded', 'local_ent_installer'));
    }
    
    $sql = " DROP TABLE $tmptablename ";
    Database::query($sql);
    ctrace('Removing temp table');

    $ldapauth->ldap_close();

    list($usec, $sec) = explode(' ',microtime()); 
    $stoptick = (float)$sec + (float)$usec;

    $deltatime = $stoptick - $starttick;

    ctrace('Execution time : '.$deltatime);
    $benchrec = new StdClass();
    $benchrec->timestart = floor($starttick);
    $benchrec->timerun = ceil($deltatime);
    $benchrec->added = 0 + @$insertcount;
    $benchrec->updated = 0 + @$updatecount;
    $benchrec->updateerrors = 0 + @$inserterrorcount;
    $benchrec->inserterrors = 0 + @$updateerrorcount;
    if (!$DB->insert_record('local_ent_installer', $benchrec)) {
        ctrace('Stat insertion failure');
    }

    // Mark last time the user sync was run.
    set_config('last_sync_date', time(), 'ent_installer', true);

    return true;
}

/**
 * Bulk insert in SQL's temp table
 */
function local_ent_installer_ldap_bulk_insert($username, $usertype, $timemodified) {
    global $DB, $CFG;

    $username = textlib::strtolower($username); // usernames are __always__ lowercase.
    if (!$DB->record_exists('tmp_extuser', array('username' => $username,
                                                'usertype' => $usertype))){
        $DB->insert_record_raw('tmp_extuser', array('username' => $username,
                                                    'usertype' => $usertype,
                                                    'lastmodified' => $timemodified));
    }
    echo '.';
}

/**
 * loads User Type special info fields definition
 * @return an array of info/custom field mappings
 */
function local_ent_installer_load_user_fields(){
    global $DB;
    
    $USERFIELDS = array();

    $now = time();
    
    $USERFIELDS['eleve'] = $DB->get_field('user_field', 'id', array('field_variable' => 'eleve'));
    if (empty($USERFIELDS['eleve'])){
        $field = new StdClass();
        $field->field_type = 1;
        $field->field_variable = 'eleve';
        $field->field_display_text = 'Eleve';
        $field->field_default_value = 0;
        $field->field_visible = 0;
        $field->field_changeable = 0;
        $field->tms = make_tms($now);
        $USERFIELDS['eleve'] = $DB->insert_record('user_field', $field);
    }

    $USERFIELDS['parent'] = $DB->get_field('user_field', 'id', array('field_variable' => 'parent'));
    if (empty($USERFIELDS['parent'])){
         $field = new StdClass();
        $field->field_type = 1;
        $field->field_variable = 'parent';
        $field->field_display_text = 'Parent';
        $field->field_default_value = 0;
        $field->field_visible = 0;
        $field->field_changeable = 0;
        $field->tms = make_tms($now);
        $USERFIELDS['parent'] = $DB->insert_record('user_field', $field);
    }

    $USERFIELDS['enseignant'] = $DB->get_field('user_field', 'id', array('field_variable' => 'enseignant'));
    if (empty($USERFIELDS['enseignant'])){
        $field = new StdClass();
        $field->field_type = 1;
        $field->field_variable = 'enseignant';
        $field->field_display_text = 'Enseignant';
        $field->field_default_value = 0;
        $field->field_visible = 0;
        $field->field_changeable = 0;
        $field->tms = make_tms($now);
        $USERFIELDS['enseignant'] = $DB->insert_record('user_field', $field);
    }

    $USERFIELDS['administration'] = $DB->get_field('user_field', 'id', array('field_variable' => 'enseignant'));
    if (empty($USERFIELDS['administration'])){
        $field = new StdClass();
        $field->field_type = 1;
        $field->field_variable = 'administration';
        $field->field_display_text = 'Administration';
        $field->field_default_value = 0;
        $field->field_visible = 0;
        $field->field_changeable = 0;
        $field->tms = make_tms($now);
        $USERFIELDS['administration'] = $DB->insert_record('user_field', $field);
    }

    $USERFIELDS['cdt'] = $DB->get_field('user_field', 'id', array('field_variable' => 'cdt'));
    if (empty($USERFIELDS['cdt'])){
        $field = new StdClass();
        $field->field_type = 1;
        $field->field_variable = 'cdt';
        $field->field_display_text = 'Chef de travaux';
        $field->field_default_value = 0;
        $field->field_visible = 0;
        $field->field_changeable = 0;
        $field->tms = make_tms($now);
        $USERFIELDS['cdt'] = $DB->insert_record('user_field', $field);
    }

    // academic info
    
    $USERFIELDS['cohort'] = $DB->get_field('user_field', 'id', array('field_variable' => 'cohort'));
    if (empty($USERFIELDS['cohort'])){
        $field = new StdClass();
        $field->field_type = 1;
        $field->field_variable = 'cohort';
        $field->field_display_text = 'Classe';
        $field->field_default_value = 0;
        $field->field_visible = 0;
        $field->field_changeable = 0;
        $field->tms = make_tms($now);
        $USERFIELDS['cohort'] = $DB->insert_record('user_field', $field);
    }

    $USERFIELDS['transport'] = $DB->get_field('user_field', 'id', array('field_variable' => 'transport'));
    if (empty($USERFIELDS['transport'])){
        $field = new StdClass();
        $field->field_type = 1;
        $field->field_variable = 'transport';
        $field->field_display_text = 'Transport en commun';
        $field->field_default_value = 0;
        $field->field_visible = 0;
        $field->field_changeable = 0;
        $field->tms = make_tms($now);
        $USERFIELDS['transport'] = $DB->insert_record('user_field', $field);
    }

    $USERFIELDS['regime'] = $DB->get_field('user_field', 'id', array('field_variable' => 'regime'));
    if (empty($USERFIELDS['regime'])){
        $field = new StdClass();
        $field->field_type = 1;
        $field->field_variable = 'regime';
        $field->field_display_text = 'Régime';
        $field->field_default_value = 0;
        $field->field_visible = 0;
        $field->field_changeable = 0;
        $field->tms = make_tms($now);
        $USERFIELDS['regime'] = $DB->insert_record('user_field', $field);
    }
    
    return $USERFIELDS;
}

/**
 * an utility function that explores the ldap ENTEtablissement object list to get proper institution id
 *
 * @param object $ldapauth the ldap authentication instance
 * @param string $search the search pattern
 * @param array $searchby where to search, either 'name' or 'city'
 * @return an array of objects with institution ID and institution name
 */
function local_ent_installer_ldap_search_institution_id($ldapauth, $search, $searchby = 'name'){
    global $LDAPQUERYTRACE;

    $ldapconnection = $ldapauth->ldap_connect();
    
    $context = get_config('ent_installer', 'structure_context');
    // just for tests
    if (empty($context)) $context = 'ou=structures,dc=atrium-paca,dc=fr';

    if ($search != '*'){
        $search = '*'.$search.'*';
    }

    if ($searchby = 'name'){
        $filter = str_replace('%%SEARCH%%', '', get_config('ent_installer', 'structure_name_filter'));
        // just for tests
        if (empty($filter)) $filter = '(&(objectClass=ENTEtablissement)(ENTStructureNomCourant='.$search.'))';
    } else {
        $filter = str_replace('%%SEARCH%%', '', get_config('ent_installer', 'structure_city_filter'));
        // just for tests
        if (empty($filter)) $filter = '(&(objectClass=ENTEtablissement)(ENTEtablissementBassin='.$search.'))';
    }

    $structureid = get_config('ent_installer', 'structure_id_attribute');
    // just for tests
    if (empty($structureid)) $structureid = 'ENTStructureUAI';

    $structurename = get_config('ent_installer', 'structure_name_attribute');
    // just for tests
    if (empty($structurename)) $structurename = 'ENTStructureNomCourant';
    
    list($usec, $sec) = explode(' ',microtime()); 
    $pretick = (float)$sec + (float)$usec;
    // Search only in this context.
    $ldap_result = @ldap_search($ldapconnection, $context, $filter, array($structureid, $structurename));
    list($usec, $sec) = explode(' ',microtime()); 
    $posttick = (float)$sec + (float)$usec;
    
    $LDAPQUERYTRACE = $posttick - $pretick. ' s. ('.$context.' '.$filter.' ['.$structureid.','.$structurename.'])';
    
    if(!$ldap_result) {
        return '';
    }

    $results = array();
    if ($entry = @ldap_first_entry($ldapconnection, $ldap_result)) {
        do {
            $institution = new StdClass;

            $value = ldap_get_values_len($ldapconnection, $entry, $structureid);
            $institution->id = textlib::convert($value[0], $ldapauth->config->ldapencoding, 'utf-8');

            $value = ldap_get_values_len($ldapconnection, $entry, $structurename);
            $institution->name = textlib::convert($value[0], $ldapauth->config->ldapencoding, 'utf-8');
            $results[] = $institution;
        } while ($entry = ldap_next_entry($ldapconnection, $entry));
    }
    unset($ldap_result); // Free mem.
    
    return $results;
}

/**
 * Reads user information from ldap and returns it in array()
 *
 * Function should return all information available. If you are saving
 * this information to moodle user-table you should honor syncronization flags
 *
 * @param object $ldapauth the ldap authentication instance
 * @param string $username username
 * @param array $options an array with CLI input options
 *
 * @return mixed array with no magic quotes or false on error
 */
function local_ent_installer_get_userinfo($ldapauth, $username, $options = array()) {
    static $entattributes;
    
    // load some cached static data
    if (!isset($entattributes)) {
        // aggregate additional ent specific attributes that hold interesting information
        $configattribs = get_config('ent_installer', 'ent_userinfo_attributes');
        // if (empty($configattribs)){
            $entattributes = array('ENTPersonFonctions','ENTPersonJointure', 'ENTEleveClasses', 'ENTEleveTransport', 'ENTEleveRegime');
        // } else {
        //     $entattributes = $ldapuserfields + explode(',', $configattribs);
        // }
    }
    
    $extusername = textlib::convert($username, 'utf-8', $ldapauth->config->ldapencoding);

    $ldapconnection = $ldapauth->ldap_connect();
    if(!($user_dn = $ldapauth->ldap_find_userdn($ldapconnection, $extusername))) {
        $ldapauth->ldap_close();
        return false;
    }

    $search_attribs = array();
    $attrmap = $ldapauth->ldap_attributes();
    foreach ($attrmap as $key => $values) {
        if (!is_array($values)) {
            $values = array($values);
        }
        foreach ($values as $value) {
            if (!in_array($value, $search_attribs)) {
                array_push($search_attribs, $value);
            }
        }
    }

    // ensure duplicates removing
    foreach ($entattributes as $value) {
        if (!in_array($value, $search_attribs)) {
            array_push($search_attribs, $value);
            // add attributes to $attrmap so they are pulled down into final user object
            $attrmap[$value] = strtolower($value);
        }
    }

    if (array_key_exists('verbose', $options)) {
        ctrace("Getting $user_dn for ".implode(',', $search_attribs));
    }
    if (!$user_info_result = ldap_read($ldapconnection, $user_dn, '(objectClass=*)', $search_attribs)) {
        $ldapauth->ldap_close();
        return false; // error!
    }

    $user_entry = ldap_get_entries_chamilo($ldapconnection, $user_info_result);
    if (empty($user_entry)) {
        $ldapauth->ldap_close();
        return false; // entry not found
    }

    $result = array();
    foreach ($attrmap as $key => $values) {
        if (!is_array($values)) {
            $values = array($values);
        }
        $ldapval = NULL;
        foreach ($values as $value) {
            $entry = array_change_key_case($user_entry[0], CASE_LOWER);

            if (($value == 'dn') || ($value == 'distinguishedname')) {
                $result[$key] = $user_dn;
                continue;
            }

            if (!array_key_exists($value, $entry)) {
                if ($options['verbose']){
                    ctrace("Requested value $value but missing in record");
                }
                continue; // wrong data mapping!
            }

            if (is_array($entry[$value])) {
                $newval = textlib::convert($entry[$value][0], $ldapauth->config->ldapencoding, 'utf-8');
            } else {
                $newval = textlib::convert($entry[$value], $ldapauth->config->ldapencoding, 'utf-8');
            }

            if (!empty($newval)) { // favour ldap entries that are set
                $ldapval = $newval;
            }
        }
        if (!is_null($ldapval)) {
            $result[$key] = $ldapval;
        }
    }

    $ldapauth->ldap_close();
    return $result;
}

/**
 * Reads user information from ldap and returns it in an object
 *
 * @param object $ldapauth the ldap authentication instance
 * @param string $username username (with system magic quotes)
 * @param array $options an array with CLI input options
 * @return mixed object or false on error
 */
function local_ent_installer_get_userinfo_asobj($ldapauth, $username, $options = array()) {

    $user_array = local_ent_installer_get_userinfo($ldapauth, $username, $options);

    if ($user_array == false) {
        return false; //error or not found
    }

    // $user_array = truncate_userinfo($user_array);
    $user = new stdClass();
    foreach ($user_array as $key => $value) {
        $user->{$key} = $value;
    }
    return $user;
}

/**
* add user to cohort after creating cohort if missing and removing to eventual 
* other cohort.
* Cohorts are handled in the 'externalent' component scope and will NOT interfere
* with locally manually created cohorts.
* @param int $userid the user id
* @param string $cohortidentifier a fully qualified cohort name (SDET compliant)
*
* return cohort short name
*/
function local_ent_installer_check_cohort($userid, $cohortidentifier){
    global $DB;
    
    list($fooinstitutionid, $cohortname) = explode('$', $cohortidentifier);
    $institutionid = get_config('ent_installer', 'institution_id'); // nicer form

    $now = make_tms(time());
    
    if (!$cohortid = $DB->get_field('usergroup', 'id', array('name' => $cohortname))){
                
        $cohort = new StdClass();
        $cohort->name = $cohortname;
        $cohort->description = $institutionid.'$'.$cohortname; // description will lock the cohort updating
        // $cohort->updated_on = $now; // if groups
        // $cohort->created_on = $now; // if groups
        $cohort->id = $DB->insert_record('usergroup', $cohort);
    }
    
    $tablegroupusername = Database::get_main_table('usergroup_rel_user');
    $tablegroupname = Database::get_main_table('usergroup');
    $sql = "
        DELETE FROM
            $tablegroupusername
        WHERE
            user_id = $userid AND
            usergroup_id IN (SELECT id FROM $tablegroupname WHERE description LIKE '{$institutionid}$%')
    ";
    
    Database::query($sql);
    
    $membership = new StdClass();
    $membership->usergroup_id = $cohortid;
    // $membership->group_id = $cohortid; // if groups
    $membership->user_id = $userid;
    // $membership->relation_type = 3; // Lecturer, if groups
    $DB->insert_record('usergroup_rel_user', $membership);
    
    return $cohortname;
}

function local_ent_installer_update_info_data($userid, $fieldid, $data){
    global $DB;
    
    if (!$oldrec = $DB->get_record('user_field_values', array('user_id' => $userid, 'field_id' => $fieldid))){
        $userinfodata = new StdClass;
        $userinfodata->field_id = $fieldid;
        $userinfodata->user_id = $userid;
        $userinfodata->field_value = $data;
        $userinfodata->tms = make_tms(time());
        $DB->insert_record('user_field_values', $userinfodata);
    } else {
        $oldrec->field_value = $data;
        $DB->update_record('user_field_values', $oldrec, 'id');
    }
}

/**
* make a course category for the teacher and give full control to it
*
*
*/
function local_ent_installer_make_teacher_category($user){
    global $DB;
    
    $institutionid = get_config('ent_installer', 'institution_id');    
    $teacherstubcategory  = get_config('ent_installer', 'teacher_stub_category');
    
    if (!$teacherstubcategory) return;
    
    $teachercatidnum = $institutionid.'$'.$user->official_code.'$CAT';
    if (!$DB->record_exists('course_category', array('code' => $teachercatidnum))){
        
        $maxtree = $DB->get_field('course_category', 'MAX(tree_pos)', array('parent_id' => $teacherstubcategory));

        $category = new StdClass();
        $category->name = fullname($user);
        $category->code = $teachercatidnum;
        $category->tree_pos = $maxtree + 1;
        $category->parent_id = $teacherstubcategory;
        $category->auth_course_child = 'TRUE';
        $category->auth_cat_child = 'TRUE';
        $category = $DB->insert_record('course_category', $category);

        // TODO : Assign role on category.
    }
}

function local_ent_installer_generate_email($user){

    $fullname = strtolower($user->firstname.'.'.$user->lastname);
    $fakedomain = get_config('ent_installer', 'fake_email_domain');

    if (empty($fakedomain)){
        $fakedomain = preg_replace('/https?:\/\/.*?\./', '', $_configuration['root_web']);
    }

    return $fullname.'@'.$fakedomain;
}