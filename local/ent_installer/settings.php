<?php

defined('MOODLE_INTERNAL') || die;
require_once $CFG->dirroot.'/local/ent_installer/adminlib.php';

if ($hassiteconfig) { // needs this condition or there is error on login page
    $settings = new admin_settingpage('local_ent_installer', get_string('pluginname', 'local_ent_installer'));

    $settings->add(new admin_setting_heading('head0', get_string('datasync', 'local_ent_installer'), ''));

    $settings->add(new admin_setting_configcheckbox('local_ent_installer/sync_enable', get_string('configsyncenable', 'local_ent_installer'), get_string('configsyncenabledesc', 'local_ent_installer'), ''));

    $settings->add(new admin_setting_configtext('local_ent_installer/institution_id', get_string('configinstitutionid', 'local_ent_installer'), get_string('configinstitutioniddesc', 'local_ent_installer'), ''));

    $settings->add(new admin_setting_configdatetime('local_ent_installer/last_sync_date', get_string('configlastsyncdate', 'local_ent_installer'),
                       get_string('configlastsyncdatedesc', 'local_ent_installer'), ''));

    $authoptions = array(
        'ldap' => 'LDAP',
        'cas' => 'CAS',
        'saml' => 'SAML');
    $settings->add(new admin_setting_configselect('local_ent_installer/real_used_auth', get_string('configrealauth', 'local_ent_installer'), get_string('configrealauthdesc', 'local_ent_installer'), 'ldap', $authoptions));

    $settings->add(new admin_setting_configcheckbox('local_ent_installer/build_teacher_category', get_string('configbuildteachercategory', 'local_ent_installer'), get_string('configbuildteachercategorydesc', 'local_ent_installer'), ''));

    $categoryoptions = $DB->get_records_menu('course_categories', array(), 'parent,sortorder', 'id, name');
    $settings->add(new admin_setting_configselect('local_ent_installer/teacher_stub_category', get_string('configteacherstubcategory', 'local_ent_installer'), get_string('configteacherstubcategorydesc', 'local_ent_installer'), 'ldap', $categoryoptions));

    $settings->add(new admin_setting_configcheckbox('local_ent_installer/update_institution_structure', get_string('configupdateinstitutionstructure', 'local_ent_installer'), get_string('configupdateinstitutionstructuredesc', 'local_ent_installer'), ''));

    $settings->add(new admin_setting_heading('head1', get_string('structuresearch', 'local_ent_installer'), ''));

    $settings->add(new admin_setting_configtext('local_ent_installer/structure_context', get_string('configstructurecontext', 'local_ent_installer'), get_string('configstructurecontextdesc', 'local_ent_installer'), ''));

    $settings->add(new admin_setting_configtext('local_ent_installer/structure_id_attribute', get_string('configstructureid', 'local_ent_installer'), get_string('configstructureiddesc', 'local_ent_installer'), ''));

    $settings->add(new admin_setting_configtext('local_ent_installer/structure_name_attribute', get_string('configstructurename', 'local_ent_installer'), get_string('configstructurenamedesc', 'local_ent_installer'), ''));

    $getidstr = get_string('configgetinstitutionidservice', 'local_ent_installer');
    $settings->add(new admin_setting_heading('local_ent_installer_searchid', get_string('configgetid', 'local_ent_installer'), "<a href=\"{$CFG->wwwroot}/local/ent_installer/getid.php\">$getidstr</a>"));

    $ADMIN->add('localplugins', $settings);
}

