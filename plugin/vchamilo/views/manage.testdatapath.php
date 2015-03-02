<?php
/**
 * Tests presence of course directories.
 *
 * @package vchamilo
 * @category plugin
 * @author Moheissen Fabien (fabien.moheissen@gmail.com)
 * @copyright valeisti (http://www.valeisti.fr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

// Loading configuration.
require_once '../../../main/inc/global.inc.php';
require_once $_configuration['root_sys'] . '/local/classes/mootochamlib.php';
require_once $_configuration['root_sys'] . '/local/classes/database.class.php';
require_once $_configuration['root_sys'] . '/plugin/vchamilo/lib/vchamilo_plugin.class.php';
require_once $_configuration['root_sys'].'plugin/vchamilo/lib.php';

$plugininstance = VChamiloPlugin::create();

// Retrieve parameters for database connection test.
$dataroot = $_REQUEST['dataroot'];

if (is_dir($dataroot)) {
    $DIR = opendir($dataroot); 
    $cpt = 0;
    $hasfiles = false;
    while (($file = readdir($DIR)) && !$hasfiles) {
        if (!preg_match("/^\\./", $file)) {
            $hasfiles = true;
        }
    }
    closedir($DIR);

    if ($hasfiles) {
        '<div class="error">'.$plugininstance->get_lang('datapathnotavailable').'</div>';
    } else {
        echo $plugininstance->get_lang('datapathavailable');
    }
} else {
    if (mkdir($dataroot, 02777, true)) {
        echo $plugininstance->get_lang('datapathcreated');
    } else {
        echo '<div class="error">'.$plugininstance->get_lang('couldnotcreatedataroot').'</div>';
    }
    echo stripslashes($dataroot);
}

echo "</p>";

$closestr = $plugininstance->get_lang('closewindow');
echo "<center>";
echo "<input type=\"button\" name=\"close\" value=\"$closestr\" onclick=\"self.close();\" />";
echo "</center>";
