<?php

define('CLI_SCRIPT', true);
define('ENT_INSTALLER_SYNC_INTERHOST', 1);
define('CHAMILO_INTERNAL', true);

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/main/inc/global.inc.php'); // global chamilo config file.
require_once($_configuration['root_sys'].'local/ent_installer/lib/clilib.php'); // CLI only functions
require_once($_configuration['root_sys'].'plugin/vchamilo/lib.php'); // VChamilo DB boot functions
require_once($_configuration['root_sys'].'local/classes/mootochamlib.php'); // moodle like API
require_once($_configuration['root_sys'].'local/classes/database.class.php'); // moodle like cleaner DB API
require_once($_configuration['root_sys'].'local/classes/textlib.class.php'); // Textlib helpers

$DB = new DatabaseManager();

// Now get cli options.

list($options, $unrecognized) = cli_get_params(
    array(
        'help'              => false,
        'nodes'             => false,
        'logfile'           => true,
    ),
    array(
        'h' => 'help',
        'n' => 'nodes',
        'l' => 'logfile',
        'm' => 'logmode'
    )
);

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

if ($options['help'] || empty($options['nodes'])) {
    $help =
        "Command line ENT Sync worker.
        
        Options:
        -h, --help          Print out this help
        -n, --nodes         Node ids to work with.
        -l, --logfile       the log file to use. No log if not defined
        -m, --logmode       'append' or 'overwrite'

        "; //TODO: localize - to be translated later when everything is finished

    echo $help;
    die;
}

if (empty($options['logmode'])) {
    $options['logmode'] = 'w';
}

if (!empty($options['logfile'])) {
    $LOG = fopen($options['logfile'], $options['logmode']);
}

// Fire sequential synchronisation.
ctrace("Starting worker");
if (isset($LOG)) {
    fputs($LOG, "Starting worker\n");
};

$nodes = explode(',', $options['nodes']);
foreach ($nodes as $nodeid) {
    $host = $DB->get_record('vchamilo', array('id' => $nodeid));
    $cmd = "/usr/bin/php {$_configuration['root_sys']}local/ent_installer/cli/sync_users.php --host={$host->root_web} ";
    $return = 0;
    $output = array();
    ctrace($cmd);
    exec($cmd, $output, $return);
    if ($return) {
        die ("Worker failed" );
    }
    if (isset($LOG)) {
        fputs($LOG, "$cmd\n#-------------------\n");
        fputs($LOG, implode("\n", $output));
    };
    sleep(ENT_INSTALLER_SYNC_INTERHOST);
}

fclose($LOG);

return 0;