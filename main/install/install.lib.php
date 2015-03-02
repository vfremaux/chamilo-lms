<?php
/* For licensing terms, see /license.txt */

/**
 * Chamilo LMS
 * This file contains functions used by the install and upgrade scripts.
 */

/**
 * Check if current system is allowed to install
 * @return bool
 */
function checkRequiredSettings()
{
    $requirements = getRequirements();
    $requiredSettings = $requirements['required'];

    foreach ($requiredSettings as $extension => $options) {
        if (!extension_loaded($extension)) {
            return false;
        }
    }

<<<<<<< HEAD
    return true;
}


/**
 * @param Symfony\Component\Translation\Translator $translator
 * @return null|string
 */
function drawRequirements($translator)
{
    $requirements = getRequirements();
    $html = null;
    $html .= '<tr>
                <td>
                    '.$translator->trans('Required').'
                </td>
                <td>
                </td>
              </tr>';

    foreach ($requirements['required'] as $extension => $req) {
        $checkExtension = check_extension(
            $extension,
            $translator->trans('Yes'),
            $translator->trans('No')
        );
        $html .= '<tr>
                    <td>
                        <a href="'.$req['url'].'">'.$extension.'</a>
                    </td>
                    <td>
                        '.$checkExtension.'
                    </td>
                  </tr>';
    }

    $html .= '<tr>
                <td>
                    '.$translator->trans('Optional').'
                </td>
                <td>
                </td>
              </tr>';

    foreach ($requirements['optional'] as $extension => $req) {

        $checkExtension = check_extension(
            $extension,
            $translator->trans('Yes'),
            $translator->trans('No')
        );

        $html .= '<tr>
                    <td>
                        <a href="'.$req['url'].'">'.$extension.'</a>
                    </td>
                    <td>
                        '.$checkExtension.'
                    </td>
                  </tr>';
    }

    return $html;
}

function drawOptions($translator)
{
    $options = getOptions($translator);
    $html = null;
    foreach ($options as $option) {
        $html .= '<tr>
                    <td>
                        <a href="'.$option['url'].'">'.$option['name'].'</a>
                    </td>
                    <td>
                        '.$option['recommended'].'
                    </td>
                    <td>
                        '.$option['current'].'
                    </td>
                  </tr>';
    }

    return $html;
}



function getRequirements()
{
    return
        array(
            'required' => array(
                //'session' => array('url' => 'http://php.net/manual/en/book.session.php', 'recommend' => Display::label('OFF', 'success')),
                'mysql' => array('url' => 'http://php.net/manual/en/book.mysql.php'),
                'curl' => array('url' => 'http://php.net/manual/fr/book.curl.php'),
                'zlib' => array('url' => 'http://php.net/manual/en/book.zlib.php'),
                'pcre' => array('url' => 'http://php.net/manual/en/book.pcre.php'),
                'xml' => array('url' => 'http://php.net/manual/en/book.xml.php'),
                'mbstring' => array('url' => 'http://php.net/manual/en/book.mbstring.php'),
                'iconv' => array('url' => 'http://php.net/manual/en/book.iconv.php'),
                'intl' => array('url' => 'http://php.net/manual/en/book.intl.php'),
                'gd' => array('url' => 'http://php.net/manual/en/book.image.php'),
                'json' => array('url' => 'http://php.net/manual/en/book.json.php')
            ),
            'optional' =>  array(
                'imagick' => array('url' => 'http://php.net/manual/en/book.imagick.php'),
                'ldap' => array('url' => 'http://php.net/manual/en/book.ldap.php'),
                'xapian' => array('url' => 'http://php.net/manual/en/book.xapian.php')
            )
        );
}

/**
 * @param Symfony\Component\Translation\Translator $translator
 * @return array
 */
function getOptions($translator)
{
    return array(
        array(
            'name' => 'Safe Mode',
            'url' => 'http://php.net/manual/features.safe-mode.php',
            'recommended' => Display::label('OFF', 'success'),
            'current' => check_php_setting('safe_mode', 'OFF'),
        ),
        array(
            'name' => 'Display Errors',
            'url' => 'http://php.net/manual/ref.errorfunc.php#ini.display-errors',
            'recommended' => Display::label('ON', 'success'),
            'current' => check_php_setting('display_errors', 'OFF'),
        ),
        array(
            'name' => 'File Uploads',
            'url' => 'http://php.net/manual/ini.core.php#ini.file-uploads',
            'recommended' => Display::label('OFF', 'success'),
            'current' => check_php_setting('file_uploads', 'ON'),
        ),
        array(
            'name' => 'Magic Quotes GPC',
            'url' => 'http://php.net/manual/ref.info.php#ini.magic-quotes-gpc',
            'recommended' => Display::label('OFF', 'success'),
            'current' => check_php_setting('magic_quotes_gpc', 'OFF'),
        ),
        array(
            'name' => 'Magic Quotes Runtime',
            'url' => 'http://php.net/manual/ref.info.php#ini.magic-quotes-runtime',
            'recommended' => Display::label('OFF', 'success'),
            'current' => check_php_setting('magic_quotes_runtime', 'OFF'),
        ),
        array(
            'name' => 'Register Globals',
            'url' => 'http://php.net/manual/security.globals.php',
            'recommended' => Display::label('OFF', 'success'),
            'current' => check_php_setting('register_globals', 'OFF'),
        ),
        array(
            'name' => 'Session auto start',
            'url' => 'http://php.net/manual/ref.session.php#ini.session.auto-start',
            'recommended' => Display::label('OFF', 'success'),
            'current' => check_php_setting('auto_start', 'OFF'),
        ),
        array(
            'name' => 'Short Open Tag',
            'url' => 'http://php.net/manual/ini.core.php#ini.short-open-tag',
            'recommended' => Display::label('OFF', 'success'),
            'current' => check_php_setting('short_open_tag', 'OFF'),
        ),
        array(
            'name' => 'Cookie HTTP Only',
            'url' => 'http://www.php.net/manual/en/session.configuration.php#ini.session.cookie-httponly',
            'recommended' => Display::label('ON', 'success'),
            'current' => check_php_setting('session.cookie_httponly', 'ON'),
        ),
        array(
            'name' => 'Maximum upload file size',
            'url' => 'http://php.net/manual/ini.core.php#ini.upload-max-filesize',
            'recommended' => Display::label('>= '.REQUIRED_MIN_UPLOAD_MAX_FILESIZE.'M', 'success'),
            'current' => compare_setting_values(ini_get('upload_max_filesize'), REQUIRED_MIN_UPLOAD_MAX_FILESIZE),
        ),
        array(
            'name' => 'Maximum post size',
            'url' => 'http://php.net/manual/ini.core.php#ini.post-max-size',
            'recommended' => Display::label('>= '.REQUIRED_MIN_POST_MAX_SIZE.'M', 'success'),
            'current' => compare_setting_values(ini_get('post_max_size'), REQUIRED_MIN_POST_MAX_SIZE),
        ),
        array(
            'name' => 'Memory Limit',
            'url' => 'http://www.php.net/manual/en/ini.core.php#ini.memory-limit',
            'recommended' => Display::label('>= '.REQUIRED_MIN_MEMORY_LIMIT.'M', 'success'),
            'current' => compare_setting_values(ini_get('memory_limit'), REQUIRED_MIN_MEMORY_LIMIT),
        )
    );
}

function translate($variable)
{
    global $app;
=======
    $current_config_file = api_get_path(CONFIGURATION_PATH).'configuration.php';
    if (!file_exists($current_config_file)) {
        return false; // Configuration file does not exist, install the system.
    }
    require $current_config_file;

    $current_version = null;
    if (isset($_configuration['dokeos_version'])) {
        $current_version = trim($_configuration['dokeos_version']);
    }
    if (empty($current_version)) {
        $current_version = trim($_configuration['system_version']);
    }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    return $app['translator']->trans($variable);
}

/**
 * This function checks if a php extension exists or not and returns an HTML status string.
 *
 * @param   string  Name of the PHP extension to be checked
 * @param   string  Text to show when extension is available (defaults to 'Yes')
 * @param   string  Text to show when extension is available (defaults to 'No')
 * @param   boolean Whether this extension is optional (in this case show unavailable text in orange rather than red)
 * @return  string  HTML string reporting the status of this extension. Language-aware.
 * @author  Christophe Gesch??
 * @author  Patrick Cool <patrick.cool@UGent.be>, Ghent University
 * @author  Yannick Warnier <yannick.warnier@dokeos.com>
 * @version Dokeos 1.8.1, May 2007
 */
function check_extension($extension_name, $return_success = 'Yes', $return_failure = 'No', $optional = false)
{
    if (extension_loaded($extension_name)) {
        return Display::label($return_success, 'success');
    } else {
        if ($optional) {
            return Display::label($return_failure, 'warning');
            //return '<strong><font color="#ff9900">'.$return_failure.'</font></strong>';
        } else {
            return Display::label($return_failure, 'important');
            //return '<strong><font color="red">'.$return_failure.'</font></strong>';
        }
    }
}


/**
 * This function checks whether a php setting matches the recommended value
 *
 * @author Patrick Cool <patrick.cool@UGent.be>, Ghent University
 * @version Dokeos 1.8, august 2006
 */
function check_php_setting($php_setting, $recommended_value, $return_success = false, $return_failure = false)
{
    $current_php_value = get_php_setting($php_setting);
    if ($current_php_value == $recommended_value) {
        return Display::label($current_php_value.' '.$return_success, 'success');
    } else {
        return Display::label($current_php_value.' '.$return_success, 'important');
<<<<<<< HEAD
=======
    }
}


/**
 *  This function return the value of a php.ini setting if not "" or if exists, otherwise return false
 */
function check_php_setting_exists($php_setting) {
    if (ini_get($php_setting) != "") {
        return ini_get($php_setting);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    }
    return false;
}


/**
 * Returns a textual value ('ON' or 'OFF') based on a requester 2-state ini- configuration setting.
 *
 * @param string $val a php ini value
 * @return boolean: ON or OFF
 * @author Joomla <http://www.joomla.org>
 */
function get_php_setting($val)
{
    return ini_get($val) == '1' ? 'ON' : 'OFF';
}

