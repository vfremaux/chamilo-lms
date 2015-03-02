<?php

// this is a root script intended to add to the generated config file of Chamilo the additional snippet
// of config code that provides additional settings and virtualisation hooking.

$configfilelocation = dirname(dirname(dirname(dirname(__FILE__)))).'/main/inc/conf/configuration.php';
$configsavefilelocation = dirname(dirname(dirname(dirname(__FILE__)))).'/main/inc/conf/configuration.save.php';

if (!file_exists($configfilelocation)) {
    die("Cannot find chamilo config file at $configfilelocation");
}

$processUser = posix_getpwuid(posix_geteuid());

if (($processUser['name'] !== 'root') && ($processUser['name'] !== 'lmsadm')) {
    print $processUser['name'];
    die("Only root or LMS owner lmsadm can use this script\n");
}

$configfile = implode('', file($configfilelocation));

$append = '';
// this will detect the proto and add forcing HTTPS
if (preg_match('/\[\'root_web\'\]\s*=\s*\'https/', $configfile)) {
    $append = "\$_configuration['force_https_forwarded_proto']  = 1;\n\n";
}

$append .= "

// this is important for home pages
\$_configuration['multiple_access_urls'] = true;

require_once \$_configuration['root_sys'].'/local/libloader.php';

// this fragment will trap the CLI scripts trying to work for a virtual node, and
// needing booting a first elementary configuration based on main config 
if (isset(\$CLI_VCHAMILO_PRECHECK) && \$CLI_VCHAMILO_PRECHECK == true){
    \$CLI_VCHAMILO_PRECHECK = false;
    return;
}

include_once \$_configuration['root_sys'].'plugin/vchamilo/lib.php';

vchamilo_hook_configuration(\$_configuration);

";

if (!preg_match('/vchamilo_hook_configuration/s', $configfile)) {
    if (!copy($configfilelocation, $configsavefilelocation)) {
        die ("Could not make backup file of config. Aborting config fix.\n");
    }

    $configfile = $configfile.$append;

    $CONFIG = fopen($configfilelocation, 'wb');
    fputs($CONFIG, $configfile);
    fclose($CONFIG);
    echo "VChamilo config file patched\n";
} else {
    echo "Chamilo config file unchanged. Vchamilo already in place.\n";
}

// Second step is to force some configuration changes
// require_once '../../../main/inc/global.inc.php';
define('CLI_SCRIPT', true);
global $CLI_VCHAMILO_PRECHECK;
$CLI_VCHAMILO_PRECHECK = true;
require dirname(dirname(dirname(dirname(__FILE__)))).'/main/inc/conf/configuration.php'; // get boot config
require_once($_configuration['root_sys'].'local/classes/mootochamlib.php');
require_once($_configuration['root_sys'].'/main/inc/global.inc.php');
require_once($_configuration['root_sys'].'local/classes/database.class.php');

$DB = new DatabaseManager();
echo $DB->get_info();

echo "Starting fixing DB configuration\n";
// Add theme 
$config = new StdClass();
$config->variable = 'stylesheets';
$config->type = 'textfield';
$config->category = 'stylesheets';
$config->selected_value = 'atrium';
$config->title = '';
$config->access_url = '1';
$config->access_url_changeable = '1';
$config->access_url_locked = '0';
if (!$oldrec = $DB->get_record('settings_current', array('variable' => 'stylesheets', 'category' => 'stylesheets'))) {
    $DB->insert_record('settings_current', $config);
} else {
    $config->id = $oldrec->id;
    $DB->update_record('settings_current', $config, 'id');
}

