<?php

/**
 *
 * @package    local
 * @subpackage ent_installer
 * @copyright  2014 Valery Fremaux
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
global $debuglevel;
global $debugdisplay;
global $DB;
$debuglevel = 4;
$debugdisplay = 4;

define('CLI_SCRIPT', true); // for Moodle imported code
define('CHAMILO_INTERNAL', true);
global $CLI_VCHAMILO_PRECHECK;

$CLI_VCHAMILO_PRECHECK = true; // force first config to be minimal

// require_once '../../../main/inc/global.inc.php';
require(dirname(dirname(dirname(dirname(__FILE__)))).'/main/inc/conf/configuration.php'); // get boot config

ini_set('debug_display', 1);
ini_set('debug_level', E_ALL);

require_once($_configuration['root_sys'].'local/classes/mootochamlib.php');       // ldap primitives
require_once($_configuration['root_sys'].'local/ent_installer/lib/clilib.php');       // cli only functions
require_once($_configuration['root_sys'].'local/ent_installer/lib/entinstalllib.php');       // ldap primitives
require_once($_configuration['root_sys'].'local/ent_installer/lib/ldaplib.php');       // ldap primitives
require_once($_configuration['root_sys'].'local/classes/textlib.class.php');       // ldap primitives
require_once($_configuration['root_sys'].'local/ent_installer/auth.php');       // ldap primitives

// Ensure errors are well explained

// now get cli options
list($options, $unrecognized) = cli_get_params(
    array(
        'interactive'       => false,
        'verbose'           => false,
        'help'              => false,
        'simulate'          => false,
        'role'              => false,
        'host'              => false,
        'force'              => false,
    ),
    array(
        'h' => 'help',
        'i' => 'interactive',
        'f' => 'force',
        'v' => 'verbose',
        's' => 'simulate',
        'r' => 'role',
        'H' => 'host'
    )
);

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

if ($options['help']) {
    $help =
"Command line ENT User Synchronizer.

Options:
--interactive         Breaks on key steps of the process and ask for interactive continue
--verbose               Provides lot of output
-h, --help          Print out this help
-s, --simulate      Get all data for simulation but will NOT process any writing in database.
-f, --force          Force updating all data.
-r, --role          Specify a role of users to import (eleve,enseignant,admnistration).
-H, --host          Set the host (physical or virtual) to operate on

"; //TODO: localize - to be translated later when everything is finished

    echo $help;
    die;
}

if (!empty($options['host'])){
    // arms the vchamilo switching
    ctrace('Arming for '.$options['host']); // mtrace not yet available.
    define('CLI_VCHAMILO_OVERRIDE', $options['host']);
}

if (!empty($options['verbose'])) {
    ctrace("Verbose mode: enabled\n");
}

// replay full config whenever. If vchamilo switch is armed, will switch now config
require($_configuration['root_sys'].'main/inc/conf/configuration.php'); // do REALLY force configuration to play again, or the following call will not have config twicked (require_once)
require($_configuration['root_sys'].'main/inc/global.inc.php'); // global chamilo config file.
ctrace('Config check : playing for '.$_configuration['root_web']);

$DB = new DatabaseManager();

// get ldap params from real ldap plugin
$ldapauth = new auth_ldap();

// run the customised synchro
local_ent_installer_sync_users($ldapauth, $options);