<<<<<<< HEAD
function compare_setting_values($current_value, $wanted_value)
{
    $current_value_string = $current_value;
    $current_value = (float) $current_value;
    $wanted_value = (float) $wanted_value;
=======
/**
 * This function returns a string "true" or "false" according to the passed parameter.
 *
 * @param integer  $var  The variable to present as text
 * @return  string  the string "true" or "false"
 * @author Christophe Gesch??
 */
function true_false($var) {
    return $var ? 'true' : 'false';
}

/**
 * Removes memory and time limits as much as possible.
 */
function remove_memory_and_time_limits() {
    if (function_exists('ini_set')) {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
    } else {
        error_log('Update-db script: could not change memory and time limits', 0);
    }
}

/**
 * Detects browser's language.
 * @return string       Returns a language identificator, i.e. 'english', 'spanish', ...
 * @author Ivan Tcholakov, 2010
 */
function detect_browser_language() {
    static $language_index = array(
        'ar' => 'arabic',
        'ast' => 'asturian',
        'bg' => 'bulgarian',
        'bs' => 'bosnian',
        'ca' => 'catalan',
        'zh' => 'simpl_chinese',
        'zh-tw' => 'trad_chinese',
        'cs' => 'czech',
        'da' => 'danish',
        'prs' => 'dari',
        'de' => 'german',
        'el' => 'greek',
        'en' => 'english',
        'es' => 'spanish',
        'eo' => 'esperanto',
        'eu' => 'basque',
        'fa' => 'persian',
        'fr' => 'french',
        'fur' => 'friulian',
        'gl' => 'galician',
        'ka' => 'georgian',
        'hr' => 'croatian',
        'he' => 'hebrew',
        'hi' => 'hindi',
        'id' => 'indonesian',
        'it' => 'italian',
        'ko' => 'korean',
        'lv' => 'latvian',
        'lt' => 'lithuanian',
        'mk' => 'macedonian',
        'hu' => 'hungarian',
        'ms' => 'malay',
        'nl' => 'dutch',
        'ja' => 'japanese',
        'no' => 'norwegian',
        'oc' => 'occitan',
        'ps' => 'pashto',
        'pl' => 'polish',
        'pt' => 'portuguese',
        'pt-br' => 'brazilian',
        'ro' => 'romanian',
        'qu' => 'quechua_cusco',
        'ru' => 'russian',
        'sk' => 'slovak',
        'sl' => 'slovenian',
        'sr' => 'serbian',
        'fi' => 'finnish',
        'sv' => 'swedish',
        'th' => 'thai',
        'tr' => 'turkish',
        'uk' => 'ukrainian',
        'vi' => 'vietnamese',
        'sw' => 'swahili',
        'yo' => 'yoruba'
    );

    $system_available_languages = & get_language_folder_list();

    $accept_languages = strtolower(str_replace('_', '-', $_SERVER['HTTP_ACCEPT_LANGUAGE']));
    foreach ($language_index as $code => $language) {
        if (strpos($accept_languages, $code) === 0) {
            if (!empty($system_available_languages[$language])) {
                return $language;
            }
        }
    }

    $user_agent = strtolower(str_replace('_', '-', $_SERVER['HTTP_USER_AGENT']));
    foreach ($language_index as $code => $language) {
        if (@preg_match("/[\[\( ]{$code}[;,_\-\)]/", $user_agent)) {
            if (!empty($system_available_languages[$language])) {
                return $language;
            }
        }
    }

    return 'english';
}


/*      FILESYSTEM RELATED FUNCTIONS */

/**
 * This function checks if the given folder is writable
 */
function check_writable($folder, $suggestion = false) {
    if (is_writable($folder)) {
        return Display::label(get_lang('Writable'), 'success');
    } else {
        if ($suggestion) {
            return Display::label(get_lang('NotWritable'), 'info');
        } else {
            return Display::label(get_lang('NotWritable'), 'important');
        }
    }
}

/**
 * This function is similar to the core file() function, except that it
 * works with line endings in Windows (which is not the case of file())
 * @param   string  File path
 * @return  array   The lines of the file returned as an array
 */
function file_to_array($filename) {
    if(!is_readable($filename) || is_dir($filename)){
        return array();
    }
    $fp = fopen($filename, 'rb');
    $buffer = fread($fp, filesize($filename));
    fclose($fp);
    return explode('<br />', nl2br($buffer));
}

/**
 * We assume this function is called from install scripts that reside inside the install folder.
 */
function set_file_folder_permissions() {
    @chmod('.', 0755); //set permissions on install dir
    @chmod('..', 0755); //set permissions on parent dir of install dir
    @chmod('country_data.csv.csv', 0755);
}

/**
 * Add's a .htaccess file to the courses directory
 * @param string $url_append The path from your webroot to your chamilo root
 */
function write_courses_htaccess_file($url_append) {
    $content = file_get_contents(dirname(__FILE__).'/'.COURSES_HTACCESS_FILENAME);
    $content = str_replace('{CHAMILO_URL_APPEND_PATH}', $url_append, $content);
    $fp = @ fopen(api_get_path(SYS_PATH).'courses/.htaccess', 'w');
    if ($fp) {
        fwrite($fp, $content);
        return fclose($fp);
    }
    return false;
}

/**
 * Write the main system config file
 * @param string $path Path to the config file
 */
function write_system_config_file($path) {

    global $dbHostForm;
    global $dbUsernameForm;
    global $dbPassForm;
    global $enableTrackingForm;
    global $singleDbForm;
    global $dbPrefixForm;
    global $dbNameForm;
    global $dbStatsForm;
    global $dbScormForm;
    global $dbUserForm;
    global $urlForm;
    global $pathForm;
    global $urlAppendPath;
    global $languageForm;
    global $encryptPassForm;
    global $installType;
    global $updatePath;
    global $session_lifetime;
    global $new_version;
    global $new_version_stable;

    $root_sys = api_add_trailing_slash(str_replace('\\', '/', realpath($pathForm)));
    $content = file_get_contents(dirname(__FILE__).'/'.SYSTEM_CONFIG_FILENAME);

    $config['{DATE_GENERATED}']         = date('r');
    $config['{DATABASE_HOST}']          = $dbHostForm;
    $config['{DATABASE_USER}']          = $dbUsernameForm;
    $config['{DATABASE_PASSWORD}']      = $dbPassForm;
    $config['TRACKING_ENABLED']         = true_false($enableTrackingForm);
    $config['SINGLE_DATABASE']          = true_false($singleDbForm);
    $config['{COURSE_TABLE_PREFIX}']    = ($singleDbForm ? 'crs_' : '');
    $config['{DATABASE_GLUE}']          = ($singleDbForm ? '_' : '`.`');
    $config['{DATABASE_PREFIX}']        = '';
    $config['{DATABASE_MAIN}']          = $dbNameForm;
    $config['{DATABASE_STATS}']         = $dbNameForm;
    $config['{DATABASE_SCORM}']         = $dbNameForm;
    $config['{DATABASE_PERSONAL}']      = $dbNameForm;
    $config['{ROOT_WEB}']               = $urlForm;
    $config['{ROOT_SYS}']               = $root_sys;
    $config['{URL_APPEND_PATH}']        = $urlAppendPath;
    $config['{PLATFORM_LANGUAGE}']      = $languageForm;
    $config['{SECURITY_KEY}']           = md5(uniqid(rand().time()));
    $config['{ENCRYPT_PASSWORD}']       = $encryptPassForm;

    $config['SESSION_LIFETIME']         = $session_lifetime;
    $config['{NEW_VERSION}']            = $new_version;
    $config['NEW_VERSION_STABLE']       = true_false($new_version_stable);

    foreach ($config as $key => $value) {
        $content = str_replace($key, $value, $content);
    }

    $fp = @ fopen($path, 'w');

    if (!$fp) {
        echo '<strong><font color="red">Your script doesn\'t have write access to the config directory</font></strong><br />
                        <em>('.str_replace('\\', '/', realpath($path)).')</em><br /><br />
                        You probably do not have write access on Chamilo root directory,
                        i.e. you should <em>CHMOD 777</em> or <em>755</em> or <em>775</em>.<br /><br />
                        Your problems can be related on two possible causes:<br />
                        <ul>
                          <li>Permission problems.<br />Try initially with <em>chmod -R 777</em> and increase restrictions gradually.</li>
                          <li>PHP is running in <a href="http://www.php.net/manual/en/features.safe-mode.php" target="_blank">Safe-Mode</a>. If possible, try to switch it off.</li>
                        </ul>
                        <a href="http://forum.chamilo.org/" target="_blank">Read about this problem in Support Forum</a><br /><br />
                        Please go back to step 5.
                        <p><input type="submit" name="step5" value="&lt; Back" /></p>
                        </td></tr></table></form></body></html>';
        exit;
    }

    fwrite($fp, $content);
    fclose($fp);
}

/**
 * Returns a list of language directories.
 */
function & get_language_folder_list() {
    static $result;
    if (!is_array($result)) {
        $result = array();
        $exceptions = array('.', '..', 'CVS', '.svn');
        $search       = array('_latin',   '_unicode',   '_corporate',   '_org'  , '_KM',   '_');
        $replace_with = array(' (Latin)', ' (unicode)', ' (corporate)', ' (org)', ' (KM)', ' ');
        $dirname = api_get_path(SYS_LANG_PATH);
        $handle = opendir($dirname);
        while ($entries = readdir($handle)) {
            if (in_array($entries, $exceptions)) {
                continue;
            }
            if (is_dir($dirname.$entries)) {
                $result[$entries] = ucwords(str_replace($search, $replace_with, $entries));
            }
        }
        closedir($handle);
        asort($result);
    }
    return $result;
}

/**
 * TODO: my_directory_to_array() - maybe within the main API there is already a suitable function?
 */
function my_directory_to_array($directory) {
    $array_items = array();
    if ($handle = opendir($directory)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($directory. "/" . $file)) {
                    $array_items = array_merge($array_items, my_directory_to_array($directory. '/' . $file));
                    $file = $directory . "/" . $file;
                    $array_items[] = preg_replace("/\/\//si", '/', $file);
                }
            }
        }
        closedir($handle);
    }
    return $array_items;
}

/**
 * This function returns the value of a parameter from the configuration file
 *
 * WARNING - this function relies heavily on global variables $updateFromConfigFile
 * and $configFile, and also changes these globals. This can be rewritten.
 *
 * @param   string  $param  the parameter of which the value is returned
 * @param   string  If we want to give the path rather than take it from POST
 * @return  string  the value of the parameter
 * @author Olivier Brouckaert
 * @author Reworked by Ivan Tcholakov, 2010
 */
function get_config_param($param, $updatePath = '') {
    global $configFile, $updateFromConfigFile;

    // Look if we already have the queried parameter.
    if (is_array($configFile) && isset($configFile[$param])) {
        return $configFile[$param];
    }
    if (empty($updatePath) && !empty($_POST['updatePath'])) {
        $updatePath = $_POST['updatePath'];
    }
    if (empty($updatePath)) {
        $updatePath = api_get_path(SYS_PATH);
    }
    $updatePath = api_add_trailing_slash(str_replace('\\', '/', realpath($updatePath)));
    $updateFromInstalledVersionFile = '';

    if (empty($updateFromConfigFile)) {
        // If update from previous install was requested,
        // try to recover old config file from dokeos 1.8.x.
        if (file_exists($updatePath.'main/inc/conf/configuration.php')) {
            $updateFromConfigFile = 'main/inc/conf/configuration.php';
        } elseif (file_exists($updatePath.'claroline/inc/conf/claro_main.conf.php')) {
            $updateFromConfigFile = 'claroline/inc/conf/claro_main.conf.php';
        } else {
            // Give up recovering.
            //error_log('Chamilo Notice: Could not find previous config file at '.$updatePath.'main/inc/conf/configuration.php nor at '.$updatePath.'claroline/inc/conf/claro_main.conf.php in get_config_param(). Will start new config (in '.__FILE__.', line '.__LINE__.')', 0);
            return null;
        }
    }

    if (file_exists($updatePath.$updateFromConfigFile) && !is_dir($updatePath.$updateFromConfigFile)) {

        // The parameter was not found among the global variables, so look into the old configuration file.

        // Make sure the installedVersion file is read first so it is overwritten
        // by the config file if the config file contains the version (from 1.8.4).
        $config_data_2 = array();
        if (file_exists($updatePath.$updateFromInstalledVersionFile)) {
            $config_data_2 = file_to_array($updatePath.$updateFromInstalledVersionFile);
        }
        $configFile = array();
        $config_data = file_to_array($updatePath.$updateFromConfigFile);
        $config_data = array_merge($config_data, $config_data_2);
        $val = '';

        // Parse the configuration file, statement by statement (line by line, actually).
        foreach ($config_data as $php_statement) {

            if (strpos($php_statement, '=') !== false) {
                // Variable assignment statement have been detected (probably).
                // It is expected to be as follows:
                // $variable = 'some_value'; // A comment that is not mandatory.

                // Split the statement into its left and right sides.
                $php_statement = explode('=', $php_statement);
                $variable = trim($php_statement[0]);
                $value = $php_statement[1];

                if (substr($variable, 0, 1) == '$') {
                    // We have for sure a php variable assignment detected.

                    // On the left side: Retrieve the pure variable's name
                    $variable = trim(str_replace('$', '', $variable));

                    // On the right side: Remove the comment, if it exists.
                    list($value) = explode(' //', $value);
                    // Remove extra whitespace, if any. Remove the trailing semicolon (;).
                    $value = substr(trim($value), 0, -1);
                    // Remove surroundig quotes, restore escaped quotes.
                    $value = str_replace('\"', '"', preg_replace('/^"|"$/', '', $value));
                    $value = str_replace('\'', '"', preg_replace('/^\'|\'$/', '', $value));

                    if (strtolower($value) == 'true') {

                        // A boolean true value have been recognized.
                        $value = 1;

                    } elseif (strtolower($value) == 'false') {

                        // A boolean false value have been recognized.
                        $value = 0;

                    } else {

                        // Probably we have a string value, but also we have to check
                        // possible string concatenations that may include string values
                        // and other configuration variables. I this case we have to
                        // get the calculated result of the concatenation.
                        $implode_string = ' ';
                        if (!strstr($value, '." ".') && strstr($value, '.$')) {
                            // Yes, there is concatenation, insert a special separator string.
                            $value = str_replace('.$', '." ".$', $value);
                            $implode_string = '';
                        }

                        // Split the concatenated values, if they are more than one.
                        $sub_strings = explode('." ".', $value);

                        // Seek for variables and retrieve their values.
                        foreach ($sub_strings as $key => & $sub_string) {
                            if (preg_match('/^\$[a-zA-Z_][a-zA-Z0-9_]*$/', $sub_string)) {
                                // A variable has been detected, read it by recursive call.
                                $sub_string = get_config_param(str_replace('$', '', $sub_string));
                            }
                        }

                        // Concatenate everything into the final, the calculated string value.
                        $value = implode($implode_string, $sub_strings);
                    }

                    // Cache the result value.
                    $configFile[$variable] = $value;

                    $a = explode("'", $variable);
                    $key_tmp = isset($a[1]) ? $a[1] : null;
                    if ($key_tmp == $param) {
                        $val = $value;
                    }
                }
            }
        }
    }

    if($param == 'dbGlu' && empty($val)){
        return '`.`';
    }
    //Special treatment for dokeos_version parameter due to Dokeos 1.8.3 have the dokeos_version in the main/inc/installedVersion.inc.php file
    if ($param == 'dokeos_version') {
        //dokeos_version from configuration.php if empty
        $dokeos_version = $val;

        if (empty($dokeos_version)) {
            //checking the dokeos_version value exists in main/inc/installedVersion.inc.php
            if (file_exists($updatePath.'main/inc/installedVersion.inc.php')) {
                $updateFromInstalledVersionFile = $updatePath.'main/inc/installedVersion.inc.php';
                require ($updateFromInstalledVersionFile); //there are only 2 variables here: $stable & $dokeos_version
                $stable = false;
            }
        }
        return $dokeos_version;
    } else {
        if (file_exists($updatePath.$updateFromConfigFile)) {
            return  $val;
        } else {
            error_log('Config array could not be found in get_config_param()', 0);
            return null;
        }
    }
}