$chamilo_settings = array(
    // VChamilo settings
    array('variable' => 'status','subkey' => 'vchamilo','type' => 'setting','category' => 'Plugins','selected_value' => 'installed','title' => 'vchamilo','comment' => NULL,'scope' => NULL,'subkeytext' => NULL,'access_url' => '1','access_url_changeable' => '1','access_url_locked' => '0'),
    array('variable' => 'vchamilo_enable_virtualisation','subkey' => 'vchamilo','type' => 'setting','category' => 'Plugins','selected_value' => '1','title' => 'vchamilo','comment' => NULL,'scope' => NULL,'subkeytext' => NULL,'access_url' => '1','access_url_changeable' => '1','access_url_locked' => '0'),
    array('variable' => 'vchamilo_course_real_root','subkey' => 'vchamilo','type' => 'setting','category' => 'Plugins','selected_value' => '/data/chamilodata/courses','title' => 'vchamilo','comment' => NULL,'scope' => NULL,'subkeytext' => NULL,'access_url' => '1','access_url_changeable' => '1','access_url_locked' => '0'),
    array('variable' => 'vchamilo_archive_real_root','subkey' => 'vchamilo','type' => 'setting','category' => 'Plugins','selected_value' => '/data/chamilodata/archive','title' => 'vchamilo','comment' => NULL,'scope' => NULL,'subkeytext' => NULL,'access_url' => '1','access_url_changeable' => '1','access_url_locked' => '0'),
    array('variable' => 'vchamilo_home_real_root','subkey' => 'vchamilo','type' => 'setting','category' => 'Plugins','selected_value' => '/data/chamilodata/home','title' => 'vchamilo','comment' => NULL,'scope' => NULL,'subkeytext' => NULL,'access_url' => '1','access_url_changeable' => '1','access_url_locked' => '0'),
    array('variable' => 'vchamilo_httpproxyhost','subkey' => 'vchamilo','type' => 'setting','category' => 'Plugins','selected_value' => 'proxy.realyce.fr','title' => 'vchamilo','comment' => NULL,'scope' => NULL,'subkeytext' => NULL,'access_url' => '1','access_url_changeable' => '1','access_url_locked' => '0'),
    array('variable' => 'vchamilo_httpproxyport','subkey' => 'vchamilo','type' => 'setting','category' => 'Plugins','selected_value' => '8181','title' => 'vchamilo','comment' => NULL,'scope' => NULL,'subkeytext' => NULL,'access_url' => '1','access_url_changeable' => '1','access_url_locked' => '0'),
    array('variable' => 'vchamilo_httpproxybypass','subkey' => 'vchamilo','type' => 'setting','category' => 'Plugins','selected_value' => '','title' => 'vchamilo','comment' => NULL,'scope' => NULL,'subkeytext' => NULL,'access_url' => '1','access_url_changeable' => '1','access_url_locked' => '0'),
    array('variable' => 'vchamilo_httpproxyuser','subkey' => 'vchamilo','type' => 'setting','category' => 'Plugins','selected_value' => '','title' => 'vchamilo','comment' => NULL,'scope' => NULL,'subkeytext' => NULL,'access_url' => '1','access_url_changeable' => '1','access_url_locked' => '0'),
    array('variable' => 'vchamilo_httpproxypassword','subkey' => 'vchamilo','type' => 'setting','category' => 'Plugins','selected_value' => '','title' => 'vchamilo','comment' => NULL,'scope' => NULL,'subkeytext' => NULL,'access_url' => '1','access_url_changeable' => '1','access_url_locked' => '0'),
    array('variable' => 'vchamilo_cmd_mysql','subkey' => 'vchamilo','type' => 'setting','category' => 'Plugins','selected_value' => '/usr/bin/mysql','title' => 'vchamilo','comment' => NULL,'scope' => NULL,'subkeytext' => NULL,'access_url' => '1','access_url_changeable' => '1','access_url_locked' => '0'),
    array('variable' => 'vchamilo_cmd_mysqldump','subkey' => 'vchamilo','type' => 'setting','category' => 'Plugins','selected_value' => '/usr/bin/mysqldump','title' => 'vchamilo','comment' => NULL,'scope' => NULL,'subkeytext' => NULL,'access_url' => '1','access_url_changeable' => '1','access_url_locked' => '0'),
    array('variable' => 'vchamilo_submit_button','subkey' => 'vchamilo','type' => 'setting','category' => 'Plugins','selected_value' => '','title' => 'vchamilo','comment' => NULL,'scope' => NULL,'subkeytext' => NULL,'access_url' => '1','access_url_changeable' => '1','access_url_locked' => '0'),

    // ENT installer settings
    array('variable' => 'status','subkey' => 'ent_installer','type' => 'setting','category' => 'Plugins','selected_value' => 'installed','title' => 'ent_installer','access_url' => '1','access_url_changeable' => '1','access_url_locked' => '0'),
    array('variable' => 'ent_installer_institution_id','subkey' => 'ent_installer','type' => 'setting','category' => 'Plugins','selected_value' => '0','title' => '','access_url' => '1','access_url_changeable' => '0','access_url_locked' => '0'),
    array('variable' => 'ent_installer_last_sync_date','subkey' => 'ent_installer','type' => 'setting','category' => 'Plugins','selected_value' => '0','title' => '','access_url' => '1','access_url_changeable' => '0','access_url_locked' => '0'),
    array('variable' => 'ent_installer_enable_sync','subkey' => 'ent_installer','type' => 'setting','category' => 'Plugins','selected_value' => '0','title' => '','access_url' => '1','access_url_changeable' => '0','access_url_locked' => '0'),
    array('variable' => 'ent_installer_real_used_auth','subkey' => 'ent_installer','type' => 'setting','category' => 'Plugins','selected_value' => '0','title' => '','access_url' => '1','access_url_changeable' => '0','access_url_locked' => '0'),
    array('variable' => 'ent_installer_fake_email_domain','subkey' => 'ent_installer','type' => 'setting','category' => 'Plugins','selected_value' => 'foo.atrium-paca.fr', 'title' => '','access_url' => '1','access_url_changeable' => '0','access_url_locked' => '0'),

    // Miscelaneous settings
    array('variable' => 'allow_course_theme','subkey' => NULL,'type' => 'radio','category' => 'Course','selected_value' => 'false','title' => 'AllowCourseThemeTitle','comment' => 'AllowCourseThemeComment','scope' => NULL,'subkeytext' => NULL,'access_url' => '1','access_url_changeable' => '0','access_url_locked' => '0')
);

echo "Starting fixing DB current_settings\n";
foreach($chamilo_settings as $setting) {
    $obj = (object)$setting;
    echo('Fixing current setting "'.$obj->variable.'"|"'.$obj->subkey.'" to "'.$obj->selected_value.'" '."\n");
    if (!$oldrec = $DB->record_exists('settings_current', array('variable' => $obj->variable, 'subkey' => $obj->subkey))) {
        $DB->insert_record('settings_current', $obj);
    } else {
        $config->id = $oldrec->id;
        $DB->update_record('settings_current', $obj, 'id');
    }
}

// force install vchamilo plugin
require_once($_configuration['root_sys'].'plugin/vchamilo/install.php');
// force install vchamilo plugin
require_once($_configuration['root_sys'].'plugin/ent_installer/install.php');


return 0;
