<?php

/**
 * @author Valery Fremaux
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 *
 * Authentication Plugin: LDAP Authentication
 *
 * Authentication using LDAP (Lightweight Directory Access Protocol).
 *
 * 2006-08-28  File created.
 */

if (!defined('CHAMILO_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

// See http://support.microsoft.com/kb/305144 to interprete these values.
if (!defined('AUTH_AD_ACCOUNTDISABLE')) {
    define('AUTH_AD_ACCOUNTDISABLE', 0x0002);
}
if (!defined('AUTH_AD_NORMAL_ACCOUNT')) {
    define('AUTH_AD_NORMAL_ACCOUNT', 0x0200);
}

// UF_DONT_EXPIRE_PASSWD value taken from MSDN directly
if (!defined('UF_DONT_EXPIRE_PASSWD')) {
    define ('UF_DONT_EXPIRE_PASSWD', 0x00010000);
}

if (!defined('LDAP_DEREF_NEVER')) {
    define('LDAP_DEREF_NEVER', 0);
}
if (!defined('LDAP_DEREF_ALWAYS')) {
    define('LDAP_DEREF_ALWAYS', 1);
}
if (!defined('AUTH_REMOVEUSER_KEEP')) {
    define('AUTH_REMOVEUSER_KEEP', 0);
}
if (!defined('AUTH_REMOVEUSER_SUSPEND')) {
    define('AUTH_REMOVEUSER_SUSPEND', 1);
}
if (!defined('AUTH_REMOVEUSER_FULLDELETE')) {
    define('AUTH_REMOVEUSER_FULLDELETE', 2);
}

// The Posix uid and gid of the 'nobody' account and 'nogroup' group.
if (!defined('AUTH_UID_NOBODY')) {
    define('AUTH_UID_NOBODY', -2);
}
if (!defined('AUTH_GID_NOGROUP')) {
    define('AUTH_GID_NOGROUP', -2);
}

/**
 * LDAP authentication object.
 */
class auth_ldap {
    
    var $config;
    
    /**
     * Init plugin config from database settings depending on the plugin auth type.
     */
    function init_plugin() {
        global $_configuration;
        
        // load ldap config into ldap object and map attributes
        include $_configuration['root_sys'].'main/inc/conf/auth.conf.php';
        
        if (is_array($extldap_config['host'])){
            $host = array_shift($extldap_config['host']);
        } else {
            $host = $extldap_config['host'];
        }
        
        $this->config = new StdClass();

        $this->config->host_url = 'ldap://'.$host;
        $this->config->objectclass = str_replace('objectClass=', '', $extldap_config['filter']);
        $this->config->pagesize = LDAP_DEFAULT_PAGESIZE;
        $this->config->passtype = 'plaintext';
        $this->config->bind_pw = $extldap_config['admin_password'];
        $this->config->contexts = $extldap_config['base_dn'];
        $this->config->create_context = '';
        $this->config->pagesize = 20;
        $this->config->ldap_version = $extldap_config['protocol_version'];
        $this->config->bind_dn = $extldap_config['admin_dn'];
        $this->config->opt_deref = LDAP_DEREF_NEVER;
        $this->config->user_type = 'default';
        $this->config->search_sub = '1';
        $this->config->user_attribute = substr($extldap_config['user_search'], 0, strpos($extldap_config['user_search'], '='));
        $this->config->auth_user_create = '';
        $this->config->forcechangepassword = 0;
        $this->config->stdchangepassword = 0;
        $this->config->changepasswordurl = '';
        $this->config->preventpassindb = 0;
        $this->config->removeuser = AUTH_REMOVEUSER_KEEP;
        $this->config->expiration = '';
        $this->config->expiration_warning = '10';
        $this->config->expireattr = '';
        $this->config->memberattribute = '';
        $this->config->memberattribute_isdn = '';
        
        // Try to remove duplicates before using the contexts (to avoid problems in sync_users()).
        $this->config->contexts = explode(';', $this->config->contexts);
        $this->config->contexts = array_map(create_function('$x', 'return textlib::strtolower(trim($x));'), $this->config->contexts);
        $this->config->contexts = implode(';', array_unique($this->config->contexts));
        
        if (empty($this->config->ldapencoding)) {
            $this->config->ldapencoding = 'utf-8';
        }
        if (empty($this->config->user_type)) {
            $this->config->user_type = 'default';
        }

        $ldap_usertypes = ldap_supported_usertypes();
        $this->config->user_type_name = $ldap_usertypes[$this->config->user_type];
        unset($ldap_usertypes);

        $default = ldap_getdefaults();

        // Use defaults if values not given
        foreach ($default as $key => $value) {
            // watch out - 0, false are correct values too
            if (!isset($this->config->{$key}) or $this->config->{$key} == '') {
                $this->config->{$key} = $value[$this->config->user_type];
            }
        }

        // Hack prefix to objectclass
        if (empty($this->config->objectclass)) {
            // Can't send empty filter
            $this->config->objectclass = '(objectClass=*)';
        } else if (stripos($this->config->objectclass, 'objectClass=') === 0) {
            // Value is 'objectClass=some-string-here', so just add ()
            // around the value (filter _must_ have them).
            $this->config->objectclass = '('.$this->config->objectclass.')';
        } else if (strpos($this->config->objectclass, '(') !== 0) {
            // Value is 'some-string-not-starting-with-left-parentheses',
            // which is assumed to be the objectClass matching value.
            // So build a valid filter with it.
            $this->config->objectclass = '(objectClass='.$this->config->objectclass.')';
        } else {
            // There is an additional possible value
            // '(some-string-here)', that can be used to specify any
            // valid filter string, to select subsets of users based
            // on any criteria. For example, we could select the users
            // whose objectClass is 'user' and have the
            // 'enabledMoodleUser' attribute, with something like:
            //
            //   (&(objectClass=user)(enabledMoodleUser=1))
            //
            // In this particular case we don't need to do anything,
            // so leave $this->config->objectclass as is.
        }
        
        $extra = $extldap_user_correspondance['extra'];
        unset($extldap_user_correspondance['extra']);
        $this->userfields = $extldap_user_correspondance;
        $this->userfields += $extra;
        
        $this->userfieldsconsts = array();
        // filter for non requestable fields
        foreach($this->userfields as $key => $value){
            if ($value == 'func') unset($this->userfields[$key]);
            
            // transfer static value to constant fields
            if (preg_match('/^\!/', $value)){
                $this->userfieldsconsts[$key] = preg_replace('/^\!/', '', $value);
                unset($this->userfields[$key]);
            }
        }
    }

    /**
     * Constructor with initialisation.
     */
    function auth_ldap() {
        $this->authtype = 'extldap';
        $this->roleauth = 'auth_ldap';
        $this->errorlogtag = '[AUTH LDAP] ';
        $this->init_plugin();
    }

    /**
     * Returns true if the username and password work and false if they are
     * wrong or don't exist.
     *
     * @param string $username The username (without system magic quotes)
     * @param string $password The password (without system magic quotes)
     *
     * @return bool Authentication success or failure.
     */
    function user_login($username, $password) {
        if (! function_exists('ldap_bind')) {
            print_error('auth_ldapnotinstalled', 'auth_ldap');
            return false;
        }

        if (!$username or !$password) {    // Don't allow blank usernames or passwords
            return false;
        }

        $extusername = textlib::convert($username, 'utf-8', $this->config->ldapencoding);
        $extpassword = textlib::convert($password, 'utf-8', $this->config->ldapencoding);

        $ldapconnection = $this->ldap_connect();
        $ldap_user_dn = $this->ldap_find_userdn($ldapconnection, $extusername);

        // If ldap_user_dn is empty, user does not exist
        if (!$ldap_user_dn) {
            $this->ldap_close();
            return false;
        }

        // Try to bind with current username and password
        $ldap_login = @ldap_bind($ldapconnection, $ldap_user_dn, $extpassword);
        $this->ldap_close();
        if ($ldap_login) {
            return true;
        }
        return false;
    }

    /**
     * Reads user information from ldap and returns it in array()
     *
     * Function should return all information available. If you are saving
     * this information to moodle user-table you should honor syncronization flags
     *
     * @param string $username username
     *
     * @return mixed array with no magic quotes or false on error
     */
    function get_userinfo($username) {
        $extusername = textlib::convert($username, 'utf-8', $this->config->ldapencoding);

        $ldapconnection = $this->ldap_connect();
        if(!($user_dn = $this->ldap_find_userdn($ldapconnection, $extusername))) {
            $this->ldap_close();
            return false;
        }

        $search_attribs = array();
        $attrmap = $this->ldap_attributes();
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

        if (!$user_info_result = ldap_read($ldapconnection, $user_dn, '(objectClass=*)', $search_attribs)) {
            $this->ldap_close();
            return false; // error!
        }

        $user_entry = ldap_get_entries_chamilo($ldapconnection, $user_info_result);
        if (empty($user_entry)) {
            $this->ldap_close();
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
                    continue; // wrong data mapping!
                }
                if (is_array($entry[$value])) {
                    $newval = textlib::convert($entry[$value][0], $this->config->ldapencoding, 'utf-8');
                } else {
                    $newval = textlib::convert($entry[$value], $this->config->ldapencoding, 'utf-8');
                }
                if (!empty($newval)) { // favour ldap entries that are set
                    $ldapval = $newval;
                }
            }
            if (!is_null($ldapval)) {
                $result[$key] = $ldapval;
            }
        }

        $this->ldap_close();
        return $result;
    }

    /**
     * Reads user information from ldap and returns it in an object
     *
     * @param string $username username (with system magic quotes)
     * @return mixed object or false on error
     */
    function get_userinfo_asobj($username) {
        $user_array = $this->get_userinfo($username);
        if ($user_array == false) {
            return false; //error or not found
        }
        $user_array = truncate_userinfo($user_array);
        $user = new stdClass();
        foreach ($user_array as $key=>$value) {
            $user->{$key} = $value;
        }
        return $user;
    }

    /**
     * Returns all usernames from LDAP
     *
     * get_userlist returns all usernames from LDAP
     *
     * @return array
     */
    function get_userlist() {
        return $this->ldap_get_userlist("({$this->config->user_attribute}=*)");
    }

    /**
     * Checks if user exists on LDAP
     *
     * @param string $username
     */
    function user_exists($username) {
        $extusername = textlib::convert($username, 'utf-8', $this->config->ldapencoding);

        // Returns true if given username exists on ldap
        $users = $this->ldap_get_userlist('('.$this->config->user_attribute.'='.ldap_filter_addslashes($extusername).')');
        return count($users);
    }

    /**
     * Creates a new user on LDAP.
     * By using information in userobject
     * Use user_exists to prevent duplicate usernames
     *
     * @param mixed $userobject  Moodle userobject
     * @param mixed $plainpass   Plaintext password
     */
    function user_create($userobject, $plainpass) {
        $extusername = textlib::convert($userobject->username, 'utf-8', $this->config->ldapencoding);
        $extpassword = textlib::convert($plainpass, 'utf-8', $this->config->ldapencoding);

        switch ($this->config->passtype) {
            case 'md5':
                $extpassword = '{MD5}' . base64_encode(pack('H*', md5($extpassword)));
                break;
            case 'sha1':
                $extpassword = '{SHA}' . base64_encode(pack('H*', sha1($extpassword)));
                break;
            case 'plaintext':
            default:
                break; // plaintext
        }

        $ldapconnection = $this->ldap_connect();
        $attrmap = $this->ldap_attributes();

        $newuser = array();

        foreach ($attrmap as $key => $values) {
            if (!is_array($values)) {
                $values = array($values);
            }
            foreach ($values as $value) {
                if (!empty($userobject->$key) ) {
                    $newuser[$value] = textlib::convert($userobject->$key, 'utf-8', $this->config->ldapencoding);
                }
            }
        }

        //Following sets all mandatory and other forced attribute values
        //User should be creted as login disabled untill email confirmation is processed
        //Feel free to add your user type and send patches to paca@sci.fi to add them
        //Moodle distribution

        switch ($this->config->user_type)  {
            case 'edir':
                $newuser['objectClass']   = array('inetOrgPerson', 'organizationalPerson', 'person', 'top');
                $newuser['uniqueId']      = $extusername;
                $newuser['logindisabled'] = 'TRUE';
                $newuser['userpassword']  = $extpassword;
                $uadd = ldap_add($ldapconnection, $this->config->user_attribute.'='.ldap_addslashes($extusername).','.$this->config->create_context, $newuser);
                break;
            case 'rfc2307':
            case 'rfc2307bis':
                // posixAccount object class forces us to specify a uidNumber
                // and a gidNumber. That is quite complicated to generate from
                // Moodle without colliding with existing numbers and without
                // race conditions. As this user is supposed to be only used
                // with Moodle (otherwise the user would exist beforehand) and
                // doesn't need to login into a operating system, we assign the
                // user the uid of user 'nobody' and gid of group 'nogroup'. In
                // addition to that, we need to specify a home directory. We
                // use the root directory ('/') as the home directory, as this
                // is the only one can always be sure exists. Finally, even if
                // it's not mandatory, we specify '/bin/false' as the login
                // shell, to prevent the user from login in at the operating
                // system level (Moodle ignores this).

                $newuser['objectClass']   = array('posixAccount', 'inetOrgPerson', 'organizationalPerson', 'person', 'top');
                $newuser['cn']            = $extusername;
                $newuser['uid']           = $extusername;
                $newuser['uidNumber']     = AUTH_UID_NOBODY;
                $newuser['gidNumber']     = AUTH_GID_NOGROUP;
                $newuser['homeDirectory'] = '/';
                $newuser['loginShell']    = '/bin/false';

                // IMPORTANT:
                // We have to create the account locked, but posixAccount has
                // no attribute to achive this reliably. So we are going to
                // modify the password in a reversable way that we can later
                // revert in user_activate().
                //
                // Beware that this can be defeated by the user if we are not
                // using MD5 or SHA-1 passwords. After all, the source code of
                // Moodle is available, and the user can see the kind of
                // modification we are doing and 'undo' it by hand (but only
                // if we are using plain text passwords).
                //
                // Also bear in mind that you need to use a binding user that
                // can create accounts and has read/write privileges on the
                // 'userPassword' attribute for this to work.

                $newuser['userPassword']  = '*'.$extpassword;
                $uadd = ldap_add($ldapconnection, $this->config->user_attribute.'='.ldap_addslashes($extusername).','.$this->config->create_context, $newuser);
                break;
            case 'ad':
                // User account creation is a two step process with AD. First you
                // create the user object, then you set the password. If you try
                // to set the password while creating the user, the operation
                // fails.

                // Passwords in Active Directory must be encoded as Unicode
                // strings (UCS-2 Little Endian format) and surrounded with
                // double quotes. See http://support.microsoft.com/?kbid=269190
                if (!function_exists('mb_convert_encoding')) {
                    print_error('auth_ldap_no_mbstring', 'auth_ldap');
                }

                // Check for invalid sAMAccountName characters.
                if (preg_match('#[/\\[\]:;|=,+*?<>@"]#', $extusername)) {
                    print_error ('auth_ldap_ad_invalidchars', 'auth_ldap');
                }

                // First create the user account, and mark it as disabled.
                $newuser['objectClass'] = array('top', 'person', 'user', 'organizationalPerson');
                $newuser['sAMAccountName'] = $extusername;
                $newuser['userAccountControl'] = AUTH_AD_NORMAL_ACCOUNT |
                                                 AUTH_AD_ACCOUNTDISABLE;
                $userdn = 'cn='.ldap_addslashes($extusername).','.$this->config->create_context;
                if (!ldap_add($ldapconnection, $userdn, $newuser)) {
                    print_error('auth_ldap_ad_create_req', 'auth_ldap');
                }

                // Now set the password
                unset($newuser);
                $newuser['unicodePwd'] = mb_convert_encoding('"' . $extpassword . '"',
                                                             'UCS-2LE', 'UTF-8');
                if(!ldap_modify($ldapconnection, $userdn, $newuser)) {
                    // Something went wrong: delete the user account and error out
                    ldap_delete ($ldapconnection, $userdn);
                    print_error('auth_ldap_ad_create_req', 'auth_ldap');
                }
                $uadd = true;
                break;
            default:
               print_error('auth_ldap_unsupportedusertype', 'auth_ldap', '', $this->config->user_type_name);
        }
        $this->ldap_close();
        return $uadd;
    }

    /**
     * Called when the user record is updated.
     *
     * Modifies user in external LDAP server. It takes olduser (before
     * changes) and newuser (after changes) compares information and
     * saves modified information to external LDAP server.
     *
     * @param mixed $olduser     Userobject before modifications    (without system magic quotes)
     * @param mixed $newuser     Userobject new modified userobject (without system magic quotes)
     * @return boolean result
     *
     */
    function user_update($olduser, $newuser) {
        global $USER;

        if (isset($olduser->username) and isset($newuser->username) and $olduser->username != $newuser->username) {
            error_log($this->errorlogtag.get_string('renamingnotallowed', 'auth_ldap'));
            return false;
        }

        if (isset($olduser->auth) and $olduser->auth != $this->authtype) {
            return true; // just change auth and skip update
        }

        $attrmap = $this->ldap_attributes();
        // Before doing anything else, make sure we really need to update anything
        // in the external LDAP server.
        $update_external = false;
        foreach ($attrmap as $key => $ldapkeys) {
            if (!empty($this->config->{'field_updateremote_'.$key})) {
                $update_external = true;
                break;
            }
        }
        if (!$update_external) {
            return true;
        }

        $extoldusername = textlib::convert($olduser->username, 'utf-8', $this->config->ldapencoding);

        $ldapconnection = $this->ldap_connect();

        $search_attribs = array();
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

        if(!($user_dn = $this->ldap_find_userdn($ldapconnection, $extoldusername))) {
            return false;
        }

        $user_info_result = ldap_read($ldapconnection, $user_dn, '(objectClass=*)', $search_attribs);
        if ($user_info_result) {
            $user_entry = ldap_get_entries_moodle($ldapconnection, $user_info_result);
            if (empty($user_entry)) {
                $attribs = join (', ', $search_attribs);
                error_log($this->errorlogtag.get_string('updateusernotfound', 'auth_ldap',
                                                          array('userdn'=>$user_dn,
                                                                'attribs'=>$attribs)));
                return false; // old user not found!
            } else if (count($user_entry) > 1) {
                error_log($this->errorlogtag.get_string('morethanoneuser', 'auth_ldap'));
                return false;
            }

            $user_entry = array_change_key_case($user_entry[0], CASE_LOWER);

            foreach ($attrmap as $key => $ldapkeys) {
                // Only process if the moodle field ($key) has changed and we
                // are set to update LDAP with it
                if (isset($olduser->$key) and isset($newuser->$key)
                  and $olduser->$key !== $newuser->$key
                  and !empty($this->config->{'field_updateremote_'. $key})) {
                    // For ldap values that could be in more than one
                    // ldap key, we will do our best to match
                    // where they came from
                    $ambiguous = true;
                    $changed   = false;
                    if (!is_array($ldapkeys)) {
                        $ldapkeys = array($ldapkeys);
                    }
                    if (count($ldapkeys) < 2) {
                        $ambiguous = false;
                    }

                    $nuvalue = textlib::convert($newuser->$key, 'utf-8', $this->config->ldapencoding);
                    empty($nuvalue) ? $nuvalue = array() : $nuvalue;
                    $ouvalue = textlib::convert($olduser->$key, 'utf-8', $this->config->ldapencoding);

                    foreach ($ldapkeys as $ldapkey) {
                        $ldapkey   = $ldapkey;
                        $ldapvalue = $user_entry[$ldapkey][0];
                        if (!$ambiguous) {
                            // Skip update if the values already match
                            if ($nuvalue !== $ldapvalue) {
                                // This might fail due to schema validation
                                if (@ldap_modify($ldapconnection, $user_dn, array($ldapkey => $nuvalue))) {
                                    continue;
                                } else {
                                    error_log($this->errorlogtag.get_string ('updateremfail', 'auth_ldap',
                                                                             array('errno'=>ldap_errno($ldapconnection),
                                                                                   'errstring'=>ldap_err2str(ldap_errno($ldapconnection)),
                                                                                   'key'=>$key,
                                                                                   'ouvalue'=>$ouvalue,
                                                                                   'nuvalue'=>$nuvalue)));
                                    continue;
                                }
                            }
                        } else {
                            // Ambiguous. Value empty before in Moodle (and LDAP) - use
                            // 1st ldap candidate field, no need to guess
                            if ($ouvalue === '') { // value empty before - use 1st ldap candidate
                                // This might fail due to schema validation
                                if (@ldap_modify($ldapconnection, $user_dn, array($ldapkey => $nuvalue))) {
                                    $changed = true;
                                    continue;
                                } else {
                                    error_log($this->errorlogtag.get_string ('updateremfail', 'auth_ldap',
                                                                             array('errno'=>ldap_errno($ldapconnection),
                                                                                   'errstring'=>ldap_err2str(ldap_errno($ldapconnection)),
                                                                                   'key'=>$key,
                                                                                   'ouvalue'=>$ouvalue,
                                                                                   'nuvalue'=>$nuvalue)));
                                    continue;
                                }
                            }

                            // We found which ldap key to update!
                            if ($ouvalue !== '' and $ouvalue === $ldapvalue ) {
                                // This might fail due to schema validation
                                if (@ldap_modify($ldapconnection, $user_dn, array($ldapkey => $nuvalue))) {
                                    $changed = true;
                                    continue;
                                } else {
                                    error_log($this->errorlogtag.get_string ('updateremfail', 'auth_ldap',
                                                                             array('errno'=>ldap_errno($ldapconnection),
                                                                                   'errstring'=>ldap_err2str(ldap_errno($ldapconnection)),
                                                                                   'key'=>$key,
                                                                                   'ouvalue'=>$ouvalue,
                                                                                   'nuvalue'=>$nuvalue)));
                                    continue;
                                }
                            }
                        }
                    }

                    if ($ambiguous and !$changed) {
                        error_log($this->errorlogtag.get_string ('updateremfailamb', 'auth_ldap',
                                                                 array('key'=>$key,
                                                                       'ouvalue'=>$ouvalue,
                                                                       'nuvalue'=>$nuvalue)));
                    }
                }
            }
        } else {
            error_log($this->errorlogtag.get_string ('usernotfound', 'auth_ldap'));
            $this->ldap_close();
            return false;
        }

        $this->ldap_close();
        return true;

    }

    /**
     * Returns user attribute mappings between moodle and LDAP
     *
     * @return array
     */

    function ldap_attributes() {
        $moodleattributes = array();
        foreach (array_keys($this->userfields) as $field) {
            $moodleattributes[$field] = textlib::strtolower(trim($this->userfields[$field]));
        }
        return $moodleattributes;
    }

    /**
     * Returns all usernames from LDAP
     *
     * @param $filter An LDAP search filter to select desired users
     * @return array of LDAP user names converted to UTF-8
     */
    function ldap_get_userlist($filter='*') {
        $fresult = array();

        $ldapconnection = $this->ldap_connect();

        if ($filter == '*') {
           $filter = '(&('.$this->config->user_attribute.'=*)'.$this->config->objectclass.')';
        }

        $contexts = explode(';', $this->config->contexts);
        if (!empty($this->config->create_context)) {
            array_push($contexts, $this->config->create_context);
        }

        $ldap_pagedresults = ldap_paged_results_supported($this->config->ldap_version);
        foreach ($contexts as $context) {
            $context = trim($context);
            if (empty($context)) {
                continue;
            }

            do {
                if ($ldap_pagedresults) {
                    ldap_control_paged_result($ldapconnection, $this->config->pagesize, true, $ldap_cookie);
                }
                if ($this->config->search_sub) {
                    // Use ldap_search to find first user from subtree.
                    $ldap_result = ldap_search($ldapconnection, $context, $filter, array($this->config->user_attribute));
                } else {
                    // Search only in this context.
                    $ldap_result = ldap_list($ldapconnection, $context, $filter, array($this->config->user_attribute));
                }
                if(!$ldap_result) {
                    continue;
                }
                if ($ldap_pagedresults) {
                    ldap_control_paged_result_response($ldapconnection, $ldap_result, $ldap_cookie);
                }
                $users = ldap_get_entries_moodle($ldapconnection, $ldap_result);
                // Add found users to list.
                for ($i = 0; $i < count($users); $i++) {
                    $extuser = textlib::convert($users[$i][$this->config->user_attribute][0],
                                                $this->config->ldapencoding, 'utf-8');
                    array_push($fresult, $extuser);
                }
                unset($ldap_result); // Free mem.
            } while ($ldap_pagedresults && !empty($ldap_cookie));
        }

        // If paged results were used, make sure the current connection is completely closed
        $this->ldap_close($ldap_pagedresults);
        return $fresult;
    }

    /**
     * Connect to the LDAP server, using the plugin configured
     * settings. It's actually a wrapper around ldap_connect_moodle()
     *
     * @return resource A valid LDAP connection (or dies if it can't connect)
     */
    function ldap_connect() {
        // Cache ldap connections. They are expensive to set up
        // and can drain the TCP/IP ressources on the server if we
        // are syncing a lot of users (as we try to open a new connection
        // to get the user details). This is the least invasive way
        // to reuse existing connections without greater code surgery.
        if(!empty($this->ldapconnection)) {
            $this->ldapconns++;
            return $this->ldapconnection;
        }

        if($ldapconnection = ldap_connect_chamilo($this->config->host_url, $this->config->ldap_version,
                                                 $this->config->user_type, $this->config->bind_dn,
                                                 $this->config->bind_pw, $this->config->opt_deref,
                                                 $debuginfo)) {
            $this->ldapconns = 1;
            $this->ldapconnection = $ldapconnection;
            return $ldapconnection;
        }

        ctrace('Host : '.$this->config->host_url);
        ctrace('Version : '.$this->config->ldap_version);
        ctrace('BindDN : '.$this->config->bind_dn);
        ctrace('BindPass : '.$this->config->bind_pw);
        ctrace('Deref : '.$this->config->opt_deref);
        print_error('auth_ldap_noconnect_all', 'local_ent_installer', true, $debuginfo);
        ctrace('aborting.');
        die;
    }

    /**
     * Disconnects from a LDAP server
     *
     * @param force boolean Forces closing the real connection to the LDAP server, ignoring any
     *                      cached connections. This is needed when we've used paged results
     *                      and want to use normal results again.
     */
    function ldap_close($force=false) {
        $this->ldapconns--;
        if (($this->ldapconns == 0) || ($force)) {
            $this->ldapconns = 0;
            @ldap_close($this->ldapconnection);
            unset($this->ldapconnection);
        }
    }

    /**
     * Search specified contexts for username and return the user dn
     * like: cn=username,ou=suborg,o=org. It's actually a wrapper
     * around ldap_find_userdn().
     *
     * @param resource $ldapconnection a valid LDAP connection
     * @param string $extusername the username to search (in external LDAP encoding, no db slashes)
     * @return mixed the user dn (external LDAP encoding) or false
     */
    function ldap_find_userdn($ldapconnection, $extusername) {
        $ldap_contexts = explode(';', $this->config->contexts);
        if (!empty($this->config->create_context)) {
            array_push($ldap_contexts, $this->config->create_context);
        }

        return ldap_find_userdn($ldapconnection, $extusername, $ldap_contexts, $this->config->objectclass,
                                $this->config->user_attribute, $this->config->search_sub);
    }

} // End of the class