/*      DATABASE RELATED FUNCTIONS */

/**
 * Gets a configuration parameter from the database. Returns returns null on failure.
 * @param   string  DB Host
 * @param   string  DB login
 * @param   string  DB pass
 * @param   string  DB name
 * @param   string  Name of param we want
 * @return  mixed   The parameter value or null if not found
 */
function get_config_param_from_db($host, $login, $pass, $db_name, $param = '') {

    Database::connect(array('server' => $host, 'username' => $login, 'password' => $pass));
    Database::query("set session sql_mode='';"); // Disabling special SQL modes (MySQL 5)
    Database::select_db($db_name);

    if (($res = Database::query("SELECT * FROM settings_current WHERE variable = '$param'")) !== false) {
        if (Database::num_rows($res) > 0) {
            $row = Database::fetch_array($res);
            return $row['selected_value'];
        }
    }
    return null;
}

/**
 * Connects to the database server.
 */
function database_server_connect() {
    global $dbHostForm, $dbUsernameForm, $dbPassForm;
    if (($res = @Database::connect(array('server' => $dbHostForm, 'username' => $dbUsernameForm, 'password' => $dbPassForm))) === false) {
        $no = Database::errno();
        $msg = Database::error();
        echo '<hr />#'.$no.': '.$msg.'<hr />';
        echo get_lang('DBServerDoesntWorkOrLoginPassIsWrong').'.<br /><br />'.
            get_lang('PleaseCheckTheseValues').' :<br /><br />'.
            '<strong>'.get_lang('DBHost').'</strong> : '.$dbHostForm.'<br />'.
            '<strong>'.get_lang('DBLogin').'</strong> : '.$dbUsernameForm.'<br />'.
            '<strong>'.get_lang('DBPassword').'</strong> : '.$dbPassForm.'<br /><br />'.
            get_lang('PleaseGoBackToStep').' '. (defined('SYSTEM_INSTALLATION') ? '3' : '1').'.'.
            '<p><button type="submit" class="back" name="step'. (defined('SYSTEM_INSTALLATION') ? '3' : '1').'" value="&lt; '.get_lang('Back').'">'.get_lang('Back').'</button></p>'.
            '</td></tr></table></form></body></html>';
        exit ();
    }
    @Database::query("set session sql_mode='';"); // Disabling special SQL modes (MySQL 5)
}

/**
 * Database exists for the MYSQL user
 * @param type $database_name
 * @return boolean
 */
function database_exists($database_name) {
    if (empty($database_name)) {
        return false;
    }
    $select_database = @Database::select_db($database_name);
    $show_database = false;
    $sql = "SHOW DATABASES LIKE '".addslashes($database_name)."'";
    $result = @Database::query($sql);
    if (Database::num_rows($result)) {
        $show_database = true;
    }
    return $select_database || $show_database;
}

/**
 * In step 3. Tests establishing connection to the database server.
 * If it's a single database environment the function checks if the database exist.
 * If the database doesn't exist we check the creation permissions.
 *
 * @return int      1 when there is no problem;
 *                  0 when a new database is impossible to be created, then the single/multiple database configuration is impossible too
 *                 -1 when there is no connection established.
 */
function test_db_connect($dbHostForm, $dbUsernameForm, $dbPassForm, $singleDbForm, $dbPrefixForm, $dbNameForm) {
    $dbConnect = -1;
    //Checking user credentials
    if (@Database::connect(array('server' => $dbHostForm, 'username' => $dbUsernameForm, 'password' => $dbPassForm)) !== false) {
        $dbConnect = 1;
    } else {
        $dbConnect = -1;
    }
    return $dbConnect; //return 1, if no problems, "0" if, in case we can't create a new DB and "-1" if there is no connection.
}

/**
 * Fills the countries table with a list of countries.
 */
function fill_track_countries_table($track_countries_table) {
    $file_path = dirname(__FILE__).'/'.COUNTRY_DATA_FILENAME;
    $countries = file($file_path);
    $add_country_sql = "INSERT INTO $track_countries_table (id, code, country, counter) VALUES ";
    foreach ($countries as $line) {
        $elems = explode(',',$line);
        $add_country_sql .= '('.intval($elems[0]).',\''.Database::escape_string($elems[1]).'\',\''.Database::escape_string($elems[2]).'\','.intval($elems[3]).'),';
    }
    $add_country_sql = substr($add_country_sql,0,-1);
    //$add_country_sql = "LOAD DATA INFILE '".Database::escape_string($file_path)."' INTO TABLE $track_countries_table FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\'';";
    @ Database::query($add_country_sql);
}

/**
 * Creates the structure of the main database and fills it
 * with data. Placeholder symbols in the main database file
 * have to be replaced by the settings entered by the user during installation.
 *
 * @param array $installation_settings list of settings entered by the user
 * @param string  optional path about the script for database
 * @return void
 */
function load_main_database($installation_settings, $db_script = '') {
    if (!empty($db_script)) {
        if (file_exists($db_script)) {
            $sql_text = file_get_contents($db_script);
        }
    } else {
        $db_script = api_get_path(SYS_CODE_PATH).'install/'.SYSTEM_MAIN_DATABASE_FILE;
        if (file_exists($db_script)) {
            $sql_text = file_get_contents($db_script);
        }
    }

    //replace symbolic parameters with user-specified values
    foreach ($installation_settings as $key => $value) {
        $sql_text = str_replace($key, Database::escape_string($value), $sql_text);
    }
    parse_sql_queries($sql_text);
}

/**
 * Creates the structure of the stats database
 * @param   string  Name of the file containing the SQL script inside the install directory
 */
function load_database_script($db_script) {
    $db_script = api_get_path(SYS_CODE_PATH).'install/'.$db_script;
    if (file_exists($db_script)) {
        $sql_text = file_get_contents($db_script);
    }
    parse_sql_queries($sql_text);
}

function parse_sql_queries($sql_text) {

    //split in array of sql strings
    $sql_instructions = array();
    split_sql_file($sql_instructions, $sql_text);

    //execute the sql instructions
    $count = count($sql_instructions);
    for ($i = 0; $i < $count; $i++) {
        $this_sql_query = $sql_instructions[$i]['query'];
        Database::query($this_sql_query);
        //UTF8 fix see #5678
        /*
        if (strpos(strtolower($this_sql_query), 'create table') === false) {
            Database::query($this_sql_query);
        } else {
            //$this_sql_query .= substr($this_sql_query, strlen($this_sql_query), strlen($this_sql_query)-1);
            $this_sql_query .= ' DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci ';
            Database::query($this_sql_query);
        }*/
    }
}


/**
 * Function copied and adapted from phpMyAdmin 2.6.0 PMA_splitSqlFile (also GNU GPL)
 * Removes comment lines and splits up large sql files into individual queries
 * Last revision: September 23, 2001 - gandon
 * @param   array    the splitted sql commands
 * @param   string   the sql commands
 * @param   integer  the MySQL release number (because certains php3 versions
 *                   can't get the value of a constant from within a function)
 * @return  boolean  always true
 */
function split_sql_file(&$ret, $sql) {
    // do not trim, see bug #1030644
    //$sql          = trim($sql);
    $sql          = rtrim($sql, "\n\r");
    $sql_len      = strlen($sql);
    $char         = '';
    $string_start = '';
    $in_string    = false;
    $nothing      = true;
    $time0        = time();

    for ($i = 0; $i < $sql_len; ++$i) {
        $char = $sql[$i];

        // We are in a string, check for not escaped end of strings except for
        // backquotes that can't be escaped
        if ($in_string) {
            for (;;) {
                $i         = strpos($sql, $string_start, $i);
                // No end of string found -> add the current substring to the
                // returned array
                if (!$i) {
                    $ret[] = $sql;
                    return true;
                }
                // Backquotes or no backslashes before quotes: it's indeed the
                // end of the string -> exit the loop
                elseif ($string_start == '`' || $sql[$i - 1] != '\\') {
                    $string_start      = '';
                    $in_string         = false;
                    break;
                }
                // one or more Backslashes before the presumed end of string...
                else {
                    // ... first checks for escaped backslashes
                    $j                     = 2;
                    $escaped_backslash     = false;
                    while ($i - $j > 0 && $sql[$i - $j] == '\\') {
                        $escaped_backslash = !$escaped_backslash;
                        $j++;
                    }
                    // ... if escaped backslashes: it's really the end of the
                    // string -> exit the loop
                    if ($escaped_backslash) {
                        $string_start  = '';
                        $in_string     = false;
                        break;
                    }
                    // ... else loop
                    else {
                        $i++;
                    }
                } // end if...elseif...else
            } // end for
        } // end if (in string)

        // lets skip comments (/*, -- and #)
        elseif (($char == '-' && $sql_len > $i + 2 && $sql[$i + 1] == '-' && $sql[$i + 2] <= ' ') || $char == '#' || ($char == '/' && $sql_len > $i + 1 && $sql[$i + 1] == '*')) {
            $i = strpos($sql, $char == '/' ? '*/' : "\n", $i);
            // didn't we hit end of string?
            if ($i === false) {
                break;
            }
            if ($char == '/') $i++;
        }

        // We are not in a string, first check for delimiter...
        elseif ($char == ';') {
            // if delimiter found, add the parsed part to the returned array
            $ret[]      = array('query' => substr($sql, 0, $i), 'empty' => $nothing);
            $nothing    = true;
            $sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
            $sql_len    = strlen($sql);
            if ($sql_len) {
                $i      = -1;
            } else {
                // The submited statement(s) end(s) here
                return true;
            }
        } // end elseif (is delimiter)

        // ... then check for start of a string,...
        elseif (($char == '"') || ($char == '\'') || ($char == '`')) {
            $in_string    = true;
            $nothing      = false;
            $string_start = $char;
        } // end elseif (is start of string)

        elseif ($nothing) {
            $nothing = false;
        }

        // loic1: send a fake header each 30 sec. to bypass browser timeout
        $time1     = time();
        if ($time1 >= $time0 + 30) {
            $time0 = $time1;
            header('X-pmaPing: Pong');
        } // end if
    } // end for

    // add any rest to the returned array
    if (!empty($sql) && preg_match('@[^[:space:]]+@', $sql)) {
        $ret[] = array('query' => $sql, 'empty' => $nothing);
    }

    return true;
} // end of the 'split_sql_file()' function

