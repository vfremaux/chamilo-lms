<?php

/**
 * get strings from a special install file, whatever
 * moodle active language is on
 * @return the string or the marked key if missing
 *
 */
function ent_installer_string($stringkey) {
    global $CFG;
    static $installstrings = null;

    if (empty($installstrings)) {
        require_once $CFG->dirroot.'/local/ent_installer/db/install_strings.php';
        $installstrings = $string; // loads string array once
    }

    if (!array_key_exists($stringkey, $installstrings)){
        return "[[install::$stringkey]]";
    }
    return $installstrings[$stringkey];
}

function ent_installer_check_jquery() {
    global $JQUERYVERSION;

    $current = '1.8.2';

    if (empty($JQUERYVERSION)) {
        $JQUERYVERSION = '1.8.2';
        require_js('jquery-'.$current.'.min.js', 'local_ent_installer');
    } else {
        if ($JQUERYVERSION < $current) {
            echo('the previously loaded version of jquery is lower than required. This may cause issues to tracker reports. Programmers might consider upgrading JQuery version in the component that preloads JQuery library.');
        }
    }
}
