<?php

/**
* this function set will map standard moodle API calls to chamilo
* internal primitives. This avoids too many changes to do in imported 
* code
*
*/
function get_config($module, $key, $isplugin = true) {
    global $_configuration, $DB;
    static $static_settings;

    if (!isset($static_settings)) {
        include_once $_configuration['root_sys'].'local/ent_installer/static_settings.php';
    }

    if ($isplugin){
        $key = $module.'_'.$key;
    }

    if ($module == 'ent_installer') {
        $dyna_setting = $DB->get_field(TABLE_MAIN_SETTINGS_CURRENT, 'selected_value', array('subkey' => 'ent_installer', 'variable' => $key));
        if (!is_null($dyna_setting)) {
            return $dyna_setting;
        }

        if(empty($config)){
            ctrace("Wrap to static setting $module,$key ");
            if (array_key_exists($key, $static_settings)){
                return $static_settings[$key];
            }
        }
    } else {
        return $DB->get_field(TABLE_MAIN_SETTINGS_CURRENT, 'selected_value', array('variable' => $key, 'subkey' => $module));
    }
}

function set_config($key, $value, $module, $isplugin = false) {

    if ($isplugin) {
        $key = $module.'_'.$key;
    }

    // ensure setting is actually in database
    api_update_setting($value, $module, $key);
}

/**
* gets a string from a component
*
*/
function get_string($key, $component = 'local_ent_installer', $a = ''){
    global $_configuration;
    static $strings;
    static $fallbackstrings;

    if ($component == 'local_ent_installer'){
        $fallbackpath = $_configuration['root_sys'].'local/ent_installer/lang/english/local_ent_installer.php';
    
        if (!isset($strings)){
            $lang = api_get_language_from_type('platform_lang');
            if (empty($lang)) $lang = 'english';
            $path = $_configuration['root_sys'].'local/ent_installer/lang/'.$lang.'/local_ent_installer.php';
            if (!file_exists($path)){
                if (!file_exists($path)){
                    print_error('missinglang', null);
                    die;
                }
                if (!isset($fallbackstrings)){
                    include $fallbackpath;
                    $fallbackstrings = $string;
                }
            }
    
            include $path;
            $strings = $string;
        }
    
        if(!array_key_exists($key, $strings)){
            if (!isset($fallbackstrings)){
                include $fallbackpath;
                $fallbackstrings = $string;
            }
            if(!array_key_exists($key, $fallbackstrings)){
                return "[[$key]]";
            }
            if (is_string($a)){
                return str_replace('{$a}', $a, $fallbackstrings[$key]);
            }
            if (is_array($a)){
                $a = (object)$a;
            }
            if (is_object($a)){
                return replace_string_vars($a, $fallbackstrings[$key]);
            }
            debugging('Stirng insertion not supported', 1);
            die;
        }

        if (is_string($a)){
            return str_replace('{$a}', $a, $strings[$key]);
        }
        if (is_array($a)){
            $a = (object)$a;
        }
        if (is_object($a)){
            return replace_string_vars($a, $strings[$key]);
        }
        debugging('Stirng insertion not supported', 1);
        die;
    } else {
        return get_lang($key);
    }
}

function replace_string_vars($a, $str){
    preg_match_all('/{\$a-\>(.+?)}/', $str, $matches);
    if (!empty($matches[1])){
        foreach($matches[1] as $replacekey){
            $str = str_replace('{$a->'.$replacekey.'}', $a->$replacekey, $str);
        }
    }
    return $str;
}

function print_error($key, $component = '', $passthru = false, $extrainfo = ''){
    global $debuglevel;
    global $debugdisplay;

    if ($component === null){
        $str = $key;
    } else {
        $str = get_string($string, $component);
    }
    ctrace('ERROR: '. $str);
    if (!empty($extrainfo)){
        ctrace('Extra: '. $extrainfo);
    }
    if ($debugdisplay >= 3){
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    }
    if (!$passthru) die;
}

function debugging($message, $level){
    global $debuglevel;
    global $debugdisplay;
    
    if ($level <= $debuglevel){
        ctrace('DEBUG: '.$message);
        if ($debugdisplay >= 3){
            debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        }
    }
}

/**
* Wrap moodle to chamilo side
*
*/
function mtrace($message){
    ctrace($message);
}

function ctrace($str){
    if (!defined('CLI_SCRIPT')) echo "<pre>\n";
    echo $str."\n";
    if (!defined('CLI_SCRIPT')) echo "</pre>\n";
}

/**
 * Sets a platform configuration setting to a given value, creating it if necessary
 * @param string    The value we want to record
 * @param string    The variable name we want to insert
 * @param string    The subkey for the variable we want to insert
 * @param string    The type for the variable we want to insert
 * @param string    The category for the variable we want to insert
 * @param string    The title
 * @param string    The comment
 * @param string    The scope
 * @param string    The subkey text
 * @param int       The access_url for which this parameter is valid
 * @param int       The changeability of this setting for non-master urls
 * @return boolean  true on success, false on failure
 */