/**
 * Get an SQL file's contents
 *
 * This function bases its parsing on the pre-set format of the specific SQL files in
 * the install/upgrade procedure:
 * Lines starting with "--" are comments (but need to be taken into account as they also hold sections names)
 * Other lines are considered to be one-line-per-query lines (this is checked quickly by this function)
 * @param   string  File to parse (in the current directory)
 * @param   string  Section to return
 * @param   boolean Print (true) or hide (false) error texts when they occur
 */
function get_sql_file_contents($file, $section, $print_errors = true) {
    //check given parameters
    if (empty($file)) {
        $error = "Missing name of file to parse in get_sql_file_contents()";
        if ($print_errors) echo $error;
        return false;
    }
    if (!in_array($section, array('main', 'user', 'stats', 'scorm', 'course'))) {
        $error = "Section '$section' is not authorized in get_sql_file_contents()";
        if ($print_errors) echo $error;
        return false;
    }
    $filepath = getcwd().'/'.$file;
    if (!is_file($filepath) or !is_readable($filepath)) {
        $error = "File $filepath not found or not readable in get_sql_file_contents()";
        if ($print_errors) echo $error;
        return false;
    }
    //read the file in an array
    // Empty lines should not be executed as SQL statements, because errors occur, see Task #2167.
    $file_contents = file($filepath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!is_array($file_contents) or count($file_contents) < 1) {
        $error = "File $filepath looks empty in get_sql_file_contents()";
        if ($print_errors) echo $error;
        return false;
    }

    //prepare the resulting array
    $section_contents = array();
    $record = false;
    foreach ($file_contents as $index => $line) {
        if (substr($line, 0, 2) == '--') {
            //This is a comment. Check if section name, otherwise ignore
            $result = array();
            if (preg_match('/^-- xx([A-Z]*)xx/', $line, $result)) { //we got a section name here
                if ($result[1] == strtoupper($section)) {
                    //we have the section we are looking for, start recording
                    $record = true;
                } else {
                    //we have another section's header. If we were recording, stop now and exit loop
                    if ($record) {
                        break;
                    }
                    $record = false;
                }
            }
        } else {
            if ($record) {
                if (!empty($line)) {
                    $section_contents[] = $line;
                }
            }
        }
    }
    //now we have our section's SQL statements group ready, return
    return $section_contents;
}
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    if ($current_value >= $wanted_value) {
        return Display::label($current_value_string, 'success');
    } else {
        return Display::label($current_value_string, 'important');
    }
}

