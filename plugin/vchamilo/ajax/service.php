<?php

require_once '../../../main/inc/global.inc.php';
require_once $_configuration['root_sys'] . '/local/classes/mootochamlib.php';
require_once $_configuration['root_sys'] . '/local/classes/database.class.php';
require_once $_configuration['root_sys'] . '/plugin/vchamilo/lib/vchamilo_plugin.class.php';

global $DB;
$DB = new DatabaseManager();

$action = $_GET['what'];
define('CHAMILO_INTERNAL', true);

$plugininstance = VChamiloPlugin::create();
$thisurl = $_configuration['root_web'].'plugin/vchamilo/views/manage.php';

api_protect_admin_script();

if ($action == 'syncthis') {
    $res = include_once($_configuration['root_sys'].'plugin/vchamilo/views/syncparams.controller.php');
    if (!$res) {
        echo '<span class="ok">Success</span>';
    } else {
        echo '<span class="failed">Failure<br/>'.$errors.'</span>';
    }
}