function api_update_setting($val, $var, $sk = null, $type = 'textfield', $c = null, $title = '', $com = '', $sc = null, $skt = null, $a = 1, $v = 0) {
    global $_setting;

    if (empty($var) || !isset($val)) { 
        return false; 
    }

    $t_settings = Database::get_main_table(TABLE_MAIN_SETTINGS_CURRENT);
    $var = Database::escape_string($var);
    $val = Database::escape_string($val);
    $a = (int) $a;

    if (empty($a)) { $a = 1; }

    // Check if this variable doesn't exist already
    $select = "SELECT id FROM $t_settings WHERE variable = '$var' ";

    if (!empty($sk)) {
        $sk = Database::escape_string($sk);
        $select .= " AND subkey = '$sk'";
    }

    if ($a > 1) {
        $select .= " AND access_url = $a";
    } else {
        $select .= " AND access_url = 1 ";
    }

    $res = Database::query($select);
    if (Database::num_rows($res) > 0) { // Found item for this access_url.
        $row = Database::fetch_array($res);
        // update value
        $update['selected_value'] = $val;
        Database::update($t_settings, $update, array('id = ?' => $row['id']));
        return $row['id'];
        
        // update in memory setting value
        $_setting[$var][$sk] = $val;
    }

    // Item not found for this access_url, we have to check if the whole thing is missing
    // (in which case we ignore the insert) or if there *is* a record but just for access_url = 1
    $insert = "INSERT INTO $t_settings " .
                "(variable,selected_value," .
                "type,category," .
                "subkey,title," .
                "comment,scope," .
                "subkeytext,access_url,access_url_changeable)" .
                " VALUES ('$var','$val',";
    if (isset($type)) {
        $type = Database::escape_string($type);
        $insert .= "'$type',";
    } else {
        $insert .= "NULL,";
    }
    if (isset($c)) { // Category
        $c = Database::escape_string($c);
        $insert .= "'$c',";
    } else {
        $insert .= "NULL,";
    }
    if (isset($sk)) { // Subkey
        $sk = Database::escape_string($sk);
        $insert .= "'$sk',";
    } else {
        $insert .= "NULL,";
    }
    if (isset($title)) { // Title
        $title = Database::escape_string($title);
        $insert .= "'$title',";
    } else {
        $insert .= "NULL,";
    }
    if (isset($com)) { // Comment
        $com = Database::escape_string($com);
        $insert .= "'$com',";
    } else {
        $insert .= "NULL,";
    }
    if (isset($sc)) { // Scope
        $sc = Database::escape_string($sc);
        $insert .= "'$sc',";
    } else {
        $insert .= "NULL,";
    }
    if (isset($skt)) { // Subkey text
        $skt = Database::escape_string($skt);
        $insert .= "'$skt',";
    } else {
        $insert .= "NULL,";
    }
    $insert .= "$a,$v)";
    $res = Database::query($insert);

    // update in memory setting value
    $_setting[$var][$sk] = $value;

    return $res;
}

/**
* converts a timestamp to sql tms
* @param lint $time a unix timestamp
*/
function make_tms($time) {
    $tms = date('Y-m-d H:i:s', $time);
    return $tms;
}

/**
 * Makes sure the data is using valid utf8, invalid characters are discarded.
 *
 * Note: this function is not intended for full objects with methods and private properties.
 *
 * @param mixed $value
 * @return mixed with proper utf-8 encoding
 */
function fix_utf8($value) {
    if (is_null($value) or $value === '') {
        return $value;

    } else if (is_string($value)) {
        if ((string)(int)$value === $value) {
            // shortcut
            return $value;
        }

        // Lower error reporting because glibc throws bogus notices.
        $olderror = error_reporting();
        if ($olderror & E_NOTICE) {
            error_reporting($olderror ^ E_NOTICE);
        }

        // Note: this duplicates min_fix_utf8() intentionally.
        static $buggyiconv = null;
        if ($buggyiconv === null) {
            $buggyiconv = (!function_exists('iconv') or iconv('UTF-8', 'UTF-8//IGNORE', '100'.chr(130).'€') !== '100€');
        }

        if ($buggyiconv) {
            if (function_exists('mb_convert_encoding')) {
                $subst = mb_substitute_character();
                mb_substitute_character('');
                $result = mb_convert_encoding($value, 'utf-8', 'utf-8');
                mb_substitute_character($subst);

            } else {
                // Warn admins on admin/index.php page.
                $result = $value;
            }

        } else {
            $result = iconv('UTF-8', 'UTF-8//IGNORE', $value);
        }

        if ($olderror & E_NOTICE) {
            error_reporting($olderror);
        }

        return $result;

    } else if (is_array($value)) {
        foreach ($value as $k=>$v) {
            $value[$k] = fix_utf8($v);
        }
        return $value;

    } else if (is_object($value)) {
        $value = clone($value); // do not modify original
        foreach ($value as $k=>$v) {
            $value->$k = fix_utf8($v);
        }
        return $value;

    } else {
        // this is some other type, no utf-8 here
        return $value;
    }
}

function print_object($obj) {
    echo '<pre>';
    print_r($obj);
    echo '</pre>';
}

function require_js($file, $component, $return = false) {
   global $_configuration, $htmlHeadXtra;

    if (preg_match('/^local_/', $component)) {
        $component = str_replace('local_', '', $component);
        $path = 'local/';
    } else {
        $path = 'plugin/';
    }
    
    $str = '<script type="text/javascript" src="'.$_configuration['root_web'].$path.$component.'/js/'.$file.'"></script>'."\n";
    if ($return === 'head') {
        $htmlHeadXtra[] = $str;
    }
    if ($return) {
        return $str;
    }
    echo $str;
}