function drawPermissionsSettings($app)
{
    $html  = null;

    // DIRECTORY AND FILE PERMISSIONS
    $html .= '<div class="RequirementContent">';

    $course_attempt_name = '__XxTestxX__';
    $course_dir = $app['path.courses'].$course_attempt_name;

<<<<<<< HEAD
    // Just in case.
    if (is_file($course_dir.'/test.txt')) {
        unlink($course_dir.'/test.txt');
    }
    if (is_dir($course_dir)) {
        rmdir($course_dir);
    }

=======
/**
 * This function displays the requirements for installing Chamilo.
 *
 * @param string $installType
 * @param boolean $badUpdatePath
 * @param string The updatePath given (if given)
 * @param array $update_from_version_8 The different subversions from version 1.8
 * @param array $update_from_version_6 The different subversions from version 1.6
 *
 * @author unknow
 * @author Patrick Cool <patrick.cool@UGent.be>, Ghent University
 */
function display_requirements($installType, $badUpdatePath, $updatePath = '', $update_from_version_8 = array(), $update_from_version_6 = array()) {
    global $_setting;
    echo '<div class="RequirementHeading"><h2>'.display_step_sequence().get_lang('Requirements')."</h2></div>";
    echo '<div class="RequirementText">';
    echo '<strong>'.get_lang('ReadThoroughly').'</strong><br />';
    echo get_lang('MoreDetails').' <a href="../../documentation/installation_guide.html" target="_blank">'.get_lang('ReadTheInstallGuide').'</a>.<br />'."\n";

    if ($installType == 'update')  {
        echo get_lang('IfYouPlanToUpgradeFromOlderVersionYouMightWantToHaveAlookAtTheChangelog').'<br />';
    }
    echo '</div>';

    //  SERVER REQUIREMENTS
    echo '<div class="RequirementHeading"><h2>'.get_lang('ServerRequirements').'</h2>';

    $timezone = check_php_setting_exists("date.timezone");
    if (!$timezone) {
        echo "<div class='warning-message'>".Display::return_icon('warning.png',get_lang('Warning'),'',ICON_SIZE_MEDIUM).get_lang("DateTimezoneSettingNotSet")."</div>";
    }

    echo '<div class="RequirementText">'.get_lang('ServerRequirementsInfo').'</div>';
    echo '<div class="RequirementContent">';
    echo '<table class="table">
            <tr>
                <td class="requirements-item">'.get_lang('PHPVersion').' >= '.REQUIRED_PHP_VERSION.'</td>
                <td class="requirements-value">';
    if (phpversion() < REQUIRED_PHP_VERSION) {
        echo '<strong><font color="red">'.get_lang('PHPVersionError').'</font></strong>';
    } else {
        echo '<strong><font color="green">'.get_lang('PHPVersionOK'). ' '.phpversion().'</font></strong>';
    }
    echo '</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/en/book.session.php" target="_blank">Session</a> '.get_lang('support').'</td>
                <td class="requirements-value">'.check_extension('session', get_lang('Yes'), get_lang('ExtensionSessionsNotAvailable')).'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/en/book.mysql.php" target="_blank">MySQL</a> '.get_lang('support').'</td>
                <td class="requirements-value">'.check_extension('mysql', get_lang('Yes'), get_lang('ExtensionMySQLNotAvailable')).'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/en/book.zlib.php" target="_blank">Zlib</a> '.get_lang('support').'</td>
                <td class="requirements-value">'.check_extension('zlib', get_lang('Yes'), get_lang('ExtensionZlibNotAvailable')).'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/en/book.pcre.php" target="_blank">Perl-compatible regular expressions</a> '.get_lang('support').'</td>
                <td class="requirements-value">'.check_extension('pcre', get_lang('Yes'), get_lang('ExtensionPCRENotAvailable')).'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/en/book.xml.php" target="_blank">XML</a> '.get_lang('support').'</td>
                <td class="requirements-value">'.check_extension('xml', get_lang('Yes'), get_lang('No')).'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/en/book.mbstring.php" target="_blank">Multibyte string</a> '.get_lang('support').' ('.get_lang('Optional').')</td>
                <td class="requirements-value">'.check_extension('mbstring', get_lang('Yes'), get_lang('ExtensionMBStringNotAvailable'), true).'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/en/book.iconv.php" target="_blank">Iconv</a> '.get_lang('support').' ('.get_lang('Optional').')</td>
                <td class="requirements-value">'.check_extension('iconv', get_lang('Yes'), get_lang('No'), true).'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/en/book.intl.php" target="_blank">Internationalization</a> '.get_lang('support').' ('.get_lang('Optional').')</td>
                <td class="requirements-value">'.check_extension('intl', get_lang('Yes'), get_lang('No'), true).'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/en/book.image.php" target="_blank">GD</a> '.get_lang('support').'</td>
                <td class="requirements-value">'.check_extension('gd', get_lang('Yes'), get_lang('ExtensionGDNotAvailable')).'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/en/book.json.php" target="_blank">JSON</a> '.get_lang('support').'</td>
                <td class="requirements-value">'.check_extension('json', get_lang('Yes'), get_lang('No')).'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/en/book.ldap.php" target="_blank">LDAP</a> '.get_lang('support').' ('.get_lang('Optional').')</td>
                <td class="requirements-value">'.check_extension('ldap', get_lang('Yes'), get_lang('ExtensionLDAPNotAvailable'), true).'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://xapian.org/" target="_blank">Xapian</a> '.get_lang('support').' ('.get_lang('Optional').')</td>
                <td class="requirements-value">'.check_extension('xapian', get_lang('Yes'), get_lang('No'), true).'</td>
            </tr>

            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/en/book.curl.php" target="_blank">cURL</a> '.get_lang('support').' ('.get_lang('Optional').')</td>
                <td class="requirements-value">'.check_extension('curl', get_lang('Yes'), get_lang('No'), true).'</td>
            </tr>

          </table>';
    echo '  </div>';
    echo '</div>';

    // RECOMMENDED SETTINGS
    // Note: these are the settings for Joomla, does this also apply for Chamilo?
    // Note: also add upload_max_filesize here so that large uploads are possible
    echo '<div class="RequirementHeading"><h2>'.get_lang('RecommendedSettings').'</h2>';
    echo '<div class="RequirementText">'.get_lang('RecommendedSettingsInfo').'</div>';
    echo '<div class="RequirementContent">';
    echo '<table class="table">
            <tr>
                <th>'.get_lang('Setting').'</th>
                <th>'.get_lang('Recommended').'</th>
                <th>'.get_lang('Actual').'</th>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/features.safe-mode.php">Safe Mode</a></td>
                <td class="requirements-recommended">'.Display::label('OFF', 'success').'</td>
                <td class="requirements-value">'.check_php_setting('safe_mode','OFF').'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/ref.errorfunc.php#ini.display-errors">Display Errors</a></td>
                <td class="requirements-recommended">'.Display::label('OFF', 'success').'</td>
                <td class="requirements-value">'.check_php_setting('display_errors','OFF').'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/ini.core.php#ini.file-uploads">File Uploads</a></td>
                <td class="requirements-recommended">'.Display::label('ON', 'success').'</td>
                <td class="requirements-value">'.check_php_setting('file_uploads','ON').'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/ref.info.php#ini.magic-quotes-gpc">Magic Quotes GPC</a></td>
                <td class="requirements-recommended">'.Display::label('OFF', 'success').'</td>
                <td class="requirements-value">'.check_php_setting('magic_quotes_gpc','OFF').'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/ref.info.php#ini.magic-quotes-runtime">Magic Quotes Runtime</a></td>
                <td class="requirements-recommended">'.Display::label('OFF', 'success').'</td>
                <td class="requirements-value">'.check_php_setting('magic_quotes_runtime','OFF').'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/security.globals.php">Register Globals</a></td>
                <td class="requirements-recommended">'.Display::label('OFF', 'success').'</td>
                <td class="requirements-value">'.check_php_setting('register_globals','OFF').'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/ref.session.php#ini.session.auto-start">Session auto start</a></td>
                <td class="requirements-recommended">'.Display::label('OFF', 'success').'</td>
                <td class="requirements-value">'.check_php_setting('session.auto_start','OFF').'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/ini.core.php#ini.short-open-tag">Short Open Tag</a></td>
                <td class="requirements-recommended">'.Display::label('OFF', 'success').'</td>
                <td class="requirements-value">'.check_php_setting('short_open_tag','OFF').'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://www.php.net/manual/en/session.configuration.php#ini.session.cookie-httponly">Cookie HTTP Only</a></td>
                <td class="requirements-recommended">'.Display::label('ON', 'success').'</td>
                <td class="requirements-value">'.check_php_setting('session.cookie_httponly','ON').'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/ini.core.php#ini.upload-max-filesize">Maximum upload file size</a></td>
                <td class="requirements-recommended">'.Display::label('>= '.REQUIRED_MIN_UPLOAD_MAX_FILESIZE.'M', 'success').'</td>
                <td class="requirements-value">'.compare_setting_values(ini_get('upload_max_filesize'), REQUIRED_MIN_UPLOAD_MAX_FILESIZE).'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://php.net/manual/ini.core.php#ini.post-max-size">Maximum post size</a></td>
                <td class="requirements-recommended">'.Display::label('>= '.REQUIRED_MIN_POST_MAX_SIZE.'M', 'success').'</td>
                <td class="requirements-value">'.compare_setting_values(ini_get('post_max_size'), REQUIRED_MIN_POST_MAX_SIZE).'</td>
            </tr>
            <tr>
                <td class="requirements-item"><a href="http://www.php.net/manual/en/ini.core.php#ini.memory-limit">Memory Limit</a></td>
                <td class="requirements-recommended">'.Display::label('>= '.REQUIRED_MIN_MEMORY_LIMIT.'M', 'success').'</td>
                <td class="requirements-value">'.compare_setting_values(ini_get('memory_limit'), REQUIRED_MIN_MEMORY_LIMIT).'</td>
            </tr>
          </table>';
    echo '  </div>';
    echo '</div>';

    // DIRECTORY AND FILE PERMISSIONS
    echo '<div class="RequirementHeading"><h2>'.get_lang('DirectoryAndFilePermissions').'</h2>';
    echo '<div class="RequirementText">'.get_lang('DirectoryAndFilePermissionsInfo').'</div>';
    echo '<div class="RequirementContent">';

    $course_attempt_name = '__XxTestxX__';
    $course_dir = api_get_path(SYS_COURSE_PATH).$course_attempt_name;

    //Just in case
    @unlink($course_dir.'/test.php');
    @rmdir($course_dir);

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    $perms_dir = array(0777, 0755, 0775, 0770, 0750, 0700);
    $perms_fil = array(0666, 0644, 0664, 0660, 0640, 0600);

    $course_test_was_created = false;

    $dir_perm_verified = 0777;
    foreach ($perms_dir as $perm) {
        $r = @mkdir($course_dir, $perm);
<<<<<<< HEAD

=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        if ($r === true) {
            $dir_perm_verified = $perm;
            $course_test_was_created = true;
            break;
        }
    }

    $fil_perm_verified = 0666;
    $file_course_test_was_created = false;

    if (is_dir($course_dir)) {
        foreach ($perms_fil as $perm) {
            if ($file_course_test_was_created == true) {
                break;
            }
<<<<<<< HEAD
            $r = touch($course_dir.'/test.php', $perm);
=======
            $r = @touch($course_dir.'/test.php',$perm);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
            if ($r === true) {
                $fil_perm_verified = $perm;
                if (check_course_script_interpretation($course_dir, $course_attempt_name, 'test.php')) {
                    $file_course_test_was_created = true;
                }
            }
        }
    }

    @unlink($course_dir.'/test.php');
    @rmdir($course_dir);

<<<<<<< HEAD
    $app['session']->set('permissions_for_new_directories', decoct($dir_perm_verified));
    $app['session']->set('permissions_for_new_files', decoct($fil_perm_verified));

    $dir_perm = Display::label('0'.decoct($dir_perm_verified), 'info');
    $file_perm = Display::label('0'.decoct($fil_perm_verified), 'info');

    $course_test_was_created  = ($course_test_was_created == true && $file_course_test_was_created == true) ? Display::label(translate('Yes'), 'success') : Display::label(translate('No'), 'important');

    $html .= '<table class="table">
            <tr>
                <td class="requirements-item">[chamilo]/config</td>
                <td class="requirements-value">'.check_writable_root_path('config/').'</td>
            </tr>
            <tr>
                <td class="requirements-item">[chamilo]/data</td>
                <td class="requirements-value">'.check_writable_root_path('data').'</td>
            </tr>
            <tr>
                <td class="requirements-item">[chamilo]/logs</td>
                <td class="requirements-value">'.check_writable_root_path('logs').'</td>
            </tr>
            <tr>
                <td class="requirements-item">'.translate('CourseTestWasCreated').'</td>
                <td class="requirements-value">'.$course_test_was_created.' </td>
            </tr>
            <tr>
                <td class="requirements-item">'.translate('PermissionsForNewDirs').'</td>
                <td class="requirements-value">'.$dir_perm.' </td>
            </tr>
            <tr>
                <td class="requirements-item">'.translate('PermissionsForNewFiles').'</td>
                <td class="requirements-value">'.$file_perm.' </td>
            </tr>';
=======
    $_SESSION['permissions_for_new_directories'] = $_setting['permissions_for_new_directories'] = $dir_perm_verified;
    $_SESSION['permissions_for_new_files'] = $_setting['permissions_for_new_files'] = $fil_perm_verified;

    $dir_perm = Display::label('0'.decoct($dir_perm_verified), 'info');
    $file_perm = Display::label('0'.decoct($fil_perm_verified), 'info');

    $courseTestLabel = Display::label(get_lang('No'), 'important');

    if ($course_test_was_created && $file_course_test_was_created) {
        $courseTestLabel = Display::label(get_lang('Yes'), 'success');
    }

    if ($course_test_was_created && !$file_course_test_was_created) {
        $courseTestLabel = Display::label(
            sprintf(
                get_lang('InstallWarningCouldNotInterpretPHP'),
                api_get_path(WEB_COURSE_PATH).$course_attempt_name.'/test.php'
            ),
            'warning'
        );
    }

    if (!$course_test_was_created && !$file_course_test_was_created) {
        $courseTestLabel = Display::label(get_lang('No'), 'important');
    }

    echo '<table class="table">
            <tr>
                <td class="requirements-item">'.api_get_path(SYS_CODE_PATH).'inc/conf/</td>
                <td class="requirements-value">'.check_writable(api_get_path(SYS_CODE_PATH).'inc/conf/').'</td>
            </tr>
            <tr>
                <td class="requirements-item">'.api_get_path(SYS_CODE_PATH).'upload/users/</td>
                <td class="requirements-value">'.check_writable(api_get_path(SYS_CODE_PATH).'upload/users/').'</td>
            </tr>
            <tr>
                <td class="requirements-item">'.api_get_path(SYS_CODE_PATH).'default_course_document/images/</td>
                <td class="requirements-value">'.check_writable(api_get_path(SYS_CODE_PATH).'default_course_document/images/').'</td>
            </tr>
            <tr>
                <td class="requirements-item">'.api_get_path(SYS_ARCHIVE_PATH).'</td>
                <td class="requirements-value">'.check_writable(api_get_path(SYS_ARCHIVE_PATH)).'</td>
            </tr>
            <tr>
                <td class="requirements-item">'.api_get_path(SYS_COURSE_PATH).'</td>
                <td class="requirements-value">'.check_writable(api_get_path(SYS_COURSE_PATH)).' </td>
            </tr>
            <tr>
                <td class="requirements-item">'.get_lang('CourseTestWasCreated').'</td>
                <td class="requirements-value">'.$courseTestLabel.' </td>
            </tr>
            <tr>
                <td class="requirements-item">'.get_lang('PermissionsForNewDirs').'</td>
                <td class="requirements-value">'.$dir_perm.' </td>
            </tr>
            <tr>
                <td class="requirements-item">'.get_lang('PermissionsForNewFiles').'</td>
                <td class="requirements-value">'.$file_perm.' </td>
            </tr>
            <tr>
                <td class="requirements-item">'.api_get_path(SYS_PATH).'home/</td>
                <td class="requirements-value">'.check_writable(api_get_path(SYS_PATH).'home/').'</td>
            </tr>
            <tr>
                <td class="requirements-item">'.api_get_path(SYS_CODE_PATH).'css/</td>
                <td class="requirements-value">'.check_writable(api_get_path(SYS_CODE_PATH).'css/', true).' ('.get_lang('SuggestionOnlyToEnableCSSUploadFeature').')</td>
            </tr>
            <tr>
                <td class="requirements-item">'.api_get_path(SYS_CODE_PATH).'lang/</td>
                <td class="requirements-value">'.check_writable(api_get_path(SYS_CODE_PATH).'lang/', true).' ('.get_lang('SuggestionOnlyToEnableSubLanguageFeature').')</td>
            </tr>'.
            //'<tr>
            //    <td class="requirements-item">chamilo/searchdb/</td>
            //    <td class="requirements-value">'.check_writable('../searchdb/').'</td>
            //</tr>'.
            //'<tr>
            //    <td class="requirements-item">'.session_save_path().'</td>
            //    <td class="requirements-value">'.(is_writable(session_save_path())
            //      ? '<strong><font color="green">'.get_lang('Writable').'</font></strong>'
            //      : '<strong><font color="red">'.get_lang('NotWritable').'</font></strong>').'</td>
            //</tr>'.
            '';
    echo '    </table>';
    echo '  </div>';
    echo '</div>';

    if ($installType == 'update' && (empty($updatePath) || $badUpdatePath)) {
        if ($badUpdatePath) { ?>
            <div class="error-message">
                <?php echo get_lang('Error'); ?>!<br />
                Chamilo <?php echo (isset($_POST['step2_update_6']) ? implode('|', $update_from_version_6) : implode('|', $update_from_version_8)).' '.get_lang('HasNotBeenFoundInThatDir'); ?>.
            </div>
        <?php }
        else {
            echo '<br />';
        }
        ?>
            <table border="0" cellpadding="5" align="center">
            <tr>
            <td><?php echo get_lang('OldVersionRootPath'); ?>:</td>
            <td><input type="text" name="updatePath" size="50" value="<?php echo ($badUpdatePath && !empty($updatePath)) ? htmlentities($updatePath) : api_get_path(SYS_SERVER_ROOT_PATH).'old_version/'; ?>" /></td>
            </tr>
            <tr>
            <td colspan="2" align="center">
                <button type="submit" class="back" name="step1" value="&lt; <?php echo get_lang('Back'); ?>" ><?php echo get_lang('Back'); ?></button>
                <input type="hidden" name="is_executable" id="is_executable" value="-" />
                <button type="submit" class="btn next" name="<?php echo (isset($_POST['step2_update_6']) ? 'step2_update_6' : 'step2_update_8'); ?>" value="<?php echo get_lang('Next'); ?> &gt;" ><?php echo get_lang('Next'); ?></button>
            </td>
            </tr>
            </table>
        <?php
    } else {
        $error = false;
        // First, attempt to set writing permissions if we don't have them yet
        $perm = api_get_permissions_for_new_directories();
        $perm_file = api_get_permissions_for_new_files();

        $notwritable = array();
        $curdir = getcwd();

        $checked_writable = api_get_path(CONFIGURATION_PATH);
        if (!is_writable($checked_writable)) {
            $notwritable[] = $checked_writable;
            @chmod($checked_writable, $perm);
        }

        $checked_writable = api_get_path(SYS_CODE_PATH).'upload/users/';
        if (!is_writable($checked_writable)) {
            $notwritable[] = $checked_writable;
            @chmod($checked_writable, $perm);
        }

        $checked_writable = api_get_path(SYS_CODE_PATH).'default_course_document/images/';
        if (!is_writable($checked_writable)) {
            $notwritable[] = $checked_writable;
            @chmod($checked_writable, $perm);
        }

        $checked_writable = api_get_path(SYS_ARCHIVE_PATH);
        if (!is_writable($checked_writable)) {
            $notwritable[] = $checked_writable;
            @chmod($checked_writable, $perm);
        }

        $checked_writable = api_get_path(SYS_COURSE_PATH);
        if (!is_writable($checked_writable)) {
            $notwritable[] = $checked_writable;
            @chmod($checked_writable, $perm);
        }

        if ($course_test_was_created == false) {
            $error = true;
        }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    $html .= '    </table>';
    $html .= '  </div>';
    $html .= '</div>';

    $error = false;
    // First, attempt to set writing permissions if we don't have them yet
    $perm = $app['session']->get('permissions_for_new_directories');
    $perm_file = $app['session']->get('permissions_for_new_files');

<<<<<<< HEAD
    $notwritable = array();

    $checked_writable = api_get_path(SYS_CONFIG_PATH);
    if (!is_writable($checked_writable)) {
        $notwritable[] = $checked_writable;
        @chmod($checked_writable, $perm);
    }
=======
        // Second, if this fails, report an error

        //--> The user would have to adjust the permissions manually
        if (count($notwritable) > 0) {
            $error = true;
            echo '<div class="error-message">';
                echo '<center><h3>'.get_lang('Warning').'</h3></center>';
                printf(get_lang('NoWritePermissionPleaseReadInstallGuide'), '</font>
                <a href="../../documentation/installation_guide.html" target="blank">', '</a> <font color="red">');
            echo '</div>';
            echo '<ul>';
            foreach ($notwritable as $value) {
                echo '<li>'.$value.'</li>';
            }
            echo '</ul>';
        }

        // Check wether a Chamilo configuration file already exists.
        elseif (file_exists(api_get_path(CONFIGURATION_PATH).'configuration.php')) {
            echo '<div class="warning-message"><h4><center>';
            echo get_lang('WarningExistingDokeosInstallationDetected');
            echo '</center></h4></div>';
        }

        // And now display the choice buttons (go back or install)
        ?>
        <p align="center" style="padding-top:15px">
        <button type="submit" name="step1" class="back" onclick="javascript: window.location='index.php'; return false;" value="&lt; <?php echo get_lang('Previous'); ?>" ><?php echo get_lang('Previous'); ?></button>
        <button type="submit" name="step2_install" class="plus" value="<?php echo get_lang("NewInstallation"); ?>" <?php if ($error) echo 'disabled="disabled"'; ?> ><?php echo get_lang('NewInstallation'); ?></button>
        <input type="hidden" name="is_executable" id="is_executable" value="-" />
        <?php
        // Real code
        echo '<button type="submit" class="save" name="step2_update_8" value="Upgrade from Dokeos 1.8.x"';
        if ($error) echo ' disabled="disabled"';
        // Temporary code for alpha version, disabling upgrade
        //echo '<input type="submit" name="step2_update" value="Upgrading is not possible in this beta version"';
        //echo ' disabled="disabled"';
        //end temp code
        echo ' >'.get_lang('UpgradeFromDokeos18x').'</button>';
        echo ' <button type="submit" class="save" name="step2_update_6" value="Upgrade from Dokeos 1.6.x"';
        if ($error) echo ' disabled="disabled"';
        echo ' >'.get_lang('UpgradeFromDokeos16x').'</button>';
        echo '</p>';
    }
}

/**
 * Displays the license (GNU GPL) as step 2, with
 * - an "I accept" button named step3 to proceed to step 3;
 * - a "Back" button named step1 to go back to the first step.
 */

function display_license_agreement() {
    echo '<div class="RequirementHeading"><h2>'.display_step_sequence().get_lang('Licence').'</h2>';
    echo '<p>'.get_lang('DokeosLicenseInfo').'</p>';
    echo '<p><a href="../../documentation/license.html" target="_blank">'.get_lang('PrintVers').'</a></p>';
    echo '</div>';
    ?>
    <table>
        <tr><td>
            <pre style="overflow: auto; height: 150px; margin-top: 5px;" class="span7"><?php echo api_htmlentities(@file_get_contents(api_get_path(SYS_PATH).'documentation/license.txt')); ?></pre>
        </td>
        </tr>
        <tr><td>
            <p>
                <label class="checkbox">
                    <input type="checkbox" name="accept" id="accept_licence" value="1" />
                    <?php echo get_lang('IAccept'); ?>
                </label>
            </p>
            </td>
        </tr>
        <tr><td><p style="color:#666"><br /><?php echo get_lang('DokeosArtLicense'); ?></p></td></tr>
        <tr>
            <td>
            <table width="100%">
                <tr>
                    <td></td>
                    <td align="center">
                        <button type="submit" class="btn back" name="step1" value="&lt; <?php echo get_lang('Previous'); ?>" ><?php echo get_lang('Previous'); ?></button>
                        <input type="hidden" name="is_executable" id="is_executable" value="-" />
                        <button type="submit" class="btn next" name="step3" onclick="javascript: if(!document.getElementById('accept_licence').checked) { alert('<?php echo get_lang('YouMustAcceptLicence')?>');return false;}" value="<?php echo get_lang('Next'); ?> &gt;" ><?php echo get_lang('Next'); ?></button>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
    </table>

    <!-- Contact information form -->
    <div>

            <a href="javascript://" class = "advanced_parameters" >
                <span id="img_plus_and_minus">&nbsp;<img src="<?php echo api_get_path(WEB_IMG_PATH) ?>div_hide.gif" alt="<?php echo get_lang('Hide') ?>" title="<?php echo get_lang('Hide')?>" style ="vertical-align:middle" />&nbsp;<?php echo get_lang('ContactInformation') ?></span>
               </a>

    </div>

    <div id="id_contact_form" style="display:block">
        <div class="normal-message"><?php echo get_lang('ContactInformationDescription') ?></div>
        <div id="contact_registration">
            <p><?php echo get_contact_registration_form() ?></p><br />
        </div>
    </div>
    <?php
}
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    $checked_writable = api_get_path(SYS_DATA_PATH);
    if (!is_writable($checked_writable)) {
        $notwritable[] = $checked_writable;
        @chmod($checked_writable, $perm);
    }

<<<<<<< HEAD
    $checked_writable = api_get_path(SYS_DEFAULT_COURSE_DOCUMENT_PATH).'images/';
    if (!is_writable($checked_writable)) {
        $notwritable[] = $checked_writable;
        @chmod($checked_writable, $perm);
    }

    $checked_writable = api_get_path(SYS_ARCHIVE_PATH);
    if (!is_writable($checked_writable)) {
        $notwritable[] = $checked_writable;
        @chmod($checked_writable, $perm);
    }

    $checked_writable = api_get_path(SYS_LOG_PATH);
    if (!is_writable($checked_writable)) {
        $notwritable[] = $checked_writable;
        @chmod($checked_writable, $perm);
=======
/**
 * Get contact registration form
 */
function get_contact_registration_form() {

    $html ='
   <form class="form-horizontal">
   <fieldset style="width:95%;padding:15px;border:1pt solid #eee">
    <div id="div_sent_information"></div>
    <div class="control-group">
            <label class="control-label"><span class="form_required">*</span>'.get_lang('Name').'</label>
            <div class="controls"><input id="person_name" type="text" name="person_name" size="30" /></div>
    </div>
    <div class="control-group">
            <label class="control-label"><span class="form_required">*</span>'.get_lang('Email').'</label>
            <div class="controls"><input id="person_email" type="text" name="person_email" size="30" /></div>
    </div>
    <div class="control-group">
            <label class="control-label"><span class="form_required">*</span>'.get_lang('CompanyName').'</label>
            <div class="controls"><input id="company_name" type="text" name="company_name" size="30" /></div>
    </div>
    <div class="control-group">
            <div class="control-label"><span class="form_required">*</span>'.get_lang('CompanyActivity').'</div>
            <div class="controls">
                    <select name="company_activity" id="company_activity" >
                            <option value="">--- '.get_lang('SelectOne').' ---</option>
                            <Option value="Advertising/Marketing/PR">Advertising/Marketing/PR</Option><Option value="Agriculture/Forestry">Agriculture/Forestry</Option>
                            <Option value="Architecture">Architecture</Option><Option value="Banking/Finance">Banking/Finance</Option>
                            <Option value="Biotech/Pharmaceuticals">Biotech/Pharmaceuticals</Option><Option value="Business Equipment">Business Equipment</Option>
                            <Option value="Business Services">Business Services</Option><Option value="Construction">Construction</Option>
                            <Option value="Consulting/Research">Consulting/Research</Option><Option value="Education">Education</Option>
                            <Option value="Engineering">Engineering</Option><Option value="Environmental">Environmental</Option>
                            <Option value="Government">Government</Option><Option value="Healthcare">Health Care</Option>
                            <Option value="Hospitality/Lodging/Travel">Hospitality/Lodging/Travel</Option><Option value="Insurance">Insurance</Option>
                            <Option value="Legal">Legal</Option><Option value="Manufacturing">Manufacturing</Option>
                            <Option value="Media/Entertainment">Media/Entertainment</Option><Option value="Mortgage">Mortgage</Option>
                            <Option value="Non-Profit">Non-Profit</Option><Option value="Real Estate">Real Estate</Option>
                            <Option value="Restaurant">Restaurant</Option><Option value="Retail">Retail</Option>
                            <Option value="Shipping/Transportation">Shipping/Transportation</Option>
                            <Option value="Technology">Technology</Option><Option value="Telecommunications">Telecommunications</Option>
                            <Option value="Other">Other</Option>
                    </select>
            </div>
    </div>

    <div class="control-group">
            <div class="control-label"><span class="form_required">*</span>'.get_lang('PersonRole').'</div>
            <div class="controls">
                    <select name="person_role" id="person_role" >
                            <option value="">--- '.get_lang('SelectOne').' ---</option>
                            <Option value="Administration">Administration</Option><Option value="CEO/President/ Owner">CEO/President/ Owner</Option>
                            <Option value="CFO">CFO</Option><Option value="CIO/CTO">CIO/CTO</Option>
                            <Option value="Consultant">Consultant</Option><Option value="Customer Service">Customer Service</Option>
                            <Option value="Engineer/Programmer">Engineer/Programmer</Option><Option value="Facilities/Operations">Facilities/Operations</Option>
                            <Option value="Finance/ Accounting Manager">Finance/ Accounting Manager</Option><Option value="Finance/ Accounting Staff">Finance/ Accounting Staff</Option>
                            <Option value="General Manager">General Manager</Option><Option value="Human Resources">Human Resources</Option>
                            <Option value="IS/IT Management">IS/IT Management</Option><Option value="IS/ IT Staff">IS/ IT Staff</Option>
                            <Option value="Marketing Manager">Marketing Manager</Option><Option value="Marketing Staff">Marketing Staff</Option>
                            <Option value="Partner/Principal">Partner/Principal</Option><Option value="Purchasing Manager">Purchasing Manager</Option>
                            <Option value="Sales/ Business Dev. Manager">Sales/ Business Dev. Manager</Option><Option value="Sales/ Business Dev.">Sales/ Business Dev.</Option>
                            <Option value="Vice President/Senior Manager">Vice President/Senior Manager</Option><Option value="Other">Other</Option>
                    </select>
            </div>
    </div>

    <div class="control-group">
            <div class="control-label"><span class="form_required">*</span>'.get_lang('CompanyCountry').'</div>
            <div class="controls">'.get_countries_list_from_array(true).'</div>
    </div>
    <div class="control-group">
            <div class="control-label">'.get_lang('CompanyCity').'</div>
            <div class="controls">
                    <input type="text" id="company_city" name="company_city" size="30" />
            </div>
    </div>
    <div class="control-group">
            <div class="control-label">'.get_lang('WhichLanguageWouldYouLikeToUseWhenContactingYou').'</div>
            <div class="controls">
                    <select id="language" name="language">
                            <option value="bulgarian">Bulgarian</option>
                            <option value="indonesian">Bahasa Indonesia</option>
                            <option value="bosnian">Bosanski</option>
                            <option value="german">Deutsch</option>
                            <option selected="selected" value="english">English</option>
                            <option value="spanish">Spanish</option>
                            <option value="french">Français</option>
                            <option value="italian">Italian</option>
                            <option value="hungarian">Magyar</option>
                            <option value="dutch">Nederlands</option>
                            <option value="brazilian">Português do Brasil</option>
                            <option value="portuguese">Português europeu</option>
                            <option value="slovenian">Slovenčina</option>
                    </select>
            </div>
    </div>

    <div class="control-group">
            <div class="control-label">'.get_lang('HaveYouThePowerToTakeFinancialDecisions').'</div>
            <div class="controls">
                    <input type="radio" name="financial_decision" id="financial_decision1" value="1" checked />'.get_lang('Yes').'
                    <input type="radio" name="financial_decision" id="financial_decision2" value="0" />'.get_lang('No').'
            </div>
    </div>
    <div class="clear"></div>
    <div class="control-group">
            <div class="control-label">&nbsp;</div>
            <div class="controls"><button type="button" class="save" onclick="javascript:send_contact_information();" value="'.get_lang('SendInformation').'" >'.get_lang('SendInformation').'</button></div>
    </div>
    <div class="control-group">
            <div class="control-label">&nbsp;</div>
            <div class="controls"><span class="form_required">*</span><small>'.get_lang('FieldRequired').'</small></div>
    </div>
</fieldset></form>';

return $html;
}

/**
 * Displays a parameter in a table row.
 * Used by the display_database_settings_form function.
 * @param   string  Type of install
 * @param   string  Name of parameter
 * @param   string  Field name (in the HTML form)
 * @param   string  Field value
 * @param   string  Extra notice (to show on the right side)
 * @param   boolean Whether to display in update mode
 * @param   string  Additional attribute for the <tr> element
 * @return  void    Direct output
 */
function display_database_parameter($install_type, $parameter_name, $form_field_name, $parameter_value, $extra_notice, $display_when_update = true, $tr_attribute = '') {
    echo "<tr ".$tr_attribute.">";
    echo "<td>$parameter_name&nbsp;&nbsp;</td>";

    if ($install_type == INSTALL_TYPE_UPDATE && $display_when_update) {
        echo '<td><input type="hidden" name="'.$form_field_name.'" id="'.$form_field_name.'" value="'.api_htmlentities($parameter_value).'" />'.$parameter_value."</td>";
    } else {
        $inputtype = $form_field_name == 'dbPassForm' ? 'password' : 'text';

        //Slightly limit the length of the database prefix to avoid having to cut down the databases names later on
        $maxlength = $form_field_name == 'dbPrefixForm' ? '15' : MAX_FORM_FIELD_LENGTH;
        if ($install_type == INSTALL_TYPE_UPDATE) {
            echo '<input type="hidden" name="'.$form_field_name.'" id="'.$form_field_name.'" value="'.api_htmlentities($parameter_value).'" />';
            echo '<td>'.api_htmlentities($parameter_value)."</td>";
        } else {
            echo '<td><input type="'.$inputtype.'" size="'.DATABASE_FORM_FIELD_DISPLAY_LENGTH.'" maxlength="'.$maxlength.'" name="'.$form_field_name.'" id="'.$form_field_name.'" value="'.api_htmlentities($parameter_value).'" />'."</td>";
            echo "<td>$extra_notice</td>";
        }

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    }

<<<<<<< HEAD
    /*$checked_writable = api_get_path(SYS_COURSE_PATH);
    if (!is_writable($checked_writable)) {
        $notwritable[] = $checked_writable;
        @chmod($checked_writable, $perm);
    }*/

    if ($course_test_was_created == false || $file_course_test_was_created == false) {
        $error = true;
    }
=======
/**
 * Displays step 3 - a form where the user can enter the installation settings
 * regarding the databases - login and password, names, prefixes, single
 * or multiple databases, tracking or not...
 */
function display_database_settings_form($installType, $dbHostForm, $dbUsernameForm, $dbPassForm, $dbPrefixForm, $enableTrackingForm, $singleDbForm, $dbNameForm, $dbStatsForm, $dbScormForm, $dbUserForm) {

    if ($installType == 'update') {
        global $_configuration, $update_from_version_6;

        if (in_array($_POST['old_version'], $update_from_version_6)) {
            $dbHostForm         = get_config_param('dbHost');

            $dbUsernameForm     = get_config_param('dbLogin');
            $dbPassForm         = get_config_param('dbPass');
            $dbPrefixForm       = get_config_param('dbNamePrefix');
            $enableTrackingForm = get_config_param('is_trackingEnabled');
            $singleDbForm       = get_config_param('singleDbEnabled');
            $dbHostForm         = get_config_param('mainDbName');

            $dbStatsForm        = get_config_param('statsDbName');
            $dbScormForm        = get_config_param('scormDbName');
            $dbUserForm         = get_config_param('user_personal_database');
            $dbScormExists      = true;
        } else {
            $dbHostForm         = $_configuration['db_host'];
            $dbUsernameForm     = $_configuration['db_user'];
            $dbPassForm         = $_configuration['db_password'];
            $dbPrefixForm       = $_configuration['db_prefix'];
            $enableTrackingForm = $_configuration['tracking_enabled'];
            $singleDbForm       = $_configuration['single_database'];
            $dbNameForm         = $_configuration['main_database'];
            $dbStatsForm        = $_configuration['statistics_database'];
            $dbScormForm        = $_configuration['scorm_database'];
            $dbUserForm         = $_configuration['user_personal_database'];
            $dbScormExists      = true;
        }

        if (empty($dbScormForm)) {
            if ($singleDbForm) {
                $dbScormForm = $dbNameForm;
            } else {
                $dbScormForm = $dbPrefixForm.'scorm';
                $dbScormExists = false;
            }
        }

        if (empty($dbUserForm)) {
            $dbUserForm = $singleDbForm ? $dbNameForm : $dbPrefixForm.'chamilo_user';
        }
        echo '<div class="RequirementHeading"><h2>' . display_step_sequence() .get_lang('DBSetting') . '</h2></div>';
        echo '<div class="RequirementContent">';
        echo get_lang('DBSettingUpgradeIntro');
        echo '</div>';
    } else {
        if (empty($dbPrefixForm)) { //make sure there is a default value for db prefix
            $dbPrefixForm = '';
        }
        echo '<div class="RequirementHeading"><h2>' . display_step_sequence() .get_lang('DBSetting') . '</h2></div>';
        echo '<div class="RequirementContent">';
        echo get_lang('DBSettingIntro');
        echo '</div>';
    }
    ?>
    </td>
    </tr>
    <tr>
    <td>
    <table class="data_table_no_border">
    <tr>
      <td width="40%"><?php echo get_lang('DBHost'); ?> </td>
      <?php if ($installType == 'update'): ?>
      <td width="30%"><input type="hidden" name="dbHostForm" value="<?php echo htmlentities($dbHostForm); ?>" /><?php echo $dbHostForm; ?></td>
      <td width="30%">&nbsp;</td>
      <?php else: ?>
      <td width="30%"><input type="text" size="25" maxlength="50" name="dbHostForm" value="<?php echo htmlentities($dbHostForm); ?>" /></td>
      <td width="30%"><?php echo get_lang('EG').' localhost'; ?></td>
      <?php endif; ?>
    </tr>
    <tr>
    <?php
    //database user username
    $example_login = get_lang('EG').' root';
    display_database_parameter($installType, get_lang('DBLogin'), 'dbUsernameForm', $dbUsernameForm, $example_login);

    //database user password
    $example_password = get_lang('EG').' '.api_generate_password();
    display_database_parameter($installType, get_lang('DBPassword'), 'dbPassForm', $dbPassForm, $example_password);

    echo '<input type="hidden" name="enableTrackingForm" value="1" />';

    $style = '';
    if ($installType == INSTALL_TYPE_UPDATE) {
        $style = '';
    }

    //Database Name fix replace weird chars
    if ($installType != INSTALL_TYPE_UPDATE) {
        $dbNameForm = str_replace(array('-','*', '$', ' ', '.'), '', $dbNameForm);
        $dbNameForm = replace_dangerous_char($dbNameForm);
    }

    display_database_parameter($installType, get_lang('MainDB'), 'dbNameForm',  $dbNameForm,  '&nbsp;', null, 'id="optional_param1" '.$style);

    //Only for updates we show this options
    if ($installType == INSTALL_TYPE_UPDATE) {
        display_database_parameter($installType, get_lang('StatDB'), 'dbStatsForm', $dbStatsForm, '&nbsp;', null, 'id="optional_param2" '.$style);
        if ($installType == INSTALL_TYPE_UPDATE && in_array($_POST['old_version'], $update_from_version_6)) {
            display_database_parameter($installType, get_lang('ScormDB'), 'dbScormForm', $dbScormForm, '&nbsp;', null, 'id="optional_param3" '.$style);
        }
        display_database_parameter($installType, get_lang('UserDB'), 'dbUserForm', $dbUserForm, '&nbsp;', null, 'id="optional_param4" '.$style);
    }
    ?>
    <tr>
        <td></td>
        <td>
            <button type="submit" class="btn" name="step3" value="<?php echo get_lang('CheckDatabaseConnection'); ?>" >
                <?php echo get_lang('CheckDatabaseConnection'); ?></button>
        </td>
    </tr>
    <tr>
        <td>

        <?php

        $dbConnect = test_db_connect($dbHostForm, $dbUsernameForm, $dbPassForm, $singleDbForm, $dbPrefixForm, $dbNameForm);

        $database_exists_text = '';

        if (database_exists($dbNameForm)) {
            $database_exists_text = '<div class="warning-message">'.get_lang('ADatabaseWithTheSameNameAlreadyExists').'</div>';
        } else {
            if ($dbConnect == -1) {
                 $database_exists_text = '<div class="warning-message">'.sprintf(get_lang('UserXCantHaveAccessInTheDatabaseX'), $dbUsernameForm, $dbNameForm).'</div>';
            } else {
                 //Try to create the database
                $user_can_create_databases = false;
                $multipleDbCheck = @Database::query("CREATE DATABASE ".mysql_real_escape_string($dbNameForm));
                if ($multipleDbCheck !== false) {
                    $multipleDbCheck = @Database::query("DROP DATABASE IF EXISTS ".mysql_real_escape_string($dbNameForm));
                    $user_can_create_databases = true;
                }

                if ($user_can_create_databases) {
                    $database_exists_text = '<div class="normal-message">'.sprintf(get_lang('DatabaseXWillBeCreated'), $dbNameForm, $dbUsernameForm).'</div>';
                } else {
                    $dbConnect = 0;
                    $database_exists_text = '<div class="warning-message">'.sprintf(get_lang('DatabaseXCantBeCreatedUserXDoestHaveEnoughPermissions'), $dbNameForm, $dbUsernameForm).'</div>';
                }
            }
        }

        if ($dbConnect == 1): ?>
        <td colspan="2">
            <?php echo $database_exists_text ?>
            <div id="db_status" class="confirmation-message">
                Database host: <strong><?php echo Database::get_host_info(); ?></strong><br />
                Database server version: <strong><?php echo Database::get_server_info(); ?></strong><br />
                Database client version: <strong><?php echo Database::get_client_info(); ?></strong><br />
                Database protocol version: <strong><?php echo Database::get_proto_info(); ?></strong>
                <div style="clear:both;"></div>
            </div>
        </td>
        <?php else: ?>
        <td colspan="2">
            <?php echo $database_exists_text ?>
            <div id="db_status" style="float:left;" class="error-message">
                <div style="float:left;">
                    <strong><?php echo get_lang('FailedConectionDatabase'); ?></strong><br />
                    <strong>Database error: <?php echo Database::errno(); ?></strong><br />
                    <?php echo Database::error().'<br />'; ?>
                </div>
            </div>
        </td>
        <?php endif; ?>
    </tr>
    <tr>
      <td>
          <button type="submit" name="step2" class="back" value="&lt; <?php echo get_lang('Previous'); ?>" ><?php echo get_lang('Previous'); ?></button>
      </td>
      <td>&nbsp;</td>
      <td align="right">
          <input type="hidden" name="is_executable" id="is_executable" value="-" />
           <?php if ($dbConnect == 1) { ?>
            <button type="submit"  class="btn next" name="step4" value="<?php echo get_lang('Next'); ?> &gt;" /><?php echo get_lang('Next'); ?></button>
          <?php } else { ?>
            <button disabled="disabled" type="submit" class="btn next disabled" name="step4" value="<?php echo get_lang('Next'); ?> &gt;" /><?php echo get_lang('Next'); ?></button>
          <?php } ?>
      </td>
    </tr>
    </table>
    <?php
}
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    /*$checked_writable = api_get_path(SYS_PATH).'home/';
    if (!is_writable($checked_writable)) {
        $notwritable[] = realpath($checked_writable);
        @chmod($checked_writable, $perm);
    }*/

<<<<<<< HEAD
    /*$checked_writable = api_get_path(CONFIGURATION_PATH).'configuration.php';
    if (file_exists($checked_writable) && !is_writable($checked_writable)) {
        $notwritable[] = $checked_writable;
        @chmod($checked_writable, $perm_file);
    }*/
=======
/**
 * Displays step 4 of the installation - configuration settings about Chamilo itself.
 */
function display_configuration_settings_form($installType, $urlForm, $languageForm, $emailForm, $adminFirstName, $adminLastName, $adminPhoneForm, $campusForm, $institutionForm, $institutionUrlForm, $encryptPassForm, $allowSelfReg, $allowSelfRegProf, $loginForm, $passForm) {
    if ($installType != 'update' && empty($languageForm)) {
        $languageForm = $_SESSION['install_language'];
    }
    echo '<div class="RequirementHeading">';
    echo "<h2>" . display_step_sequence() . get_lang("CfgSetting") . "</h2>";
    echo '</div>';
    echo '<div class="RequirementContent">';
    echo '<p>'.get_lang('ConfigSettingsInfo').' <strong>main/inc/conf/configuration.php</strong></p>';
    echo '</div>';

    echo '<fieldset>';
    echo '<legend>'.get_lang('Administrator').'</legend>';
    echo '<table class="data_table_no_border">';

    //Parameter 1: administrator's login

    display_configuration_parameter($installType, get_lang('AdminLogin'), 'loginForm', $loginForm, $installType == 'update');

    //Parameter 2: administrator's password
    if ($installType != 'update') {
        display_configuration_parameter($installType, get_lang('AdminPass'), 'passForm', $passForm, false);
    }

    //Parameters 3 and 4: administrator's names
    if (api_is_western_name_order()) {
        display_configuration_parameter($installType, get_lang('AdminFirstName'), 'adminFirstName', $adminFirstName);
        display_configuration_parameter($installType, get_lang('AdminLastName'), 'adminLastName', $adminLastName);
    } else {
        display_configuration_parameter($installType, get_lang('AdminLastName'), 'adminLastName', $adminLastName);
        display_configuration_parameter($installType, get_lang('AdminFirstName'), 'adminFirstName', $adminFirstName);
    }

    //Parameter 3: administrator's email
    display_configuration_parameter($installType, get_lang('AdminEmail'), 'emailForm', $emailForm);

    //Parameter 6: administrator's telephone
    display_configuration_parameter($installType, get_lang('AdminPhone'), 'adminPhoneForm', $adminPhoneForm);

    echo '</table>';
    echo '</fieldset>';

    echo '<fieldset>';
    echo '<legend>'.get_lang('Platform').'</legend>';

    echo '<table class="data_table_no_border">';

    //First parameter: language
    echo "<tr>";
    echo '<td>'.get_lang('MainLang')."&nbsp;&nbsp;</td>";
    if ($installType == 'update') {
        echo '<td><input type="hidden" name="languageForm" value="'.api_htmlentities($languageForm, ENT_QUOTES).'" />'.$languageForm."</td>";

    } else { // new installation
        echo '<td>';
        display_language_selection_box('languageForm', $languageForm);
        echo "</td>\n";
    }
    echo "</tr>\n";


    //Second parameter: Chamilo URL
    echo "<tr>";
    echo '<td>'.get_lang('ChamiloURL').' (<font color="red">'.get_lang('ThisFieldIsRequired')."</font>)&nbsp;&nbsp;</td>";

    if ($installType == 'update') {
        echo '<td>'.api_htmlentities($urlForm, ENT_QUOTES)."</td>\n";
    } else {
        echo '<td><input type="text" size="40" maxlength="100" name="urlForm" value="'.api_htmlentities($urlForm, ENT_QUOTES).'" />'."</td>";
    }
    echo "</tr>";


    //Parameter 9: campus name
    display_configuration_parameter($installType, get_lang('CampusName'), 'campusForm', $campusForm);

    //Parameter 10: institute (short) name
    display_configuration_parameter($installType, get_lang('InstituteShortName'), 'institutionForm', $institutionForm);

    //Parameter 11: institute (short) name
    display_configuration_parameter($installType, get_lang('InstituteURL'), 'institutionUrlForm', $institutionUrlForm);

    ?>
    <tr>
      <td><?php echo get_lang("EncryptMethodUserPass"); ?> :</td>
      <?php if ($installType == 'update') { ?>
      <td><input type="hidden" name="encryptPassForm" value="<?php echo $encryptPassForm; ?>" /><?php echo $encryptPassForm; ?></td>
      <?php } else { ?>
      <td>
          <div class="control-group">
              <label class="radio inline">
                  <input  type="radio" name="encryptPassForm" value="sha1" id="encryptPass1" <?php echo ($encryptPassForm == 'sha1') ? 'checked="checked" ': ''; ?>/><?php echo 'sha1'; ?>
              </label>

              <label class="radio inline">
                  <input type="radio" name="encryptPassForm" value="md5" id="encryptPass0" <?php echo $encryptPassForm == 1 ? 'checked="checked" ' : ''; ?>/><?php echo 'md5'; ?>
              </label>

              <label class="radio inline">
                  <input type="radio" name="encryptPassForm" value="none" id="encryptPass2" <?php echo $encryptPassForm === '0' or $encryptPassForm === 0 ? 'checked="checked" ':''; ?>/><?php echo get_lang('None'); ?>
              </label>
          </div>
          </td>
      <?php } ?>
    </tr>
    <tr>
      <td><?php echo get_lang('AllowSelfReg'); ?> :</td>

      <?php if ($installType == 'update'): ?>
      <td><input type="hidden" name="allowSelfReg" value="<?php echo $allowSelfReg; ?>" /><?php echo $allowSelfReg ? get_lang('Yes') : get_lang('No'); ?></td>
      <?php else: ?>
      <td>
          <div class="control-group">
            <label class="radio inline">
                <input type="radio" name="allowSelfReg" value="1" id="allowSelfReg1" <?php echo $allowSelfReg ? 'checked="checked" ' : ''; ?>/> <?php echo get_lang('Yes'); ?>
            </label>
            <label class="radio inline">
                <input type="radio" name="allowSelfReg" value="0" id="allowSelfReg0" <?php echo $allowSelfReg ? '' : 'checked="checked" '; ?>/><?php echo get_lang('No'); ?>
            </label>
          </div>
      </td>
      <?php endif; ?>

    </tr>
    <tr>
      <td><?php echo get_lang('AllowSelfRegProf'); ?> :</td>

      <?php if ($installType == 'update'): ?>
      <td><input type="hidden" name="allowSelfRegProf" value="<?php echo $allowSelfRegProf; ?>" /><?php echo $allowSelfRegProf? get_lang('Yes') : get_lang('No'); ?></td>
      <?php else: ?>
      <td>
          <div class="control-group">
            <label class="radio inline">
                <input type="radio" name="allowSelfRegProf" value="1" id="allowSelfRegProf1" <?php echo $allowSelfRegProf ? 'checked="checked" ' : ''; ?>/>
            <?php echo get_lang('Yes'); ?>
            </label>
            <label class="radio inline">
                <input type="radio" name="allowSelfRegProf" value="0" id="allowSelfRegProf0" <?php echo $allowSelfRegProf ? '' : 'checked="checked" '; ?>/>
            <?php echo get_lang('No'); ?>
            </label>
          </div>
      </td>
      <?php endif; ?>

    </tr>
    <tr>
        <td>
            <button type="submit" class="btn back" name="step3" value="&lt; <?php echo get_lang('Previous'); ?>" /><?php echo get_lang('Previous'); ?></button>
        </td>
        <td align="right">
            <input type="hidden" name="is_executable" id="is_executable" value="-" />
            <button class="btn next" type="submit" name="step5" value="<?php echo get_lang('Next'); ?> &gt;" /><?php echo get_lang('Next'); ?></button></td>
    </tr>
    </fieldset>
    </table>
    <?php
}
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    // Second, if this fails, report an error

    // The user would have to adjust the permissions manually

    if (count($notwritable) > 0) {
        $html .= '<div class="error-message">';
        $html .= '<center><h3>'.translate('Warning').'</h3></center>';
        $html .=  sprintf(
            translate('NoWritePermissionPleaseReadInstallGuide'),
            '</font>
            <a href="../../documentation/installation_guide.html" target="blank">',
            '</a> <font color="red">'
        );
        $html .= '</div>';

        $html .= '<ul>';
        foreach ($notwritable as $value) {
            $html .= '<li>'.$value.'</li>';
        }
        $html .= '</ul>';
    } elseif (file_exists(api_get_path(CONFIGURATION_PATH).'configuration.php')) {
        // Check wether a Chamilo configuration file already exists.
        $html .= '<div class="warning-message"><h4><center>';
        $html .= translate('WarningExistingDokeosInstallationDetected');
        $html .= '</center></h4></div>';
    }

    return $html;
}

<<<<<<< HEAD

=======
/**
 * Lockis settings that can't be changed in other portals
 */
function locking_settings() {
    $access_url_locked_settings = api_get_locked_settings();
    $table = Database::get_main_table(TABLE_MAIN_SETTINGS_CURRENT);
    foreach ($access_url_locked_settings as $setting) {
        $sql = "UPDATE $table SET access_url_locked = 1 WHERE variable  = '$setting'";
        Database::query($sql);
    }
}

function update_dir_and_files_permissions() {
    $table = Database::get_main_table(TABLE_MAIN_SETTINGS_CURRENT);
    $permissions_for_new_directories = isset($_SESSION['permissions_for_new_directories']) ? $_SESSION['permissions_for_new_directories'] : 0770;
    $permissions_for_new_files = isset($_SESSION['permissions_for_new_files']) ? $_SESSION['permissions_for_new_files'] : 0660;
    // use decoct() to store as string
    $sql = "UPDATE $table SET selected_value = '0".decoct($permissions_for_new_directories)."' WHERE variable  = 'permissions_for_new_directories'";
    Database::query($sql);

    $sql = "UPDATE $table SET selected_value = '0".decoct($permissions_for_new_files)."' WHERE variable  = 'permissions_for_new_files'";
    Database::query($sql);

    unset($_SESSION['permissions_for_new_directories']);
    unset($_SESSION['permissions_for_new_files']);
}

function compare_setting_values($current_value, $wanted_value) {
    $current_value_string = $current_value;
    $current_value = (float)$current_value;
    $wanted_value = (float)$wanted_value;

    if ($current_value >= $wanted_value) {
        return Display::label($current_value_string, 'success');
    } else {
        return Display::label($current_value_string, 'important');
    }
}
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

function check_course_script_interpretation($course_dir, $course_attempt_name, $file = 'test.php')
{
    $output = false;
    //Write in file
    $file_name = $course_dir.'/'.$file;
    $content = '<?php echo "123"; exit;';

    if (is_writable($file_name)) {
<<<<<<< HEAD
        if ($handler = @fopen($file_name, "w")) {
            //write content
            if (fwrite($handler , $content)) {

                $file = api_get_path(SYS_COURSE_PATH).$course_attempt_name.'/'.$file;
                if (file_exists($file)) {
                    return true;
                }

                //You can't access to a course file like this. You will be prompted to the installation process.
                //If you access
                $sock_errno = '';
                $sock_errmsg = '';

=======
        if ($handler = @fopen($file_name, "w")) {
            //write content
            if (fwrite($handler, $content)) {
                $sock_errno = '';
                $sock_errmsg = '';
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
                $url = api_get_path(WEB_COURSE_PATH).$course_attempt_name.'/'.$file;

                $parsed_url = parse_url($url);
                //$scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] : ''; //http
                $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
                $path = isset($parsed_url['path']) ? $parsed_url['path'] : '/';
                $port = isset($parsed_url['port']) ? $parsed_url['port'] : '80';

                //Check fsockopen (doesn't work with https)
                if ($fp = @fsockopen(str_replace('http://', '', $url), -1, $sock_errno, $sock_errmsg, 60)) {
                    $out  = "GET $path HTTP/1.1\r\n";
                    $out .= "Host: $host\r\n";
                    $out .= "Connection: Close\r\n\r\n";

                    fwrite($fp, $out);
                    while (!feof($fp)) {
                        $result = str_replace("\r\n", '', fgets($fp, 128));
                        if (!empty($result) && $result == '123') {
                            $output = true;
                        }
                    }
                    fclose($fp);
                    //Check allow_url_fopen
                } elseif (ini_get('allow_url_fopen')) {
                    if ($fp = @fopen($url, 'r')) {
                        while ($result = fgets($fp, 1024)) {
                            if (!empty($result) && $result == '123') {
                                $output = true;
                            }
                        }
                        fclose($fp);
                    }
                    // Check if has support for cURL
                } elseif (function_exists('curl_init')) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    //curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $result = curl_exec($ch);
                    if (!empty($result) && $result == '123') {
                        $output = true;
                    }
                    curl_close($ch);
                }
            }
            @fclose($handler);
        }
    }

    return $output;
}
<<<<<<< HEAD


/**
 * This function checks if the given folder is writable
 */
function check_writable_root_path($folder, $suggestion = false)
{
    if (is_writable(api_get_path(SYS_PATH).$folder)) {
        return Display::label(translate('Writable'), 'success');
    } else {
        if ($suggestion) {
            return Display::label(translate('NotWritable'), 'info');
        } else {
            return Display::label(translate('NotWritable'), 'important');
        }
    }
}
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
