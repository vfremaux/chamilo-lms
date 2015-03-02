<?php

/**
 *
 * @package    local
 * @subpackage ent_installer
 * @copyright  2014 Valery Fremaux
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define('CLI_SCRIPT', true);

define('ENT_INSTALLER_SYNC_MAX_WORKERS', 2);
define('CHAMILO_INTERNAL', true);
define('JOB_INTERLEAVE', 2);

echo "Running Host synchronisation tool\n";
echo "=================================\n";

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/main/inc/global.inc.php'); // global chamilo config file.
require_once($_configuration['root_sys'].'local/ent_installer/lib/clilib.php'); // CLI only functions
require_once($_configuration['root_sys'].'local/classes/mootochamlib.php'); // moodle like API
require_once($_configuration['root_sys'].'local/classes/database.class.php'); // moodle like cleaner DB API
require_once($_configuration['root_sys'].'local/classes/textlib.class.php'); // Textlib helpers
require_once($_configuration['root_sys'].'plugin/vchamilo/lib.php'); // VChamilo DB boot functions

echo "end requires \n";

$DB = new DatabaseManager();

// Ensure options are blanck;
unset($options);

// Now get cli options.

list($options, $unrecognized) = cli_get_params(
    array(
        'help'             => false,
        'workers'          => false,
        'distributed'      => false,
        'logroot'          => false,
        'verbose'          => false,
    ),
    array(
        'h' => 'help',
        'w' => 'workers',
        'd' => 'distributed',
        'l' => 'logroot',
        'v' => 'verbose',
    )
);

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

if ($options['help']) {
    $help =
        "Command line ENT Sync worker.

        Options:
        -h, --help          Print out this help
        -w, --workers       Number of workers.
        -d, --distributed   Distributed operations.
        -l, --logroot       Root directory for logs.
        -v, --verbose       More verbose.

        "; //TODO: localize - to be translated later when everything is finished

    echo $help;
    die;
}

if (!empty($options['verbose'])) {
    echo "checking options\n";
}

if ($options['workers'] === false) {
    $options['workers'] = ENT_INSTALLER_SYNC_MAX_WORKERS;
}

if (!empty($options['logroot'])) {
    $logroot = $options['logroot'];
} else {
    $logroot = api_get_path(TO_SYS, SYS_ARCHIVE_PATH);
}

$allhosts = $DB->get_records('vchamilo', array('visible' => 1));

// Make worker lists

if (!empty($options['verbose'])) {
    echo "Making Job List\n";
}

$joblists = array();
$i = 0;
foreach ($allhosts as $h) {
    $joblist[$i][] = $h->id;
    $i++;
    if ($i == $options['workers']) {
        $i = 0;
    }
}

// Start spreading workers, and pass the list of vhost ids. Launch workers in background
// Linux only implementation.

$i = 1;
foreach ($joblist as $jl) {
    $jobids = array();
    if (!empty($jl)) {
        $hids = implode(',', $jl);
        $workercmd = "/usr/bin/php {$_configuration['root_sys']}/local/ent_installer/cli/sync_hosts_worker.php --nodes=\"$hids\" --logfile={$logroot}/ent_sync_log_{$i}.log ";
        if ($options['distributed']) {
            $workercmd .= ' &';
        }
        ctrace("Executing $workercmd\n######################################################\n");
        $output = array();
        exec($workercmd, $output, $return);
        if ($return) {
            die("Worker ended with error");
        }
        if (!$options['distributed']) {
            ctrace(implode("\n", $output));
        }
        $i++;
        sleep(JOB_INTERLEAVE);
    }
}