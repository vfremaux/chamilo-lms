<?php
/* For licensing terms, see /license.txt */

/**
 * File: internationalization.lib.php
 * Internationalization library for Chamilo 1.8.7 LMS
 * A library implementing internationalization related functions.
 * License: GNU General Public License Version 3 (Free Software Foundation)
 * @author Ivan Tcholakov, <ivantcholakov@gmail.com>, 2009, 2010
 * @author More authors, mentioned in the correpsonding fragments of this source.
 * @package chamilo.library
 */
<<<<<<< HEAD
use Patchwork\Utf8 as u;
use Symfony\Component\Intl\DateFormatter\IntlDateFormatter;
=======

/**
 * Constants
 */

// Special tags for marking untranslated variables.
define('SPECIAL_OPENING_TAG', '[=');
define('SPECIAL_CLOSING_TAG', '=]');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

// Predefined date formats in Chamilo provided by the language sub-system.
// To be used as a parameter for the function api_format_date()

define('TIME_NO_SEC_FORMAT', 0); // 15:23
define('DATE_FORMAT_SHORT', 1); // Aug 25, 09
define('DATE_FORMAT_LONG', 2); // Monday August 25, 09
define('DATE_FORMAT_LONG_NO_DAY', 10); // August 25, 2009
define('DATE_TIME_FORMAT_LONG', 3); // Monday August 25, 2009 at 03:28 PM

define('DATE_FORMAT_NUMBER', 4); // 25.08.09
define('DATE_TIME_FORMAT_LONG_24H', 5); // August 25, 2009 at 15:28
define('DATE_TIME_FORMAT_SHORT', 6); // Aug 25, 2009 at 03:28 PM
define('DATE_TIME_FORMAT_SHORT_TIME_FIRST', 7); // 03:28 PM, Aug 25 2009
define('DATE_FORMAT_NUMBER_NO_YEAR', 8); // 25.08 dd-mm
define('DATE_FORMAT_ONLY_DAYNAME', 9); // Monday, Sunday, etc

// Formatting person's name.
<<<<<<< HEAD
define('PERSON_NAME_COMMON_CONVENTION', 0); // Formatting a person's name using the pattern as it has been
// configured in the internationalization database for every language.
// This (default) option would be the most used.
// The followind options may be used in limited number of places for overriding the common convention:
define('PERSON_NAME_WESTERN_ORDER', 1); // Formatting a person's name in Western order: first_name last_name
define('PERSON_NAME_EASTERN_ORDER', 2); // Formatting a person's name in Eastern order: last_name first_name
define('PERSON_NAME_LIBRARY_ORDER', 3); // Contextual: formatting person's name in library order: last_name, first_name
define('PERSON_NAME_EMAIL_ADDRESS', PERSON_NAME_WESTERN_ORDER); // Contextual: formatting a person's name assotiated with an email-address. Ivan: I am not sure how seems email servers an clients would interpret name order, so I assign the Western order.
define('PERSON_NAME_DATA_EXPORT', PERSON_NAME_EASTERN_ORDER); // Contextual: formatting a person's name for data-exporting operarions. For backward compatibility this format has been set to Eastern order.
=======
// Formatting a person's name using the pattern as it has been
// configured in the internationalization database for every language.
// This (default) option would be the most used.
define('PERSON_NAME_COMMON_CONVENTION', 0);
// The following options may be used in limited number of places for overriding the common convention:

// Formatting a person's name in Western order: first_name last_name
define('PERSON_NAME_WESTERN_ORDER', 1);
// Formatting a person's name in Eastern order: last_name first_name
define('PERSON_NAME_EASTERN_ORDER', 2);
// Contextual: formatting person's name in library order: last_name, first_name
define('PERSON_NAME_LIBRARY_ORDER', 3);
// Contextual: formatting a person's name assotiated with an email-address. Ivan: I am not sure how seems email servers an clients would interpret name order, so I assign the Western order.
define('PERSON_NAME_EMAIL_ADDRESS', PERSON_NAME_WESTERN_ORDER);
// Contextual: formatting a person's name for data-exporting operations. For backward compatibility this format has been set to Eastern order.
define('PERSON_NAME_DATA_EXPORT', PERSON_NAME_EASTERN_ORDER);

// The following constants are used for tuning language detection functionality.
// We reduce the text for language detection to the given number of characters
// for increasing speed and to decrease memory consumption.
define ('LANGUAGE_DETECT_MAX_LENGTH', 2000);
// Maximum allowed difference in so called delta-points for aborting certain language detection.
// The value 80000 is good enough for speed and detection accuracy.
// If you set the value of $max_delta too low, no language will be recognized.
// $max_delta = 400 * 350 = 140000 is the best detection with lowest speed.
define ('LANGUAGE_DETECT_MAX_DELTA', 140000);

/**
 * Initialization of some internal default values in the internationalization library.
 * @return void
 * Note: This function should be called only once in the global initialization script.
 */
function api_initialize_internationalization()
{
    if (MBSTRING_INSTALLED) {
        @ini_set('mbstring.func_overload', 0);
        @ini_set('mbstring.encoding_translation', 0);
        @ini_set('mbstring.http_input', 'pass');
        @ini_set('mbstring.http_output', 'pass');
        @ini_set('mbstring.language', 'neutral');
    }
    api_set_internationalization_default_encoding('UTF-8');
}

/**
 * Sets the internal default encoding for the multi-byte string functions.
 * @param string $encoding		The specified default encoding.
 * @return string				Returns the old value of the default encoding.
 */
function api_set_internationalization_default_encoding($encoding) {
    $encoding = api_refine_encoding_id($encoding);
    $result = _api_mb_internal_encoding();
    _api_mb_internal_encoding($encoding);
    _api_mb_regex_encoding($encoding);
    _api_iconv_set_encoding('iconv_internal_encoding', $encoding);

    return $result;
}

/**
 * Language support
 */

// These variables are for internal purposes only, they serve the function api_is_translated().
$_api_is_translated = false;
$_api_is_translated_call = false;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

/**
 * Returns a translated (localized) string, called by its identificator.
 * @param string $variable                This is the identificator (name) of the translated string to be retrieved.
 * Notes:
 * Translations are created many contributors through using a special tool: Chamilo Translation Application.
 * @link http://translate.chamilo.org/
 */
function get_lang($variable)
{
    global $app;
    $translated = $app['translator']->trans($variable);
    if ($translated == $variable) {
        // Check the langVariable for BC
        $translated = $app['translator']->trans("lang$variable");
        if ($translated == "lang$variable") {
            return $variable;
        }
    }
    return $translated;
}

/**
 * Gets the current interface language.
  * @return string  The current language of the interface.
 */
function api_get_interface_language($purified = false, $check_sub_language = false)
{
    global $app;
    return $app['language'];
}

/**
 * Validates the input language (english, spanish, etc)
 * in order always to return a language that is enabled in the system.
 * This function is to be used for data import when provided language should be validated.
 * @param string $language The language to be validated.
 * @return string Returns the input language identificator. If the input language is not enabled, platform language is returned then.
 */
function api_get_valid_language($language)
{
    static $enabled_languages;
    if (!isset($enabled_languages)) {
        $enabled_languages_info = api_get_languages();
        $enabled_languages = $enabled_languages_info['folder'];
    }
    $language = str_replace('_km', '_KM', strtolower(trim($language)));
    if (empty($language) || !in_array($language, $enabled_languages)) {
        $language = api_get_setting('platformLanguage');
    }
    return $language;
}

/**
 * Returns a purified language id, without possible suffixes that will disturb language identification in certain cases.
 * @param string $language    The input language identificator, for example 'french_unicode'.
 * @param string            The same purified or filtered language identificator, for example 'french'.
 */
function api_purify_language_id($language)
{
    static $purified = array();
    if (!isset($purified[$language])) {
        $purified[$language] = trim(
            str_replace(array('_unicode', '_latin', '_corporate', '_org', '_km'), '', strtolower($language))
        );
    }
    return $purified[$language];
}

/**
 * Gets language isocode column from the language table, taking the given language as a query parameter.
 * @param string $language        This is the name of the folder containing translations for the corresponding language (e.g arabic, english).
 * @param string $default_code    This is the value to be returned if there was no code found corresponding to the given language.
 * If $language is omitted, interface language is assumed then.
 * @return string            The found isocode or null on error.
 * Returned codes are according to the following standards (in order of preference):
 * -  ISO 639-1 : Alpha-2 code (two-letters code - en, fr, es, ...)
 * -  RFC 4646  : five-letter code based on the ISO 639 two-letter language codes
 *    and the ISO 3166 two-letter territory codes (pt-BR, ...)
 * -  ISO 639-2 : Alpha-3 code (three-letters code - ast, fur, ...)
 */
function api_get_language_isocode($language = null, $default_code = 'en')
{
    static $iso_code = array();
    if (empty($language)) {
        $language = api_get_interface_language(false, true);
    }

    if (!isset($iso_code[$language])) {
        $sql = "SELECT isocode
                FROM ".Database::get_main_table(TABLE_MAIN_LANGUAGE)."
                WHERE dokeos_folder = '$language'";
        $result = Database::query($sql);
        if (Database::num_rows($result)) {
            $result = Database::fetch_array($result);
            $iso_code[$language] = trim($result['isocode']);
        } else {
            $language_purified_id = api_purify_language_id($language);
            $iso_code[$language] = isset($iso_code[$language_purified_id]) ? $iso_code[$language_purified_id] : null;
        }
        if (empty($iso_code[$language])) {
            $iso_code[$language] = $default_code;
        }
    }

    return $iso_code[$language];
}

/**
 * Gets language iso code column from the language table
 *
 * @return array    An array with the current iso codes
 *
 * */
function api_get_platform_isocodes()
{
    $iso_code = array();
    $sql_result = Database::query(
        "SELECT isocode FROM ".Database::get_main_table(TABLE_MAIN_LANGUAGE)."
        ORDER BY isocode "
    );
    if (Database::num_rows($sql_result)) {
        while ($row = Database::fetch_array($sql_result)) {
            $iso_code[] = trim($row['isocode']);
        }
    }
    return $iso_code;
}

/**
 * Gets text direction according to the given language.
 * @param string $language    This is the name of the folder containing translations for the corresponding language (e.g 'arabic', 'english', ...).
 * ISO-codes are acceptable too ('ar', 'en', ...). If $language is omitted, interface language is assumed then.
 * @return string            The correspondent to the language text direction ('ltr' or 'rtl').
 */
function api_get_text_direction($language = null)
{
    static $text_direction = array();
<<<<<<< HEAD
=======
    /*
     * Not necessary to validate the language because the list if rtl/ltr is harcoded
     *
    /*
     $language_is_supported = api_is_language_supported($language);
    if (!$language_is_supported || empty($language)) {
        $language = api_get_interface_language(false, true);
    }*/
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    if (empty($language)) {
        $language = api_get_interface_language();
    }
    if (!isset($text_direction[$language])) {
        $text_direction[$language] = in_array(
            api_purify_language_id($language),
            array(
                'arabic',
                'ar',
                'dari',
                'prs',
                'hebrew',
                'he',
                'iw',
                'pashto',
                'ps',
                'persian',
                'fa',
                'ur',
                'yiddish',
                'yid'
            )
        ) ? 'rtl' : 'ltr';
    }
<<<<<<< HEAD

    return $text_direction[$language];
}

=======
    return $text_direction[$language];
}

/**
 * This function checks whether a given language can use Latin 1 encoding.
 * In the past (Chamilo 1.8.6.2), the function was used in the installation script only once.
 * It is not clear whether this function would be use useful for something else in the future.
 * @param string $language	The checked language.
 * @return bool				TRUE if the given language can use Latin 1 encoding (ISO-8859-15, ISO-8859-1, WINDOWS-1252, ...), FALSE otherwise.
 */
function api_is_latin1_compatible($language) {
    static $latin1_languages;
    if (!isset($latin1_languages)) {
        $latin1_languages = _api_get_latin1_compatible_languages();
    }
    $language = api_purify_language_id($language);
    return in_array($language, $latin1_languages);
}

/**
 * Language recognition
 * Based on the publication:
 * W. B. Cavnar and J. M. Trenkle. N-gram-based text categorization.
 * Proceedings of SDAIR-94, 3rd Annual Symposium on Document Analysis
 * and Information Retrieval, 1994.
 * @link http://citeseer.ist.psu.edu/cache/papers/cs/810/http:zSzzSzwww.info.unicaen.frzSz~giguetzSzclassifzSzcavnar_trenkle_ngram.pdf/n-gram-based-text.pdf
 */
function api_detect_language(&$string, $encoding = null) {
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    if (empty($string)) {
        return false;
    }
    $result_array = &_api_compare_n_grams(_api_generate_n_grams(api_substr($string, 0, LANGUAGE_DETECT_MAX_LENGTH, $encoding), $encoding), $encoding);
    if (empty($result_array)) {
        return false;
    }
    list($key, $delta_points) = each($result_array);
    return strstr($key, ':', true);
}

/**
 * Date and time conversions and formats
 */

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
/**
 * Returns an alphabetized list of timezones in an associative array that can be used to populate a select
 *
 * @return array List of timezone identifiers
 *
 * @author Guillaume Viguier <guillaume.viguier@beeznest.com>
 */
function api_get_timezones()
{
    $timezone_identifiers = DateTimeZone::listIdentifiers();
    sort($timezone_identifiers);
    $out = array();
    foreach ($timezone_identifiers as $tz) {
        $out[$tz] = $tz;
    }
    $null_option = array('' => '');
    $result = array_merge($null_option, $out);
    return $result;
}

/**
 * Returns the timezone to be converted to/from, based on user or admin preferences
 *
 * @return string The timezone chosen
 */
function _api_get_timezone()
{
    $userId = api_get_user_id();

    // First, get the default timezone of the server
    $to_timezone = date_default_timezone_get();
    // Second, see if a timezone has been chosen for the platform
    $timezone_value = api_get_setting('timezone_value', 'timezones');
    if ($timezone_value != null) {
        $to_timezone = $timezone_value;
    }

    // If allowed by the administrator
    $use_users_timezone = api_get_setting('use_users_timezone', 'timezones');

    if ($use_users_timezone == 'true' && !empty($userId) && !api_is_anonymous()) {
        $userInfo = api_get_user_info();
        $extraFields = $userInfo['extra_fields'];
        // Get the timezone based on user preference, if it exists
        // $timezone_user = UserManager::get_extra_user_data_by_field($userId, 'timezone');
        if (isset($extraFields['extra_timezone']) && $extraFields['extra_timezone'] != null) {
            $to_timezone = $extraFields['extra_timezone'];
        }
    }

    return $to_timezone;
}

/**
 * Returns the given date as a DATETIME in UTC timezone.
 * This function should be used before entering any date in the DB.
 *
 * @param mixed $time The date to be converted (can be a string supported by date() or a timestamp)
 * @param bool $return_null_if_invalid_date if the date is not correct return null instead of the current date
 * @param bool $returnObj
 * @return string The DATETIME in UTC to be inserted in the DB, or null if the format of the argument is not supported
 *
 * @author Julio Montoya - Adding the 2nd parameter
 * @author Guillaume Viguier <guillaume.viguier@beeznest.com>
 */
function api_get_utc_datetime($time = null, $return_null_if_invalid_date = false, $returnObj = false)
{
    $from_timezone = _api_get_timezone();

    $to_timezone = 'UTC';
    if (is_null($time) || empty($time) || $time == '0000-00-00 00:00:00') {
        if ($return_null_if_invalid_date) {
            return null;
        }
        if ($returnObj) {
            return $date = new DateTime(gmdate('Y-m-d H:i:s'));
        }
        return gmdate('Y-m-d H:i:s');
    }
    // If time is a timestamp, return directly in utc
    if (is_numeric($time)) {
        $time = intval($time);
        return gmdate('Y-m-d H:i:s', $time);
    }
    try {
        $date = new DateTime($time, new DateTimezone($from_timezone));
        $date->setTimezone(new DateTimeZone($to_timezone));
        if ($returnObj) {
            return $date;
        } else {
            return $date->format('Y-m-d H:i:s');
        }
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Returns a DATETIME string converted to the right timezone
 * @param mixed The time to be converted
 * @param string The timezone to be converted to.
 * If null, the timezone will be determined based on user preference,
 * or timezone chosen by the admin for the platform.
 * @param string The timezone to be converted from. If null, UTC will be assumed.
 * @return string The converted time formatted as Y-m-d H:i:s
 *
 * @author Guillaume Viguier <guillaume.viguier@beeznest.com>
 */
<<<<<<< HEAD
function api_get_local_time(
    $time = null,
    $to_timezone = null,
    $from_timezone = null,
    $return_null_if_invalid_date = false
) {
=======
function api_get_local_time($time = null, $to_timezone = null, $from_timezone = null, $return_null_if_invalid_date = false)
{
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    // Determining the timezone to be converted from
    if (is_null($from_timezone)) {
        $from_timezone = 'UTC';
    }
    // Determining the timezone to be converted to
    if (is_null($to_timezone)) {
        $to_timezone = _api_get_timezone();
    }
    // If time is a timestamp, convert it to a string
    if (is_null($time) || empty($time) || $time == '0000-00-00 00:00:00') {
        if ($return_null_if_invalid_date) {
            return null;
        }
        $from_timezone = 'UTC';
        $time = gmdate('Y-m-d H:i:s');
    }
    if (is_numeric($time)) {
        $time = intval($time);
        $from_timezone = 'UTC';
        $time = gmdate('Y-m-d H:i:s', $time);
    }
    try {
        $date = new DateTime($time, new DateTimezone($from_timezone));
        $date->setTimezone(new DateTimeZone($to_timezone));
        return $date->format('Y-m-d H:i:s');
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Converts a string into a timestamp safely (handling timezones), using strtotime
 *
 * @param string String to be converted
 * @param string Timezone (if null, the timezone will be determined based on user preference, or timezone chosen by the admin for the platform)
 * @return int Timestamp
 *
 * @author Guillaume Viguier <guillaume.viguier@beeznest.com>
 */
function api_strtotime($time, $timezone = null)
{
    $system_timezone = date_default_timezone_get();
    if (!empty($timezone)) {
        date_default_timezone_set($timezone);
    }
    $timestamp = strtotime($time);
    date_default_timezone_set($system_timezone);
    return $timestamp;
}

/**
 * Returns formatted date/time, correspondent to a given language.
 * The given date should be in the timezone chosen by the administrator and/or user. Use api_get_local_time to get it.
 *
 * @author Patrick Cool <patrick.cool@UGent.be>, Ghent University
 * @author Christophe Gesche<gesche@ipm.ucl.ac.be>
 *         originally inspired from from PhpMyAdmin
 * @author Ivan Tcholakov, 2009, code refactoring, adding support for predefined date/time formats.
 * @author Guillaume Viguier <guillaume.viguier@beeznest.com>
 *
 * @param mixed Timestamp or datetime string
 * @param mixed Date format (string or int; see date formats in the Chamilo system: TIME_NO_SEC_FORMAT, DATE_FORMAT_SHORT, DATE_FORMAT_LONG, DATE_TIME_FORMAT_LONG)
 * @param string $language (optional)        Language indentificator. If it is omited, the current interface language is assumed.
 * @return string                            Returns the formatted date.
 *
 * @link http://php.net/manual/en/function.strftime.php
 */
function api_format_date($time, $format = null, $language = null)
{
    if (is_string($time)) {
        $time = strtotime($time);
    }

    if (is_null($format)) {
        $format = DATE_TIME_FORMAT_LONG;
    }

    $datetype = null;
    $timetype = null;

    if (is_int($format)) {
        switch ($format) {
            case DATE_FORMAT_ONLY_DAYNAME:
<<<<<<< HEAD
                $datetype = IntlDateFormatter::SHORT;
                $timetype = IntlDateFormatter::NONE;
                break;
            case DATE_FORMAT_NUMBER_NO_YEAR:
                $datetype = IntlDateFormatter::SHORT;
                $timetype = IntlDateFormatter::NONE;
                break;
            case DATE_FORMAT_NUMBER:
                $datetype = IntlDateFormatter::SHORT;
                $timetype = IntlDateFormatter::NONE;
                break;
            case TIME_NO_SEC_FORMAT:
                $datetype = IntlDateFormatter::NONE;
                $timetype = IntlDateFormatter::SHORT;
                break;
            case DATE_FORMAT_SHORT:
                $datetype = IntlDateFormatter::LONG;
                $timetype = IntlDateFormatter::NONE;
                break;
            case DATE_FORMAT_LONG:
                $datetype = IntlDateFormatter::FULL;
                $timetype = IntlDateFormatter::NONE;
                break;
            case DATE_TIME_FORMAT_LONG:
                $datetype = IntlDateFormatter::FULL;
                $timetype = IntlDateFormatter::SHORT;
                break;
            case DATE_FORMAT_LONG_NO_DAY:
                $datetype = IntlDateFormatter::FULL;
                $timetype = IntlDateFormatter::SHORT;
                break;
            case DATE_TIME_FORMAT_SHORT:
                $datetype = IntlDateFormatter::FULL;
                $timetype = IntlDateFormatter::SHORT;
                break;
            case DATE_TIME_FORMAT_SHORT_TIME_FIRST:
                $datetype = IntlDateFormatter::FULL;
                $timetype = IntlDateFormatter::SHORT;
                break;
            case DATE_TIME_FORMAT_LONG_24H:
                $datetype = IntlDateFormatter::FULL;
                $timetype = IntlDateFormatter::SHORT;
                break;
            default:
                $datetype = IntlDateFormatter::FULL;
                $timetype = IntlDateFormatter::SHORT;
=======
                $date_format = get_lang('dateFormatOnlyDayName', '', $language);
                if (INTL_INSTALLED) {
        			$datetype = IntlDateFormatter::SHORT;
        			$timetype = IntlDateFormatter::NONE;
        		}
                break;
            case DATE_FORMAT_NUMBER_NO_YEAR:
                $date_format = get_lang('dateFormatShortNumberNoYear', '', $language);
        		if (INTL_INSTALLED) {
        			$datetype = IntlDateFormatter::SHORT;
        			$timetype = IntlDateFormatter::NONE;
        		}
                break;
        	case DATE_FORMAT_NUMBER:
        		$date_format = get_lang('dateFormatShortNumber', '', $language);
        		if (INTL_INSTALLED) {
        			$datetype = IntlDateFormatter::SHORT;
        			$timetype = IntlDateFormatter::NONE;
        		}
        		break;
            case TIME_NO_SEC_FORMAT:
                $date_format = get_lang('timeNoSecFormat', '', $language);
                if (INTL_INSTALLED) {
                    $datetype = IntlDateFormatter::NONE;
                    $timetype = IntlDateFormatter::SHORT;
                }
                break;
            case DATE_FORMAT_SHORT:
                $date_format = get_lang('dateFormatShort', '', $language);
                if (INTL_INSTALLED) {
                    $datetype = IntlDateFormatter::LONG;
                    $timetype = IntlDateFormatter::NONE;
                }
                break;
            case DATE_FORMAT_LONG:
                $date_format = get_lang('dateFormatLong', '', $language);
                if (INTL_INSTALLED) {
                    $datetype = IntlDateFormatter::FULL;
                    $timetype = IntlDateFormatter::NONE;
                }
                break;
            case DATE_TIME_FORMAT_LONG:
                $date_format = get_lang('dateTimeFormatLong', '', $language);
                if (INTL_INSTALLED) {
                    $datetype = IntlDateFormatter::FULL;
                    $timetype = IntlDateFormatter::SHORT;
                }
                break;
            case DATE_FORMAT_LONG_NO_DAY:
                $date_format = get_lang('dateFormatLongNoDay', '', $language);
                if (INTL_INSTALLED) {
                    $datetype = IntlDateFormatter::FULL;
                    $timetype = IntlDateFormatter::SHORT;
                }
                break;
			case DATE_TIME_FORMAT_SHORT:
                $date_format = get_lang('dateTimeFormatShort', '', $language);
                if (INTL_INSTALLED) {
                    $datetype = IntlDateFormatter::FULL;
                    $timetype = IntlDateFormatter::SHORT;
                }
                break;
			case DATE_TIME_FORMAT_SHORT_TIME_FIRST:
                $date_format = get_lang('dateTimeFormatShortTimeFirst', '', $language);
                if (INTL_INSTALLED) {
                    $datetype = IntlDateFormatter::FULL;
                    $timetype = IntlDateFormatter::SHORT;
                }
                break;
            case DATE_TIME_FORMAT_LONG_24H:
                $date_format = get_lang('dateTimeFormatLong24H', '', $language);
                if (INTL_INSTALLED) {
                    $datetype = IntlDateFormatter::FULL;
                    $timetype = IntlDateFormatter::SHORT;
                }
                break;
            default:
                $date_format = get_lang('dateTimeFormatLong', '', $language);
                if (INTL_INSTALLED) {
                    $datetype = IntlDateFormatter::FULL;
                    $timetype = IntlDateFormatter::SHORT;
                }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        }
    }

<<<<<<< HEAD
    // Use ICU
    if (is_null($language)) {
        $language = api_get_language_isocode();
    }

    $date_formatter = new IntlDateFormatter(
        $language,
        $datetype,
        $timetype,
        date_default_timezone_get()
    );

    $formatted_date = api_to_system_encoding(
        $date_formatter->format($time),
        'UTF-8'
    );
=======
    if (0) {
        //if using PHP 5.3 format dates like: $dateFormatShortNumber, can't be used
        //
        // Use ICU
        if (is_null($language)) {
            $language = api_get_language_isocode();
        }
        $date_formatter = new IntlDateFormatter($language, $datetype, $timetype, date_default_timezone_get());
        //$date_formatter->setPattern($date_format);
        $formatted_date = api_to_system_encoding($date_formatter->format($time), 'UTF-8');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    return $formatted_date;
}

/**
 * Returns the difference between the current date (date(now)) with the parameter $date in a string format like "2 days, 1 hour"
 * Example: $date = '2008-03-07 15:44:08';
 *             date_to_str($date) it will return 3 days, 20 hours
 * The given date should be in the timezone chosen by the user or administrator. Use api_get_local_time() to get it...
 *
 * @param  string The string has to be the result of a date function in this format -> date('Y-m-d H:i:s', time());
 * @return string The difference between the current date and the parameter in a literal way "3 days, 2 hour" *
 * @author Julio Montoya
 */

function date_to_str_ago($date)
{
    static $initialized = false;
    static $today, $yesterday;
    static $min_decade, $min_year, $min_month, $min_week, $min_day, $min_hour, $min_minute;
    static $min_decades, $min_years, $min_months, $min_weeks, $min_days, $min_hours, $min_minutes;
    static $sec_time_time, $sec_time_sing, $sec_time_plu;

    $system_timezone = date_default_timezone_get();
    date_default_timezone_set(_api_get_timezone());

    if (!$initialized) {
        $today = get_lang('Today');
        $yesterday = get_lang('Yesterday');

        $min_decade = get_lang('MinDecade');
        $min_year = get_lang('MinYear');
        $min_month = get_lang('MinMonth');
        $min_week = get_lang('MinWeek');
        $min_day = get_lang('MinDay');
        $min_hour = get_lang('MinHour');
        $min_minute = get_lang('MinMinute');

        $min_decades = get_lang('MinDecades');
        $min_years = get_lang('MinYears');
        $min_months = get_lang('MinMonths');
        $min_weeks = get_lang('MinWeeks');
        $min_days = get_lang('MinDays');
        $min_hours = get_lang('MinHours');
        $min_minutes = get_lang('MinMinutes');

        $sec_time_time = array(315569260, 31556926, 2629743.83, 604800, 86400, 3600, 60);
        $sec_time_sing = array($min_decade, $min_year, $min_month, $min_week, $min_day, $min_hour, $min_minute);
        $sec_time_plu = array($min_decades, $min_years, $min_months, $min_weeks, $min_days, $min_hours, $min_minutes);
        $initialized = true;
    }

    $dst_date = is_string($date) ? strtotime($date) : $date;
    // For avoiding calling date() several times
    $date_array = date('s/i/G/j/n/Y', $dst_date);
    $date_split = explode('/', $date_array);

    $dst_s = $date_split[0];
    $dst_m = $date_split[1];
    $dst_h = $date_split[2];
    $dst_day = $date_split[3];
    $dst_mth = $date_split[4];
    $dst_yr = $date_split[5];

    $dst_date = mktime($dst_h, $dst_m, $dst_s, $dst_mth, $dst_day, $dst_yr);
    $time = $offset = time() - $dst_date; // Seconds between current days and today.

    // Here start the functions sec_to_str()
    $act_day = date('d');
    $act_mth = date('n');
    $act_yr = date('Y');

    if ($dst_day == $act_day && $dst_mth == $act_mth && $dst_yr == $act_yr) {
        return $today;
    }

    if ($dst_day == $act_day - 1 && $dst_mth == $act_mth && $dst_yr == $act_yr) {
        return $yesterday;
    }

    $str_result = array();
    $time_result = array();
    $key_result = array();

    $str = '';
    $i = 0;
    for ($i = 0; $i < count($sec_time_time); $i++) {
        $seconds = $sec_time_time[$i];
        if ($seconds > $time) {
            continue;
        }
        $current_value = intval($time / $seconds);

        if ($current_value != 1) {
            $date_str = $sec_time_plu[$i];
        } else {
            $date_str = $sec_time_sing[$i];

        }
        $key_result[] = $sec_time_sing[$i];

        $str_result[] = $current_value.' '.$date_str;
        $time_result[] = $current_value;
        $str .= $current_value.$date_str;
        $time %= $seconds;
    }

    if ($key_result[0] == $min_day && $key_result[1] == $min_minute) {
        $key_result[1] = ' 0 '.$min_hours;
        $str_result[0] = $time_result[0].' '.$key_result[0];
        $str_result[1] = $key_result[1];
    }

    if ($key_result[0] == $min_year && ($key_result[1] == $min_day || $key_result[1] == $min_week)) {
        $key_result[1] = ' 0 '.$min_months;
        $str_result[0] = $time_result[0].' '.$key_result[0];
        $str_result[1] = $key_result[1];
    }

    if (!empty($str_result[1])) {
        $str = $str_result[0].', '.$str_result[1];
    } else {
        $str = $str_result[0];
    }

    date_default_timezone_set($system_timezone);
    return $str;
}

/**
 * Converts a date to the right timezone and localizes it in the format given as an argument
 * @param mixed The time to be converted
 * @param mixed Format to be used (TIME_NO_SEC_FORMAT, DATE_FORMAT_SHORT, DATE_FORMAT_LONG, DATE_TIME_FORMAT_LONG)
 * @param string Timezone to be converted from. If null, UTC will be assumed.
 * @return string Converted and localized date
 *
 * @author Guillaume Viguier <guillaume.viguier@beeznest.com>
 */
function api_convert_and_format_date($time = null, $format = null, $from_timezone = null)
{
    // First, convert the datetime to the right timezone
    $time = api_get_local_time($time, null, $from_timezone);
    // Second, localize the date
    return api_format_date($time, $format);
}

/**
 * Returns an array of translated week days in short names.
 * @param string $language (optional)    Language indentificator. If it is omited, the current interface language is assumed.
 * @return string                        Returns an array of week days (short names).
 * Example: api_get_week_days_short('english') means array('Sun', 'Mon', ... 'Sat').
 * Note: For all languages returned days are in the English order.
 */
function api_get_week_days_short($language = null)
{
    $days = & _api_get_day_month_names($language);
    return $days['days_short'];
}

/**
 * Returns an array of translated week days.
 * @param string $language (optional)    Language indentificator. If it is omited, the current interface language is assumed.
 * @return string                        Returns an array of week days.
 * Example: api_get_week_days_long('english') means array('Sunday, 'Monday', ... 'Saturday').
 * Note: For all languages returned days are in the English order.
 */
function api_get_week_days_long($language = null)
{
    $days = & _api_get_day_month_names($language);
    return $days['days_long'];
}

/**
 * Returns an array of translated months in short names.
 * @param string $language (optional)    Language indentificator. If it is omited, the current interface language is assumed.
 * @return string                        Returns an array of months (short names).
 * Example: api_get_months_short('english') means array('Jan', 'Feb', ... 'Dec').
 */
function api_get_months_short($language = null)
{
    $months = & _api_get_day_month_names($language);
    return $months['months_short'];
}

/**
 * Returns an array of translated months.
 * @param string $language (optional)    Language indentificator. If it is omited, the current interface language is assumed.
 * @return string                        Returns an array of months.
 * Example: api_get_months_long('english') means array('January, 'February' ... 'December').
 */
function api_get_months_long($language = null)
{
    $months = & _api_get_day_month_names($language);
    return $months['months_long'];
}

/**
 * Name order conventions
 */

/**
 * Builds a person (full) name depending on the convention for a given language.
 * @param string $first_name            The first name of the preson.
 * @param string $last_name                The last name of the person.
 * @param string $title                    The title of the person.
 * @param int/string $format (optional)    The person name format. It may be a pattern-string (for example '%t %l, %f' or '%T %F %L', ...) or some of the constants PERSON_NAME_COMMON_CONVENTION (default), PERSON_NAME_WESTERN_ORDER, PERSON_NAME_EASTERN_ORDER, PERSON_NAME_LIBRARY_ORDER.
 * @param string $language (optional)    The language identificator. if it is omitted, the current interface language is assumed. This parameter has meaning with the format PERSON_NAME_COMMON_CONVENTION only.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return bool                            The result is sort of full name of the person.
 * Sample results:
 * Peter Ustinoff or Dr. Peter Ustinoff     - the Western order
 * Ustinoff Peter or Dr. Ustinoff Peter     - the Eastern order
 * Ustinoff, Peter or - Dr. Ustinoff, Peter - the library order
 * Note: See the file chamilo/main/inc/lib/internationalization_database/name_order_conventions.php where you can revise the convention for your language.
 * @author Carlos Vargas <carlos.vargas@dokeos.com> - initial implementation.
 * @author Ivan Tcholakov
 */
<<<<<<< HEAD
function api_get_person_name($first_name, $last_name, $title = null, $format = null, $language = null, $encoding = null)
{
=======
function api_get_person_name($first_name, $last_name, $title = null, $format = null, $language = null, $encoding = null, $username = null) {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    static $valid = array();
    if (empty($format)) {
        $format = PERSON_NAME_COMMON_CONVENTION;
    }

    if (empty($language)) {
        $language = api_get_interface_language(false, true);
    }

    if (empty($encoding)) {
        $encoding = mb_internal_encoding();
    }

    if (!isset($valid[$format][$language])) {
        if (is_int($format)) {
            switch ($format) {
                case PERSON_NAME_COMMON_CONVENTION:
                    $valid[$format][$language] = _api_get_person_name_convention($language, 'format');
                    $usernameOrderFromDatabase = api_get_setting('user_name_order');
                    if (isset($usernameOrderFromDatabase) && !empty($usernameOrderFromDatabase)) {
                        $valid[$format][$language] = $usernameOrderFromDatabase;
                    }
                    break;
                case PERSON_NAME_WESTERN_ORDER:
                    $valid[$format][$language] = '%t %f %l';
                    break;
                case PERSON_NAME_EASTERN_ORDER:
                    $valid[$format][$language] = '%t %l %f';
                    break;
                case PERSON_NAME_LIBRARY_ORDER:
                    $valid[$format][$language] = '%t %l, %f';
                    break;
                default:
                    $valid[$format][$language] = '%t %f %l';
                    break;
            }
        } else {
            $valid[$format][$language] = _api_validate_person_name_format($format);
        }
    }

    $format = $valid[$format][$language];
<<<<<<< HEAD
    $person_name = str_replace(array('%f', '%l', '%t'), array($first_name, $last_name, $title), $format);
    if (strpos($format, '%F') !== false || strpos($format, '%L') !== false || strpos($format, '%T') !== false) {
        $person_name = str_replace(
            array(
                '%F',
                '%L',
                '%T'
            ),
            array(
                api_strtoupper($first_name, $encoding),
                api_strtoupper($last_name, $encoding),
                api_strtoupper($title, $encoding)
            ),
            $person_name
        );
    }
=======

    $keywords = array('%firstname', '%f', '%F', '%lastname', '%l', '%L', '%title', '%t', '%T', '%username', '%u', '%U');

    $values = array(
        $first_name,
        $first_name,
        api_strtoupper($first_name, $encoding),
        $last_name,
        $last_name,
        api_strtoupper($last_name, $encoding),
        $title,
        $title,
        api_strtoupper($title, $encoding),
        $username,
        $username,
        api_strtoupper($username, $encoding),
    );
    $person_name = str_replace($keywords, $values, $format);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    return _api_clean_person_name($person_name);
}

/**
 * Checks whether a given format represents person name in Western order (for which first name is first).
 * @param int/string $format (optional)    The person name format. It may be a pattern-string (for example '%t. %l, %f') or some of the constants PERSON_NAME_COMMON_CONVENTION (default), PERSON_NAME_WESTERN_ORDER, PERSON_NAME_EASTERN_ORDER, PERSON_NAME_LIBRARY_ORDER.
 * @param string $language (optional)    The language indentificator. If it is omited, the current interface language is assumed. This parameter has meaning with the format PERSON_NAME_COMMON_CONVENTION only.
 * @return bool                            The result TRUE means that the order is first_name last_name, FALSE means last_name first_name.
 * Note: You may use this function for determing the order of the fields or columns "First name" and "Last name" in forms, tables and reports.
 * @author Ivan Tcholakov
 */
function api_is_western_name_order($format = null, $language = null)
{
    static $order = array();
    if (empty($format)) {
        $format = PERSON_NAME_COMMON_CONVENTION;
    }

<<<<<<< HEAD
    if (empty($language)) {
=======
    $language_is_supported = api_is_language_supported($language);

    if (!$language_is_supported || empty($language)) {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        $language = api_get_interface_language(false, true);
    }
    if (!isset($order[$format][$language])) {
        $test_name = api_get_person_name('%f', '%l', '%t', $format, $language);
        $order[$format][$language] = stripos($test_name, '%f') <= stripos($test_name, '%l');
    }
    return $order[$format][$language];
}

/**
 * Returns a directive for sorting person names depending on a given language and based on the options in the internationalization "database".
 * @param string $language (optional) The input language. If it is omited, the current interface language is assumed.
 * @return bool Returns boolean value. TRUE means ORDER BY first_name, last_name; FALSE means ORDER BY last_name, first_name.
 * Note: You may use this function:
 * 2. for constructing the ORDER clause of SQL queries, related to first_name and last_name;
 * 3. for adjusting php-implemented sorting in tables and reports.
 * @author Ivan Tcholakov
 */
<<<<<<< HEAD
function api_sort_by_first_name($language = null)
{
=======
function api_sort_by_first_name($language = null) {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    $userNameSortBy = api_get_setting('user_name_sort_by');
    if (!empty($userNameSortBy) && in_array($userNameSortBy, array('firstname', 'lastname'))) {
        return $userNameSortBy == 'firstname' ? true : false;
    }
<<<<<<< HEAD
=======

    static $sort_by_first_name = array();
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    static $sort_by_first_name = array();
    if (empty($language)) {
        $language = api_get_interface_language(false, true);
    }
    if (!isset($sort_by_first_name[$language])) {
        $sort_by_first_name[$language] = _api_get_person_name_convention($language, 'sort_by');
    }

    return $sort_by_first_name[$language];
}

<<<<<<< HEAD
=======
/**
 * A safe way to calculate binary lenght of a string (as number of bytes)
 */

/**
 * Calculates binary lenght of a string, as number of bytes, regardless the php-setting mbstring.func_overload.
 * This function should work for all multi-byte related changes of PHP5 configuration.
 * @param string $string	The input string.
 * @return int				Returns the length of the input string (or binary data) as number of bytes.
 */
function api_byte_count(& $string) {
    static $use_mb_strlen;
    if (!isset($use_mb_strlen)) {
        $use_mb_strlen = MBSTRING_INSTALLED && ((int) ini_get('mbstring.func_overload') & 2);
    }
    if ($use_mb_strlen) {
        return mb_strlen($string, '8bit');
    }
    return strlen($string);
}

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
/**
 * Multibyte string conversion functions
 */

/**
 * Converts character encoding of a given string.
 * @param string $string                    The string being converted.
 * @param string $to_encoding                The encoding that $string is being converted to.
 * @param string $from_encoding (optional)    The encoding that $string is being converted from. If it is omited, the platform character set is assumed.
 * @return string                            Returns the converted string.
 * This function is aimed at replacing the function mb_convert_encoding() for human-language strings.
 * @link http://php.net/manual/en/function.mb-convert-encoding
 */
function api_convert_encoding($string, $to_encoding, $from_encoding = null)
{
    return mb_convert_encoding($string, $to_encoding, $from_encoding);
}

/**
 * Converts a given string into UTF-8 encoded string.
 * @param string $string                    The string being converted.
 * @param string $from_encoding (optional)    The encoding that $string is being converted from. If it is omited, the platform character set is assumed.
 * @return string                            Returns the converted string.
 * This function is aimed at replacing the function utf8_encode() for human-language strings.
 * @link http://php.net/manual/en/function.utf8-encode
 */
function api_utf8_encode($string, $from_encoding = null)
{
    return u::utf8_encode($string);
}

/**
 * Converts a given string from UTF-8 encoding to a specified encoding.
 * @param string $string                    The string being converted.
 * @param string $to_encoding (optional)    The encoding that $string is being converted to. If it is omited, the platform character set is assumed.
 * @return string                            Returns the converted string.
 * This function is aimed at replacing the function utf8_decode() for human-language strings.
 * @link http://php.net/manual/en/function.utf8-decode
 */
function api_utf8_decode($string, $to_encoding = null)
{
    return u::utf8_decode($string);
}

/**
 * Converts a given string into the system ecoding (or platform character set).
 * When $from encoding is omited on UTF-8 platforms then language dependent encoding
 * is guessed/assumed. On non-UTF-8 platforms omited $from encoding is assumed as UTF-8.
 * When the parameter $check_utf8_validity is true the function checks string's
 * UTF-8 validity and decides whether to try to convert it or not.
 * This function is useful for problem detection or making workarounds.
 * @param string $string                        The string being converted.
 * @param string $from_encoding (optional)        The encoding that $string is being converted from. It is guessed when it is omited.
 * @param bool $check_utf8_validity (optional)    A flag for UTF-8 validity check as condition for making conversion.
 * @return string                                Returns the converted string.
 */
function api_to_system_encoding($string, $from_encoding = null, $check_utf8_validity = false)
{
    $system_encoding = api_get_system_encoding();
<<<<<<< HEAD
=======
    if (empty($from_encoding)) {
        if (api_is_utf8($system_encoding)) {
            $from_encoding = api_get_non_utf8_encoding();
        } else {
            $from_encoding = 'UTF-8';
        }
    }
    if (api_equal_encodings($system_encoding, $from_encoding)) {
        return $string;
    }
    if ($check_utf8_validity) {
        if (api_is_utf8($system_encoding)) {
            if (api_is_valid_utf8($string)) {
                return $string;
            }
        } elseif (api_is_utf8($from_encoding)) {
            if (!api_is_valid_utf8($string)) {
                return $string;
            }
        }
    }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    return api_convert_encoding($string, $system_encoding, $from_encoding);
}

/**
 * Converts all applicable characters to HTML entities.
 * @param string $string                The input string.
 * @param int $quote_style (optional)    The quote style - ENT_COMPAT (default), ENT_QUOTES, ENT_NOQUOTES.
 * @param string $encoding (optional)    The encoding (of the input string) used in conversion. If it is omited, the platform character set is assumed.
 * @return string                        Returns the converted string.
 * This function is aimed at replacing the function htmlentities() for human-language strings.
 * @link http://php.net/manual/en/function.htmlentities
 */
function api_htmlentities($string, $quote_style = ENT_COMPAT, $encoding = null)
{
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
<<<<<<< HEAD
=======
    if (!api_is_utf8($encoding) && _api_html_entity_supports($encoding)) {
        return htmlentities($string, $quote_style, $encoding);
    }
    switch($quote_style) {
        case ENT_COMPAT:
            $string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
            break;
        case ENT_QUOTES:
            $string = str_replace(array('&', '\'', '"', '<', '>'), array('&amp;', '&#039;', '&quot;', '&lt;', '&gt;'), $string);
            break;
    }
    if (_api_mb_supports($encoding)) {
        if (!api_is_utf8($encoding)) {
            $string = api_utf8_encode($string, $encoding);
        }
        $string = @mb_convert_encoding(api_utf8_encode($string, $encoding), 'HTML-ENTITIES', 'UTF-8');
        if (!api_is_utf8($encoding)) { // Just in case.
            $string = api_utf8_decode($string, $encoding);
        }
    } elseif (_api_convert_encoding_supports($encoding)) {
        if (!api_is_utf8($encoding)) {
            $string = _api_convert_encoding($string, 'UTF-8', $encoding);
        }
        $string = implode(array_map('_api_html_entity_from_unicode', _api_utf8_to_unicode($string)));
        if (!api_is_utf8($encoding)) { // Just in case.
            $string = _api_convert_encoding($string, $encoding, 'UTF-8');
        }
    }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    return htmlentities($string, $quote_style, $encoding);
}


/**
<<<<<<< HEAD
 * Checks whether the specified encoding is supported by the html-entitiy related functions.
 * @param string $encoding	The specified encoding.
 * @return bool				Returns TRUE when the specified encoding is supported, FALSE othewise.
 */
function _api_html_entity_supports($encoding) {
    static $supports = array();
    if (!isset($supports[$encoding])) {
        // See http://php.net/manual/en/function.htmlentities.php
        $html_entity_encodings = array(
            'ISO-8859-1',
            'ISO-8859-15',
            'UTF-8',
            'CP866',
            'CP1251',
            'CP1252',
            'KOI8-R',
            'BIG5', '950',
            'GB2312', '936',
            'BIG5-HKSCS',
            'Shift_JIS', 'SJIS', '932',
            'EUC-JP', 'EUCJP'
        );
        $supports[$encoding] = api_equal_encodings($encoding, $html_entity_encodings);
    }
    return $supports[$encoding];
}

/**
 * Converts HTML entities into normal characters.
 * @param string $string                The input string.
 * @param int $quote_style (optional)    The quote style - ENT_COMPAT (default), ENT_QUOTES, ENT_NOQUOTES.
 * @param string $encoding (optional)    The encoding (of the result) used in conversion.
 * If it is omitted, the platform character set is assumed.
 * @return string                        Returns the converted string.
=======
 * Converts HTML entities into normal characters.
 * @param string $string				The input string.
 * @param int $quote_style (optional)	The quote style - ENT_COMPAT (default), ENT_QUOTES, ENT_NOQUOTES.
 * @param string $encoding (optional)	The encoding (of the result) used in conversion. If it is omited, the platform character set is assumed.
 * @return string						Returns the converted string.
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
 * This function is aimed at replacing the function html_entity_decode() for human-language strings.
 * @link http://php.net/html_entity_decode
 */
function api_html_entity_decode($string, $quote_style = ENT_COMPAT, $encoding = null)
{
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
<<<<<<< HEAD
    return html_entity_decode($string, $quote_style, $encoding);
=======
    if (_api_html_entity_supports($encoding)) {
        return html_entity_decode($string, $quote_style, $encoding);
    }
    if (api_is_encoding_supported($encoding)) {
        if (!api_is_utf8($encoding)) {
            $string = api_utf8_encode($string, $encoding);
        }
        $string = html_entity_decode($string, $quote_style, 'UTF-8');
        if (!api_is_utf8($encoding)) {
            return api_utf8_decode($string, $encoding);
        }
        return $string;
    }
    return $string; // Here the function gives up.
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
}

/**
 * This function encodes (conditionally) a given string to UTF-8 if XmlHttp-request has been detected.
 * @param string $string                    The string being converted.
 * @param string $from_encoding (optional)    The encoding that $string is being converted from. If it is omited, the platform character set is assumed.
 * @return string                            Returns the converted string.
 */
function api_xml_http_response_encode($string, $from_encoding = null)
{
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        if (empty($from_encoding)) {
            $from_encoding = _api_mb_internal_encoding();
        }
        if (!api_is_utf8($from_encoding)) {
            return api_utf8_encode($string, $from_encoding);
        }
    }
    return $string;
}

function _api_mb_internal_encoding()
{
    return mb_internal_encoding();
}

/**
 * Transliterates a string with arbitrary encoding into a plain ASCII string.
 *
 * Example:
 * echo api_transliterate(api_html_entity_decode(
 *     '&#1060;&#1105;&#1076;&#1086;&#1088; '.
 *     '&#1052;&#1080;&#1093;&#1072;&#1081;&#1083;&#1086;&#1074;&#1080;&#1095; '.
 *     '&#1044;&#1086;&#1089;&#1090;&#1086;&#1077;&#1074;&#1082;&#1080;&#1081;',
 *     ENT_QUOTES, 'UTF-8'), 'X', 'UTF-8');
 * The output should be: Fyodor Mihaylovich Dostoevkiy
 *
<<<<<<< HEAD
 * @param string $string                    The input string.
 * @param string $unknown (optional)        Replacement character for unknown characters and illegal UTF-8 sequences.
 * @param string $from_encoding (optional)    The encoding of the input string. If it is omited, the platform character set is assumed.
 * @return string                            Plain ASCII output.

 */
function api_transliterate($string, $unknown = '?', $from_encoding = null)
{
    return URLify::transliterate($string);
    //return u::toAscii($string, $unknown);
=======
 * @param string $string					The input string.
 * @param string $unknown (optional)		Replacement character for unknown characters and illegal UTF-8 sequences.
 * @param string $from_encoding (optional)	The encoding of the input string. If it is omited, the platform character set is assumed.
 * @return string							Plain ASCII output.
 *
 * Based on Drupal's module "Transliteration", version 6.x-2.1, 09-JUN-2009:
 * @author Stefan M. Kudwien (smk-ka)
 * @author Daniel F. Kudwien (sun)
 * @link http://drupal.org/project/transliteration
 *
 * See also MediaWiki's UtfNormal.php and CPAN's Text::Unidecode library
 * @link http://www.mediawiki.org
 * @link http://search.cpan.org/~sburke/Text-Unidecode-0.04/lib/Text/Unidecode.pm).
 *
 * Adaptation for Chamilo 1.8.7, 2010
 * Initial implementation for Dokeos 1.8.6.1, 12-JUN-2009
 * @author Ivan Tcholakov
 */
function api_transliterate($string, $unknown = '?', $from_encoding = null) {
    static $map = array();
    $string = api_utf8_encode($string, $from_encoding);
    // Screen out some characters that eg won't be allowed in XML.
    $string = preg_replace('/[\x00-\x08\x0b\x0c\x0e-\x1f]/', $unknown, $string);
    // ASCII is always valid NFC!
    // If we're only ever given plain ASCII, we can avoid the overhead
    // of initializing the decomposition tables by skipping out early.
    if (api_is_valid_ascii($string)) {
        return $string;
    }
    static $tail_bytes;
    if (!isset($tail_bytes)) {
        // Each UTF-8 head byte is followed by a certain
        // number of tail bytes.
        $tail_bytes = array();
        for ($n = 0; $n < 256; $n++) {
            if ($n < 0xc0) {
                $remaining = 0;
            }
            elseif ($n < 0xe0) {
                $remaining = 1;
            }
            elseif ($n < 0xf0) {
                $remaining = 2;
            }
            elseif ($n < 0xf8) {
                $remaining = 3;
            }
            elseif ($n < 0xfc) {
                $remaining = 4;
            }
            elseif ($n < 0xfe) {
                $remaining = 5;
            } else {
                $remaining = 0;
            }
            $tail_bytes[chr($n)] = $remaining;
        }
    }

    // Chop the text into pure-ASCII and non-ASCII areas;
    // large ASCII parts can be handled much more quickly.
    // Don't chop up Unicode areas for punctuation, though,
    // that wastes energy.
    preg_match_all('/[\x00-\x7f]+|[\x80-\xff][\x00-\x40\x5b-\x5f\x7b-\xff]*/', $string, $matches);
    $result = '';
    foreach ($matches[0] as $str) {
        if ($str{0} < "\x80") {
            // ASCII chunk: guaranteed to be valid UTF-8
            // and in normal form C, so skip over it.
            $result .= $str;
            continue;
        }
        // We'll have to examine the chunk byte by byte to ensure
        // that it consists of valid UTF-8 sequences, and to see
        // if any of them might not be normalized.
        //
        // Since PHP is not the fastest language on earth, some of
        // this code is a little ugly with inner loop optimizations.
        $head = '';
        $chunk = api_byte_count($str);
        // Counting down is faster. I'm *so* sorry.
        $len = $chunk + 1;
        for ($i = -1; --$len; ) {
            $c = $str{++$i};
            if ($remaining = $tail_bytes[$c]) {
                // UTF-8 head byte!
                $sequence = $head = $c;
                do {
                    // Look for the defined number of tail bytes...
                    if (--$len && ($c = $str{++$i}) >= "\x80" && $c < "\xc0") {
                    // Legal tail bytes are nice.
                    $sequence .= $c;
                    } else {
                        if ($len == 0) {
                            // Premature end of string!
                            // Drop a replacement character into output to
                            // represent the invalid UTF-8 sequence.
                            $result .= $unknown;
                            break 2;
                        } else {
                            // Illegal tail byte; abandon the sequence.
                            $result .= $unknown;
                            // Back up and reprocess this byte; it may itself
                            // be a legal ASCII or UTF-8 sequence head.
                            --$i;
                            ++$len;
                            continue 2;
                        }
                    }
                } while (--$remaining);
                $n = ord($head);
                if ($n <= 0xdf) {
                    $ord = ($n - 192) * 64 + (ord($sequence{1}) - 128);
                }
                else if ($n <= 0xef) {
                    $ord = ($n - 224) * 4096 + (ord($sequence{1}) - 128) * 64 + (ord($sequence{2}) - 128);
                }
                else if ($n <= 0xf7) {
                    $ord = ($n - 240) * 262144 + (ord($sequence{1}) - 128) * 4096 + (ord($sequence{2}) - 128) * 64 + (ord($sequence{3}) - 128);
                }
                else if ($n <= 0xfb) {
                    $ord = ($n - 248) * 16777216 + (ord($sequence{1}) - 128) * 262144 + (ord($sequence{2}) - 128) * 4096 + (ord($sequence{3}) - 128) * 64 + (ord($sequence{4}) - 128);
                }
                else if ($n <= 0xfd) {
                    $ord = ($n - 252) * 1073741824 + (ord($sequence{1}) - 128) * 16777216 + (ord($sequence{2}) - 128) * 262144 + (ord($sequence{3}) - 128) * 4096 + (ord($sequence{4}) - 128) * 64 + (ord($sequence{5}) - 128);
                }
                // Lookup and replace a character from the transliteration database.
                $bank = $ord >> 8;
                // Check if we need to load a new bank
                if (!isset($map[$bank])) {
                    $file = dirname(__FILE__).'/internationalization_database/transliteration/' . sprintf('x%02x', $bank) . '.php';
                    if (file_exists($file)) {
                        $map[$bank] = include ($file);
                    } else {
                        $map[$bank] = array('en' => array());
                    }
                }
                $ord = $ord & 255;
                $result .= isset($map[$bank]['en'][$ord]) ? $map[$bank]['en'][$ord] : $unknown;
                $head = '';
            } elseif ($c < "\x80") {
                // ASCII byte.
                $result .= $c;
                $head = '';
            } elseif ($c < "\xc0") {
                // Illegal tail bytes.
                if ($head == '') {
                    $result .= $unknown;
                }
            } else {
                // Miscellaneous freaks.
                $result .= $unknown;
                $head = '';
            }
        }
    }
    return $result;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
}

/**
 * @see str_ireplace
 */
function api_str_ireplace($search, $replace, $subject, & $count = null, $encoding = null)
{
    return str_ireplace($search, $replace, $subject, $count);
}

/**
 * @see str_split
 */
function api_str_split($string, $split_length = 1, $encoding = null)
{
    return str_split($string, $split_length);
}

/**
 * @see stripos
 */
function api_stripos($haystack, $needle, $offset = 0, $encoding = null)
{
    return stripos($haystack, $needle, $offset);
}

/**
 * @see stristr
 */
function api_stristr($haystack, $needle, $before_needle = false, $encoding = null)
{
    return stristr($haystack, $needle, $before_needle);
}

/**
<<<<<<< HEAD
 * @see mb_strlen
 */
function api_strlen($string, $encoding = null)
{
    return mb_strlen($string);
=======
 * Converts a string to an array.
 * @param string $string				The input string.
 * @param int $split_length				Maximum character-length of the chunk, one character by default.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return array						The result array of chunks with the spcified length.
 * Notes:
 * If the optional split_length parameter is specified, the returned array will be broken down into chunks
 * with each being split_length in length, otherwise each chunk will be one character in length.
 * FALSE is returned if split_length is less than 1.
 * If the split_length length exceeds the length of string, the entire string is returned as the first (and only) array element.
 * This function is aimed at replacing the function str_split() for human-language strings.
 * @link http://php.net/str_split
 */
function api_str_split($string, $split_length = 1, $encoding = null) {
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    if (empty($string)) {
        return array();
    }
    if ($split_length < 1) {
        return false;
    }
    if (_api_is_single_byte_encoding($encoding)) {
        return str_split($string, $split_length);
    }
    if (api_is_encoding_supported($encoding)) {
        $len = api_strlen($string);
        if ($len <= $split_length) {
            return array($string);
        }
        if (!api_is_utf8($encoding)) {
            $string = api_utf8_encode($string, $encoding);
        }
        if (preg_match_all('/.{'.$split_length.'}|[^\x00]{1,'.$split_length.'}$/us', $string, $result) === false) {
            return array();
        }
        if (!api_is_utf8($encoding)) {
            global $_api_encoding;
            $_api_encoding = $encoding;
            $result = _api_array_utf8_decode($result[0]);
        }
        return $result[0];
    }
    return str_split($string, $split_length);
}

/**
 * Finds position of first occurrence of a string within another, case insensitive.
 * @param string $haystack				The string from which to get the position of the first occurrence.
 * @param string $needle				The string to be found.
 * @param int $offset					The position in $haystack to start searching from. If it is omitted, searching starts from the beginning.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return mixed						Returns the numeric position of the first occurrence of $needle in the $haystack, or FALSE if $needle is not found.
 * Note: The first character's position is 0, the second character position is 1, and so on.
 * This function is aimed at replacing the functions stripos() and mb_stripos() for human-language strings.
 * @link http://php.net/manual/en/function.stripos
 * @link http://php.net/manual/en/function.mb-stripos
 */
function api_stripos($haystack, $needle, $offset = 0, $encoding = null) {
    if (_api_mb_supports($encoding)) {
        if (empty($encoding)) {
            $encoding = _api_mb_internal_encoding();
        }
        return mb_stripos($haystack, $needle, $offset, $encoding);
    }
    return stripos($haystack, $needle, $offset);
}

/**
 * Finds first occurrence of a string within another, case insensitive.
 * @param string $haystack					The string from which to get the first occurrence.
 * @param mixed $needle						The string to be found.
 * @param bool $before_needle (optional)	Determines which portion of $haystack this function returns. The default value is FALSE.
 * @param string $encoding (optional)		The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return mixed							Returns the portion of $haystack, or FALSE if $needle is not found.
 * Notes:
 * If $needle is not a string, it is converted to an integer and applied as the ordinal value (codepoint if the encoding is UTF-8) of a character.
 * If $before_needle is set to TRUE, the function returns all of $haystack from the beginning to the first occurrence of $needle.
 * If $before_needle is set to FALSE, the function returns all of $haystack from the first occurrence of $needle to the end.
 * This function is aimed at replacing the functions stristr() and mb_stristr() for human-language strings.
 * @link http://php.net/manual/en/function.stristr
 * @link http://php.net/manual/en/function.mb-stristr
 */
function api_stristr($haystack, $needle, $before_needle = false, $encoding = null) {
    if (_api_mb_supports($encoding)) {
        if (empty($encoding)) {
            $encoding = _api_mb_internal_encoding();
        }
        return mb_stristr($haystack, $needle, $before_needle, $encoding);
    }
    return stristr($haystack, $needle, $before_needle);
}

/**
 * Returns length of the input string.
 * @param string $string				The string which length is to be calculated.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return int							Returns the number of characters within the string. A multi-byte character is counted as 1.
 * This function is aimed at replacing the functions strlen() and mb_strlen() for human-language strings.
 * @link http://php.net/manual/en/function.strlen
 * @link http://php.net/manual/en/function.mb-strlen
 * Note: When you use strlen() to test for an empty string, you needn't change it to api_strlen().
 * For example, in lines like the following:
 * if (strlen($string) > 0)
 * if (strlen($string) != 0)
 * there is no need the original function strlen() to be changed, it works correctly and faster for these cases.
 */
function api_strlen($string, $encoding = null) {
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    if (_api_is_single_byte_encoding($encoding)) {
        return strlen($string);
    }
    if (_api_mb_supports($encoding)) {
        return @mb_strlen($string, $encoding);
    }
    if (_api_iconv_supports($encoding)) {
        return @iconv_strlen($string, $encoding);
    }
    return strlen($string);
}

/**
 * Finds position of first occurrence of a string within another.
 * @param string $haystack				The string from which to get the position of the first occurrence.
 * @param string $needle				The string to be found.
 * @param int $offset (optional)		The position in $haystack to start searching from. If it is omitted, searching starts from the beginning.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return mixed						Returns the numeric position of the first occurrence of $needle in the $haystack, or FALSE if $needle is not found.
 * Note: The first character's position is 0, the second character position is 1, and so on.
 * This function is aimed at replacing the functions strpos() and mb_strpos() for human-language strings.
 * @link http://php.net/manual/en/function.strpos
 * @link http://php.net/manual/en/function.mb-strpos
 */
function api_strpos($haystack, $needle, $offset = 0, $encoding = null) {
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    if (_api_is_single_byte_encoding($encoding)) {
        return strpos($haystack, $needle, $offset);
    } elseif (_api_mb_supports($encoding)) {
        return mb_strpos($haystack, $needle, $offset, $encoding);
    }
    return strpos($haystack, $needle, $offset);
}

/**
 * Finds the last occurrence of a character in a string.
 * @param string $haystack					The string from which to get the last occurrence.
 * @param mixed $needle						The string which first character is to be found.
 * @param bool $before_needle (optional)	Determines which portion of $haystack this function returns. The default value is FALSE.
 * @param string $encoding (optional)		The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return mixed							Returns the portion of $haystack, or FALSE if the first character from $needle is not found.
 * Notes:
 * If $needle is not a string, it is converted to an integer and applied as the ordinal value (codepoint if the encoding is UTF-8) of a character.
 * If $before_needle is set to TRUE, the function returns all of $haystack from the beginning to the first occurrence.
 * If $before_needle is set to FALSE, the function returns all of $haystack from the first occurrence to the end.
 * This function is aimed at replacing the functions strrchr() and mb_strrchr() for human-language strings.
 * @link http://php.net/manual/en/function.strrchr
 * @link http://php.net/manual/en/function.mb-strrchr
 */
function api_strrchr($haystack, $needle, $before_needle = false, $encoding = null) {
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    if (_api_is_single_byte_encoding($encoding)) {
        if (!$before_needle) {
            return strrchr($haystack, $needle);
        }
        $result = strrchr($haystack, $needle);
        if ($result === false) {
            return false;
        }
        return api_substr($haystack, 0, api_strlen($haystack, $encoding) - api_strlen($result, $encoding), $encoding);
    } elseif (_api_mb_supports($encoding)) {
        return mb_strrchr($haystack, $needle, $before_needle, $encoding);
    }
    $result = strrchr($haystack, $needle);
    if ($result === false) {
        return false;
    }
    return api_substr($haystack, 0, api_strlen($haystack, $encoding) - api_strlen($result, $encoding), $encoding);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
}

/**
 * @see mb_strpos
 */
function api_strpos($haystack, $needle, $offset = 0, $encoding = null)
{
    return mb_strpos($haystack, $needle, $offset, $encoding);
}

/**
 * Finds the position of last occurrence (case insensitive) of a string in a string.
 * @param string $haystack                The string from which to get the position of the last occurrence.
 * @param string $needle                The string to be found.
 * @param int $offset (optional)        $offset may be specified to begin searching an arbitrary position. Negative values will stop searching at an arbitrary point prior to the end of the string.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return mixed                        Returns the numeric position of the first occurrence (case insensitive) of $needle in the $haystack, or FALSE if $needle is not found.
 * Note: The first character's position is 0, the second character position is 1, and so on.
 * This function is aimed at replacing the functions strripos() and mb_strripos() for human-language strings.
 * @link http://php.net/manual/en/function.strripos
 * @link http://php.net/manual/en/function.mb-strripos
 */
function api_strripos($haystack, $needle, $offset = 0, $encoding = null)
{
<<<<<<< HEAD
    return mb_strripos($haystack, $needle, $offset, $encoding);
=======
    return api_strrpos(api_strtolower($haystack, $encoding), api_strtolower($needle, $encoding), $offset, $encoding);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
}

/**
 * @see mb_strrpos
 */
function api_strrpos($haystack, $needle, $offset = 0, $encoding = null)
{
<<<<<<< HEAD
    return mb_strrpos($haystack, $needle, $offset);
}

/**
 * @see mb_strstr
 **/
function api_strstr($haystack, $needle, $before_needle = false, $encoding = null)
{
    return mb_strstr($haystack, $needle, $before_needle);
=======
    if (_api_mb_supports($encoding)) {
        if (empty($encoding)) {
            $encoding = _api_mb_internal_encoding();
        }
        return mb_strrpos($haystack, $needle, $offset, $encoding);
    }
    return strrpos($haystack, $needle, $offset);
}

/**
 * Finds first occurrence of a string within another.
 * @param string $haystack					The string from which to get the first occurrence.
 * @param mixed $needle						The string to be found.
 * @param bool $before_needle (optional)	Determines which portion of $haystack this function returns. The default value is FALSE.
 * @param string $encoding (optional)		The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return mixed							Returns the portion of $haystack, or FALSE if $needle is not found.
 * Notes:
 * If $needle is not a string, it is converted to an integer and applied as the ordinal value (codepoint if the encoding is UTF-8) of a character.
 * If $before_needle is set to TRUE, the function returns all of $haystack from the beginning to the first occurrence of $needle.
 * If $before_needle is set to FALSE, the function returns all of $haystack from the first occurrence of $needle to the end.
 * This function is aimed at replacing the functions strstr() and mb_strstr() for human-language strings.
 * @link http://php.net/manual/en/function.strstr
 * @link http://php.net/manual/en/function.mb-strstr
 */
function api_strstr($haystack, $needle, $before_needle = false, $encoding = null) {
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    if (!is_string($needle)) {
        $needle = (int)$needle;
        if (api_is_utf8($encoding)) {
            $needle = _api_utf8_chr($needle);
        } else {
            $needle = chr($needle);
        }
    }
    if ($needle == '') {
        return false;
    }
    if (_api_is_single_byte_encoding($encoding)) {
        return strstr($haystack, $needle, $before_needle);
    }
    if (_api_mb_supports($encoding)) {
        return mb_strstr($haystack, $needle, $before_needle, $encoding);
    }
    return strstr($haystack, $needle, $before_needle);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
}

/**
 * @see strtolower
 */
<<<<<<< HEAD
function api_strtolower($string, $encoding = null)
{
=======
function api_strtolower($string, $encoding = null) {
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    if (_api_mb_supports($encoding)) {
        return mb_strtolower($string, $encoding);
    }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    return strtolower($string);
}

/**
 * @see strtoupper
 */
<<<<<<< HEAD
function api_strtoupper($string, $encoding = null)
{
=======
function api_strtoupper($string, $encoding = null) {
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    if (_api_mb_supports($encoding)) {
        return mb_strtoupper($string, $encoding);
    }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    return strtoupper($string);
}

/**
 * @see substr
 */
<<<<<<< HEAD
function api_substr($string, $start, $length = null, $encoding = null)
{
=======
function api_substr($string, $start, $length = null, $encoding = null) {
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    // Passing null as $length would mean 0. This behaviour has been corrected here.
    if (is_null($length)) {
        $length = api_strlen($string, $encoding);
    }
    if (_api_is_single_byte_encoding($encoding)) {
        return substr($string, $start, $length);
    }
    if (_api_mb_supports($encoding)) {
        return mb_substr($string, $start, $length, $encoding);
    }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    return substr($string, $start, $length);
}

/**
 * @see substr_replace
 */
<<<<<<< HEAD
function api_substr_replace($string, $replacement, $start, $length = null, $encoding = null)
{
=======
function api_substr_count($haystack, $needle, $encoding = null) {
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    if (_api_mb_supports($encoding)) {
        return mb_substr_count($haystack, $needle, $encoding);
    }
    return substr_count($haystack, $needle);
}

/**
 * Replaces text within a portion of a string.
 * @param string $string				The input string.
 * @param string $replacement			The replacement string.
 * @param int $start					The position from which replacing will begin.
 * Notes:
 * If $start is positive, the replacing will begin at the $start'th offset into the string.
 * If $start is negative, the replacing will begin at the $start'th character from the end of the string.
 * @param int $length (optional)		The position where replacing will end.
 * Notes:
 * If given and is positive, it represents the length of the portion of the string which is to be replaced.
 * If it is negative, it represents the number of characters from the end of string at which to stop replacing.
 * If it is not given, then it will default to api_strlen($string); i.e. end the replacing at the end of string.
 * If $length is zero, then this function will have the effect of inserting replacement into the string at the given start offset.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return string						The result string is returned.
 * This function is aimed at replacing the function substr_replace() for human-language strings.
 * @link http://php.net/manual/function.substr-replace
 */
function api_substr_replace($string, $replacement, $start, $length = null, $encoding = null) {
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    if (_api_is_single_byte_encoding($encoding)) {
        if (is_null($length)) {
            return substr_replace($string, $replacement, $start);
        }
        return substr_replace($string, $replacement, $start, $length);
    }
    if (api_is_encoding_supported($encoding)) {
        if (is_null($length)) {
            $length = api_strlen($string);
        }
        if (!api_is_utf8($encoding)) {
            $string = api_utf8_encode($string, $encoding);
            $replacement = api_utf8_encode($replacement, $encoding);
        }
        $string = _api_utf8_to_unicode($string);
        array_splice($string, $start, $length, _api_utf8_to_unicode($replacement));
        $string = _api_utf8_from_unicode($string);
        if (!api_is_utf8($encoding)) {
            $string = api_utf8_decode($string, $encoding);
        }
        return $string;
    }
    if (is_null($length)) {
        return substr_replace($string, $replacement, $start);
    }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    return substr_replace($string, $replacement, $start, $length);
}

/**
 * @see ucfirst
 */
<<<<<<< HEAD
function api_ucfirst($string, $encoding = null)
{
    return ucfirst($string);
=======
function api_ucfirst($string, $encoding = null) {
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    return api_strtoupper(api_substr($string, 0, 1, $encoding), $encoding) . api_substr($string, 1, api_strlen($string, $encoding), $encoding);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
}

/**
 * @see ucwords
 */
<<<<<<< HEAD
function api_ucwords($string, $encoding = null)
{
    return ucwords($string);
}

=======
function api_ucwords($string, $encoding = null) {
    if (_api_mb_supports($encoding)) {
        if (empty($encoding)) {
            $encoding = _api_mb_internal_encoding();
        }
        return mb_convert_case($string, MB_CASE_TITLE, $encoding);
    }
    return ucwords($string);
}

/**
 * String operations using regular expressions
 */

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
/**
 * Performs a regular expression match, UTF-8 aware when it is applicable.
 * @param string $pattern                The pattern to search for, as a string.
 * @param string $subject                The input string.
 * @param array &$matches (optional)    If matches is provided, then it is filled with the results of search (as an array).
 *                                         $matches[0] will contain the text that matched the full pattern, $matches[1] will have the text that matched the first captured parenthesized subpattern, and so on.
 * @param int $flags (optional)            Could be PREG_OFFSET_CAPTURE. If this flag is passed, for every occurring match the appendant string offset will also be returned.
 *                                         Note that this changes the return value in an array where every element is an array consisting of the matched string at index 0 and its string offset into subject at index 1.
 * @param int $offset (optional)        Normally, the search starts from the beginning of the subject string. The optional parameter offset can be used to specify the alternate place from which to start the search.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return int|boolean                    Returns the number of times pattern matches or FALSE if an error occurred.
 * @link http://php.net/preg_match
 */
function api_preg_match($pattern, $subject, &$matches = null, $flags = 0, $offset = 0, $encoding = null)
{
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    return preg_match(api_is_utf8($encoding) ? $pattern.'u' : $pattern, $subject, $matches, $flags, $offset);
}

/**
 * Performs a global regular expression match, UTF-8 aware when it is applicable.
 * @param string $pattern                The pattern to search for, as a string.
 * @param string $subject                The input string.
 * @param array &$matches (optional)    Array of all matches in multi-dimensional array ordered according to $flags.
 * @param int $flags (optional)            Can be a combination of the following flags (note that it doesn't make sense to use PREG_PATTERN_ORDER together with PREG_SET_ORDER):
 * PREG_PATTERN_ORDER - orders results so that $matches[0] is an array of full pattern matches, $matches[1] is an array of strings matched by the first parenthesized subpattern, and so on;
 * PREG_SET_ORDER - orders results so that $matches[0] is an array of first set of matches, $matches[1] is an array of second set of matches, and so on;
 * PREG_OFFSET_CAPTURE - If this flag is passed, for every occurring match the appendant string offset will also be returned. Note that this changes the value of matches
 * in an array where every element is an array consisting of the matched string at offset 0 and its string offset into subject at offset 1.
 * If no order flag is given, PREG_PATTERN_ORDER is assumed.
 * @param int $offset (optional)        Normally, the search starts from the beginning of the subject string. The optional parameter offset can be used to specify the alternate place from which to start the search.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return int|boolean                    Returns the number of full pattern matches (which might be zero), or FALSE if an error occurred.
 * @link http://php.net/preg_match_all
 */
function api_preg_match_all($pattern, $subject, &$matches, $flags = PREG_PATTERN_ORDER, $offset = 0, $encoding = null)
{
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    if (is_null($flags)) {
        $flags = PREG_PATTERN_ORDER;
    }
    return preg_match_all(api_is_utf8($encoding) ? $pattern.'u' : $pattern, $subject, $matches, $flags, $offset);
}

/**
 * Performs a regular expression search and replace, UTF-8 aware when it is applicable.
 * @param string|array $pattern            The pattern to search for. It can be either a string or an array with strings.
 * @param string|array $replacement        The string or an array with strings to replace.
 * @param string|array $subject            The string or an array with strings to search and replace.
 * @param int $limit                    The maximum possible replacements for each pattern in each subject string. Defaults to -1 (no limit).
 * @param int &$count                    If specified, this variable will be filled with the number of replacements done.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return array|string|null            returns an array if the subject parameter is an array, or a string otherwise.
 * If matches are found, the new subject will be returned, otherwise subject will be returned unchanged or NULL if an error occurred.
 * @link http://php.net/preg_replace
 */
function api_preg_replace($pattern, $replacement, $subject, $limit = -1, &$count = 0, $encoding = null)
{
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    $is_utf8 = api_is_utf8($encoding);
    if (is_array($pattern)) {
        foreach ($pattern as &$p) {
            $p = $is_utf8 ? $p.'u' : $p;
        }
    } else {
        $pattern = $is_utf8 ? $pattern.'u' : $pattern;
    }
    return preg_replace($pattern, $replacement, $subject, $limit, $count);
}

/**
 * Performs a regular expression search and replace using a callback function, UTF-8 aware when it is applicable.
 * @param string|array $pattern            The pattern to search for. It can be either a string or an array with strings.
 * @param function $callback            A callback that will be called and passed an array of matched elements in the $subject string. The callback should return the replacement string.
 * @param string|array $subject            The string or an array with strings to search and replace.
 * @param int $limit (optional)            The maximum possible replacements for each pattern in each subject string. Defaults to -1 (no limit).
 * @param int &$count (optional)        If specified, this variable will be filled with the number of replacements done.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return array|string                    Returns an array if the subject parameter is an array, or a string otherwise.
 * @link http://php.net/preg_replace_callback
 */
function api_preg_replace_callback($pattern, $callback, $subject, $limit = -1, &$count = 0, $encoding = null)
{
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    if (is_array($pattern)) {
        foreach ($pattern as &$p) {
            $p = api_is_utf8($encoding) ? $p.'u' : $p;
        }
    } else {
        $pattern = api_is_utf8($encoding) ? $pattern.'u' : $pattern;
    }
    return preg_replace_callback($pattern, $callback, $subject, $limit, $count);
}

/**
 * Splits a string by a regular expression, UTF-8 aware when it is applicable.
 * @param string $pattern                The pattern to search for, as a string.
 * @param string $subject                The input string.
 * @param int $limit (optional)            If specified, then only substrings up to $limit are returned with the rest of the string being placed in the last substring. A limit of -1, 0 or null means "no limit" and, as is standard across PHP.
 * @param int $flags (optional)            $flags can be any combination of the following flags (combined with bitwise | operator):
 * PREG_SPLIT_NO_EMPTY - if this flag is set, only non-empty pieces will be returned;
 * PREG_SPLIT_DELIM_CAPTURE - if this flag is set, parenthesized expression in the delimiter pattern will be captured and returned as well;
 * PREG_SPLIT_OFFSET_CAPTURE - If this flag is set, for every occurring match the appendant string offset will also be returned.
 * Note that this changes the return value in an array where every element is an array consisting of the matched string at offset 0 and its string offset into subject at offset 1.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return array                        Returns an array containing substrings of $subject split along boundaries matched by $pattern.
 * @link http://php.net/preg_split
 */
function api_preg_split($pattern, $subject, $limit = -1, $flags = 0, $encoding = null)
{
    if (empty($encoding)) {
        $encoding = _api_mb_internal_encoding();
    }
    return preg_split(api_is_utf8($encoding) ? $pattern.'u' : $pattern, $subject, $limit, $flags);
}

<<<<<<< HEAD
=======
/**
 * Obsolete string operations using regular expressions, to be deprecated
 */

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
/**
 * Note: Try to avoid using this function. Use api_preg_match() with Perl-compatible regular expression syntax.
 *
 * Executes a regular expression match with extended multibyte support.
 * By default this function uses the platform character set.
 * @param string $pattern            The regular expression pattern.
 * @param string $string            The searched string.
 * @param array $regs (optional)    If specified, by this passed by reference parameter an array containing found match and its substrings is returned.
 * @return mixed                    1 if match is found, FALSE if not. If $regs has been specified, byte-length of the found match is returned, or FALSE if no match has been found.
 * This function is aimed at replacing the functions ereg() and mb_ereg() for human-language strings.
 * @link http://php.net/manual/en/function.ereg
 * @link http://php.net/manual/en/function.mb-ereg
 * @deprecated
 */
function api_ereg($pattern, $string, & $regs = null)
{
    $count = func_num_args();
    $encoding = _api_mb_regex_encoding();
    if (_api_mb_supports($encoding)) {
        if ($count < 3) {
            return @mb_ereg($pattern, $string);
        }
        return @mb_ereg($pattern, $string, $regs);
    }
    if (MBSTRING_INSTALLED && api_is_encoding_supported($encoding)) {
        global $_api_encoding;
        $_api_encoding = $encoding;
        _api_mb_regex_encoding('UTF-8');
        if ($count < 3) {
            $result = @mb_ereg(api_utf8_encode($pattern, $encoding), api_utf8_encode($string, $encoding));
        } else {
            $result = @mb_ereg(api_utf8_encode($pattern, $encoding), api_utf8_encode($string, $encoding), $regs);
            $regs = _api_array_utf8_decode($regs);
        }
        _api_mb_regex_encoding($encoding);
        return $result;
    }
    if ($count < 3) {
        return ereg($pattern, $string);
    }
    return ereg($pattern, $string, $regs);
}

/**
 * Note: Try to avoid using this function. Use api_preg_match() with Perl-compatible regular expression syntax.
 *
 * Executes a regular expression match, ignoring case, with extended multibyte support.
 * By default this function uses the platform character set.
 * @param string $pattern            The regular expression pattern.
 * @param string $string            The searched string.
 * @param array $regs (optional)    If specified, by this passed by reference parameter an array containing found match and its substrings is returned.
 * @return mixed                    1 if match is found, FALSE if not. If $regs has been specified, byte-length of the found match is returned, or FALSE if no match has been found.
 * This function is aimed at replacing the functions eregi() and mb_eregi() for human-language strings.
 * @link http://php.net/manual/en/function.eregi
 * @link http://php.net/manual/en/function.mb-eregi
 */
function api_eregi($pattern, $string, & $regs = null)
{
    $count = func_num_args();
    $encoding = _api_mb_regex_encoding();
    if (_api_mb_supports($encoding)) {
        if ($count < 3) {
            return @mb_eregi($pattern, $string);
        }
        return @mb_eregi($pattern, $string, $regs);
    }
    if (MBSTRING_INSTALLED && api_is_encoding_supported($encoding)) {
        global $_api_encoding;
        $_api_encoding = $encoding;
        _api_mb_regex_encoding('UTF-8');
        if ($count < 3) {
            $result = @mb_eregi(api_utf8_encode($pattern, $encoding), api_utf8_encode($string, $encoding));
        } else {
            $result = @mb_eregi(api_utf8_encode($pattern, $encoding), api_utf8_encode($string, $encoding), $regs);
            $regs = _api_array_utf8_decode($regs);
        }
        _api_mb_regex_encoding($encoding);
        return $result;
    }
    if ($count < 3) {
        return eregi($pattern, $string);
<<<<<<< HEAD
=======
    }
    return eregi($pattern, $string, $regs);
}

/**
 * Note: Try to avoid using this function. Use api_preg_replace() with Perl-compatible regular expression syntax.
 *
 * Scans string for matches to pattern, then replaces the matched text with replacement, ignoring case, with extended multibyte support.
 * By default this function uses the platform character set.
 * @param string $pattern				The regular expression pattern.
 * @param string $replacement			The replacement text.
 * @param string $string				The searched string.
 * @param string $option (optional)		Matching condition.
 * If i is specified for the matching condition parameter, the case will be ignored.
 * If x is specified, white space will be ignored.
 * If m is specified, match will be executed in multiline mode and line break will be included in '.'.
 * If p is specified, match will be executed in POSIX mode, line break will be considered as normal character.
 * If e is specified, replacement string will be evaluated as PHP expression.
 * @return mixed						The modified string is returned. If no matches are found within the string, then it will be returned unchanged. FALSE will be returned on error.
 * This function is aimed at replacing the functions eregi_replace() and mb_eregi_replace() for human-language strings.
 * @link http://php.net/manual/en/function.eregi-replace
 * @link http://php.net/manual/en/function.mb-eregi-replace
 */
function api_eregi_replace($pattern, $replacement, $string, $option = null) {
    $encoding = _api_mb_regex_encoding();
    if (_api_mb_supports($encoding)) {
        if (is_null($option)) {
            return @mb_eregi_replace($pattern, $replacement, $string);
        }
        return @mb_eregi_replace($pattern, $replacement, $string, $option);
    }
    if (MBSTRING_INSTALLED && api_is_encoding_supported($encoding)) {
        _api_mb_regex_encoding('UTF-8');
        if (is_null($option)) {
            $result = api_utf8_decode(@mb_eregi_replace(api_utf8_encode($pattern, $encoding), api_utf8_encode($replacement, $encoding), api_utf8_encode($string, $encoding)), $encoding);
        } else {
            $result = api_utf8_decode(@mb_eregi_replace(api_utf8_encode($pattern, $encoding), api_utf8_encode($replacement, $encoding), api_utf8_encode($string, $encoding), $option), $encoding);
        }
        _api_mb_regex_encoding($encoding);
        return $result;
    }
    return eregi_replace($pattern, $replacement, $string);
}

/**
 * Note: Try to avoid using this function. Use api_preg_split() with Perl-compatible regular expression syntax.
 *
 * Splits a multibyte string using regular expression pattern and returns the result as an array.
 * By default this function uses the platform character set.
 * @param string $pattern			The regular expression pattern.
 * @param string $string			The string being split.
 * @param int $limit (optional)		If this optional parameter $limit is specified, the string will be split in $limit elements as maximum.
 * @return array					The result as an array.
 * This function is aimed at replacing the functions split() and mb_split() for human-language strings.
 * @link http://php.net/manual/en/function.split
 * @link http://php.net/manual/en/function.mb-split
 */
function api_split($pattern, $string, $limit = null) {
    $encoding = _api_mb_regex_encoding();
    if (_api_mb_supports($encoding)) {
        if (is_null($limit)) {
            return @mb_split($pattern, $string);
        }
        return @mb_split($pattern, $string, $limit);
    }
    if (MBSTRING_INSTALLED && api_is_encoding_supported($encoding)) {
        global $_api_encoding;
        $_api_encoding = $encoding;
        _api_mb_regex_encoding('UTF-8');
        if (is_null($limit)) {
            $result = @mb_split(api_utf8_encode($pattern, $encoding), api_utf8_encode($string, $encoding));
        } else {
            $result = @mb_split(api_utf8_encode($pattern, $encoding), api_utf8_encode($string, $encoding), $limit);
        }
        $result = _api_array_utf8_decode($result);
        _api_mb_regex_encoding($encoding);
        return $result;
    }
    if (is_null($limit)) {
        return split($pattern, $string);
    }
    return split($pattern, $string, $limit);
}

/**
 * String comparison
 */

/**
 * Performs string comparison, case insensitive, language sensitive, with extended multibyte support.
 * @param string $string1				The first string.
 * @param string $string2				The second string.
 * @param string $language (optional)	The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return int							Returns < 0 if $string1 is less than $string2; > 0 if $string1 is greater than $string2; and 0 if the strings are equal.
 * This function is aimed at replacing the function strcasecmp() for human-language strings.
 * @link http://php.net/manual/en/function.strcasecmp
 */
function api_strcasecmp($string1, $string2, $language = null, $encoding = null) {
    return api_strcmp(api_strtolower($string1, $encoding), api_strtolower($string2, $encoding), $language, $encoding);
}

/**
 * Performs string comparison, case sensitive, language sensitive, with extended multibyte support.
 * @param string $string1				The first string.
 * @param string $string2				The second string.
 * @param string $language (optional)	The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return int							Returns < 0 if $string1 is less than $string2; > 0 if $string1 is greater than $string2; and 0 if the strings are equal.
 * This function is aimed at replacing the function strcmp() for human-language strings.
 * @link http://php.net/manual/en/function.strcmp.php
 * @link http://php.net/manual/en/collator.compare.php
 */
function api_strcmp($string1, $string2, $language = null, $encoding = null) {
    if (INTL_INSTALLED) {
        $collator = _api_get_collator($language);
        if (is_object($collator)) {
            $result = collator_compare($collator, api_utf8_encode($string1, $encoding), api_utf8_encode($string2, $encoding));
            return $result === false ? 0 : $result;
        }
    }
    return strcmp($string1, $string2);
}

/**
 * Performs string comparison in so called "natural order", case insensitive, language sensitive, with extended multibyte support.
 * @param string $string1				The first string.
 * @param string $string2				The second string.
 * @param string $language (optional)	The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return int							Returns < 0 if $string1 is less than $string2; > 0 if $string1 is greater than $string2; and 0 if the strings are equal.
 * This function is aimed at replacing the function strnatcasecmp() for human-language strings.
 * @link http://php.net/manual/en/function.strnatcasecmp
 */
function api_strnatcasecmp($string1, $string2, $language = null, $encoding = null) {
    return api_strnatcmp(api_strtolower($string1, $encoding), api_strtolower($string2, $encoding), $language, $encoding);
}

/**
 * Performs string comparison in so called "natural order", case sensitive, language sensitive, with extended multibyte support.
 * @param string $string1				The first string.
 * @param string $string2				The second string.
 * @param string $language (optional)	The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return int							Returns < 0 if $string1 is less than $string2; > 0 if $string1 is greater than $string2; and 0 if the strings are equal.
 * This function is aimed at replacing the function strnatcmp() for human-language strings.
 * @link http://php.net/manual/en/function.strnatcmp.php
 * @link http://php.net/manual/en/collator.compare.php
 */
function api_strnatcmp($string1, $string2, $language = null, $encoding = null) {
    if (INTL_INSTALLED) {
        $collator = _api_get_alpha_numerical_collator($language);
        if (is_object($collator)) {
            $result = collator_compare($collator, api_utf8_encode($string1, $encoding), api_utf8_encode($string2, $encoding));
            return $result === false ? 0 : $result;
        }
    }
    return strnatcmp($string1, $string2);
}

/**
 * Sorting arrays
 */

/**
 * Sorts an array with maintaining index association, elements will be arranged from the lowest to the highest.
 * @param array $array					The input array.
 * @param int $sort_flag (optional)		Shows how elements of the array to be compared.
 * @param string $language (optional)	The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return bool							Returns TRUE on success, FALSE on error.
 * Note: $sort_flag may have the following values:
 * SORT_REGULAR - internal PHP-rules for comparison will be applied, without preliminary changing types;
 * SORT_NUMERIC - items will be compared as numbers;
 * SORT_STRING - items will be compared as strings. If intl extension is enabled, then comparison will be language-sensitive using internally a created ICU locale;
 * SORT_LOCALE_STRING - items will be compared as strings depending on the current POSIX locale. If intl extension is enabled, then comparison will be language-sensitive using internally a created ICU locale.
 * This function is aimed at replacing the function asort() for sorting human-language strings.
 * @link http://php.net/manual/en/function.asort.php
 * @link http://php.net/manual/en/collator.asort.php
 */
function api_asort(&$array, $sort_flag = SORT_REGULAR, $language = null, $encoding = null) {
    if (INTL_INSTALLED) {
        if (empty($encoding)) {
            $encoding = _api_mb_internal_encoding();
        }
        $collator = _api_get_collator($language);
        if (is_object($collator)) {
            if (api_is_utf8($encoding)) {
                $sort_flag = ($sort_flag == SORT_LOCALE_STRING) ? SORT_STRING : $sort_flag;
                return collator_asort($collator, $array, _api_get_collator_sort_flag($sort_flag));
            }
            elseif ($sort_flag == SORT_STRING || $sort_flag == SORT_LOCALE_STRING) {
                global $_api_collator, $_api_encoding;
                $_api_collator = $collator;
                $_api_encoding = $encoding;
                return uasort($array, '_api_cmp');
            }
        }
    }
    return asort($array, $sort_flag);
}

/**
 * Sorts an array with maintaining index association, elements will be arranged from the highest to the lowest (in reverse order).
 * @param array $array					The input array.
 * @param int $sort_flag (optional)		Shows how elements of the array to be compared.
 * @param string $language (optional)	The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return bool							Returns TRUE on success, FALSE on error.
 * Note: $sort_flag may have the following values:
 * SORT_REGULAR - internal PHP-rules for comparison will be applied, without preliminary changing types;
 * SORT_NUMERIC - items will be compared as numbers;
 * SORT_STRING - items will be compared as strings. If intl extension is enabled, then comparison will be language-sensitive using internally a created ICU locale;
 * SORT_LOCALE_STRING - items will be compared as strings depending on the current POSIX locale. If intl extension is enabled, then comparison will be language-sensitive using internally a created ICU locale.
 * This function is aimed at replacing the function arsort() for sorting human-language strings.
 * @link http://php.net/manual/en/function.arsort.php
 */
function api_arsort(&$array, $sort_flag = SORT_REGULAR, $language = null, $encoding = null) {
    if (INTL_INSTALLED) {
        if (empty($encoding)) {
            $encoding = _api_mb_internal_encoding();
        }
        $collator = _api_get_collator($language);
        if (is_object($collator)) {
            if ($sort_flag == SORT_STRING || $sort_flag == SORT_LOCALE_STRING) {
                global $_api_collator, $_api_encoding;
                $_api_collator = $collator;
                $_api_encoding = $encoding;
                return uasort($array, '_api_rcmp');
            }
        }
    }
    return arsort($array, $sort_flag);
}

/**
 * Sorts an array using natural order algorithm.
 * @param array $array					The input array.
 * @param string $language (optional)	The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return bool							Returns TRUE on success, FALSE on error.
 * This function is aimed at replacing the function natsort() for sorting human-language strings.
 * @link http://php.net/manual/en/function.natsort.php
 */
function api_natsort(&$array, $language = null, $encoding = null) {
    if (INTL_INSTALLED) {
        if (empty($encoding)) {
            $encoding = _api_mb_internal_encoding();
        }
        $collator = _api_get_alpha_numerical_collator($language);
        if (is_object($collator)) {
            global $_api_collator, $_api_encoding;
            $_api_collator = $collator;
            $_api_encoding = $encoding;
            return uasort($array, '_api_cmp');
        }
    }
    return natsort($array);
}

/**
 * Sorts an array using natural order algorithm in reverse order.
 * @param array $array					The input array.
 * @param string $language (optional)	The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return bool							Returns TRUE on success, FALSE on error.
 */
function api_natrsort(&$array, $language = null, $encoding = null) {
    if (INTL_INSTALLED) {
        if (empty($encoding)) {
            $encoding = _api_mb_internal_encoding();
        }
        $collator = _api_get_alpha_numerical_collator($language);
        if (is_object($collator)) {
            global $_api_collator, $_api_encoding;
            $_api_collator = $collator;
            $_api_encoding = $encoding;
            return uasort($array, '_api_rcmp');
        }
    }
    return uasort($array, '_api_strnatrcmp');
}

/**
 * Sorts an array using natural order algorithm, case-insensitive.
 * @param array $array					The input array.
 * @param string $language (optional)	The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return bool							Returns TRUE on success, FALSE on error.
 * This function is aimed at replacing the function natcasesort() for sorting human-language strings.
 * @link http://php.net/manual/en/function.natcasesort.php
 */
function api_natcasesort(&$array, $language = null, $encoding = null) {
    if (INTL_INSTALLED) {
        if (empty($encoding)) {
            $encoding = _api_mb_internal_encoding();
        }
        $collator = _api_get_alpha_numerical_collator($language);
        if (is_object($collator)) {
            global $_api_collator, $_api_encoding;
            $_api_collator = $collator;
            $_api_encoding = $encoding;
            return uasort($array, '_api_casecmp');
        }
    }
    return natcasesort($array);
}

/**
 * Sorts an array using natural order algorithm, case-insensitive, reverse order.
 * @param array $array					The input array.
 * @param string $language (optional)	The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return bool							Returns TRUE on success, FALSE on error.
 */
function api_natcasersort(&$array, $language = null, $encoding = null) {
    if (INTL_INSTALLED) {
        if (empty($encoding)) {
            $encoding = _api_mb_internal_encoding();
        }
        $collator = _api_get_alpha_numerical_collator($language);
        if (is_object($collator)) {
            global $_api_collator, $_api_encoding;
            $_api_collator = $collator;
            $_api_encoding = $encoding;
            return uasort($array, '_api_casercmp');
        }
    }
    return uasort($array, '_api_strnatcasercmp');
}

/**
 * Sorts an array by keys, elements will be arranged from the lowest key to the highest key.
 * @param array $array					The input array.
 * @param int $sort_flag (optional)		Shows how keys of the array to be compared.
 * @param string $language (optional)	The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)	The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return bool							Returns TRUE on success, FALSE on error.
 * Note: $sort_flag may have the following values:
 * SORT_REGULAR - internal PHP-rules for comparison will be applied, without preliminary changing types;
 * SORT_NUMERIC - keys will be compared as numbers;
 * SORT_STRING - keys will be compared as strings. If intl extension is enabled, then comparison will be language-sensitive using internally a created ICU locale;
 * SORT_LOCALE_STRING - keys will be compared as strings depending on the current POSIX locale. If intl extension is enabled, then comparison will be language-sensitive using internally a created ICU locale.
 * This function is aimed at replacing the function ksort() for sorting human-language key strings.
 * @link http://php.net/manual/en/function.ksort.php
 */
function api_ksort(&$array, $sort_flag = SORT_REGULAR, $language = null, $encoding = null) {
    if (INTL_INSTALLED) {
        if (empty($encoding)) {
            $encoding = _api_mb_internal_encoding();
        }
        $collator = _api_get_collator($language);
        if (is_object($collator)) {
            if ($sort_flag == SORT_STRING || $sort_flag == SORT_LOCALE_STRING) {
                global $_api_collator, $_api_encoding;
                $_api_collator = $collator;
                $_api_encoding = $encoding;
                return uksort($array, '_api_cmp');
            }
        }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    }
    return eregi($pattern, $string, $regs);
}

/**
 * Note: Try to avoid using this function. Use api_preg_replace() with Perl-compatible regular expression syntax.
 *
 * Scans string for matches to pattern, then replaces the matched text with replacement, ignoring case, with extended multibyte support.
 * By default this function uses the platform character set.
 * @param string $pattern                The regular expression pattern.
 * @param string $replacement            The replacement text.
 * @param string $string                The searched string.
 * @param string $option (optional)        Matching condition.
 * If i is specified for the matching condition parameter, the case will be ignored.
 * If x is specified, white space will be ignored.
 * If m is specified, match will be executed in multiline mode and line break will be included in '.'.
 * If p is specified, match will be executed in POSIX mode, line break will be considered as normal character.
 * If e is specified, replacement string will be evaluated as PHP expression.
 * @return mixed                        The modified string is returned. If no matches are found within the string, then it will be returned unchanged. FALSE will be returned on error.
 * This function is aimed at replacing the functions eregi_replace() and mb_eregi_replace() for human-language strings.
 * @link http://php.net/manual/en/function.eregi-replace
 * @link http://php.net/manual/en/function.mb-eregi-replace
 */
function api_eregi_replace($pattern, $replacement, $string, $option = null)
{
    $encoding = _api_mb_regex_encoding();
    if (_api_mb_supports($encoding)) {
        if (is_null($option)) {
            return @mb_eregi_replace($pattern, $replacement, $string);
        }
        return @mb_eregi_replace($pattern, $replacement, $string, $option);
    }
    if (MBSTRING_INSTALLED && api_is_encoding_supported($encoding)) {
        _api_mb_regex_encoding('UTF-8');
        if (is_null($option)) {
            $result = api_utf8_decode(
                @mb_eregi_replace(
                    api_utf8_encode($pattern, $encoding),
                    api_utf8_encode($replacement, $encoding),
                    api_utf8_encode($string, $encoding)
                ),
                $encoding
            );
        } else {
            $result = api_utf8_decode(
                @mb_eregi_replace(
                    api_utf8_encode($pattern, $encoding),
                    api_utf8_encode($replacement, $encoding),
                    api_utf8_encode($string, $encoding),
                    $option
                ),
                $encoding
            );
        }
        _api_mb_regex_encoding($encoding);
        return $result;
    }
    return eregi_replace($pattern, $replacement, $string);
}

/**
 * String comparison
 */

/**
 * Performs string comparison, case insensitive, language sensitive, with extended multibyte support.
 * @param string $string1                The first string.
 * @param string $string2                The second string.
 * @param string $language (optional)    The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return int                            Returns < 0 if $string1 is less than $string2; > 0 if $string1 is greater than $string2; and 0 if the strings are equal.
 * This function is aimed at replacing the function strcasecmp() for human-language strings.
 * @link http://php.net/manual/en/function.strcasecmp
 */
function api_strcasecmp($string1, $string2, $language = null, $encoding = null)
{
    return strcasecmp($string1, $string2);
}

/**
 * Performs string comparison, case sensitive, language sensitive, with extended multibyte support.
 * @param string $string1                The first string.
 * @param string $string2                The second string.
 * @param string $language (optional)    The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return int                            Returns < 0 if $string1 is less than $string2; > 0 if $string1 is greater than $string2; and 0 if the strings are equal.
 * This function is aimed at replacing the function strcmp() for human-language strings.
 * @link http://php.net/manual/en/function.strcmp.php
 * @link http://php.net/manual/en/collator.compare.php
 */
function api_strcmp($string1, $string2, $language = null, $encoding = null)
{
    return strcmp($string1, $string2);
}

/**
 * Performs string comparison in so called "natural order", case insensitive, language sensitive, with extended multibyte support.
 * @param string $string1                The first string.
 * @param string $string2                The second string.
 * @param string $language (optional)    The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return int                            Returns < 0 if $string1 is less than $string2; > 0 if $string1 is greater than $string2; and 0 if the strings are equal.
 * This function is aimed at replacing the function strnatcasecmp() for human-language strings.
 * @link http://php.net/manual/en/function.strnatcasecmp
 */
function api_strnatcasecmp($string1, $string2, $language = null, $encoding = null)
{
    return strnatcasecmp($string1, $string2);
}

/**
 * Performs string comparison in so called "natural order", case sensitive, language sensitive, with extended multibyte support.
 * @param string $string1                The first string.
 * @param string $string2                The second string.
 * @param string $language (optional)    The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return int                            Returns < 0 if $string1 is less than $string2; > 0 if $string1 is greater than $string2; and 0 if the strings are equal.
 * This function is aimed at replacing the function strnatcmp() for human-language strings.
 * @link http://php.net/manual/en/function.strnatcmp.php
 * @link http://php.net/manual/en/collator.compare.php
 */
function api_strnatcmp($string1, $string2, $language = null, $encoding = null)
{
    return strnatcmp($string1, $string2);
}

/**
 * Sorts an array with maintaining index association, elements will be arranged from the lowest to the highest.
 * @param array $array                    The input array.
 * @param int $sort_flag (optional)        Shows how elements of the array to be compared.
 * @param string $language (optional)    The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return bool                            Returns TRUE on success, FALSE on error.
 * Note: $sort_flag may have the following values:
 * SORT_REGULAR - internal PHP-rules for comparison will be applied, without preliminary changing types;
 * SORT_NUMERIC - items will be compared as numbers;
 * SORT_STRING - items will be compared as strings. If intl extension is enabled, then comparison will be language-sensitive using internally a created ICU locale;
 * SORT_LOCALE_STRING - items will be compared as strings depending on the current POSIX locale. If intl extension is enabled, then comparison will be language-sensitive using internally a created ICU locale.
 * This function is aimed at replacing the function asort() for sorting human-language strings.
 * @link http://php.net/manual/en/function.asort.php
 * @link http://php.net/manual/en/collator.asort.php
 */
function api_asort(&$array, $sort_flag = SORT_REGULAR, $language = null, $encoding = null)
{
    if (INTL_INSTALLED) {
        if (empty($encoding)) {
            $encoding = _api_mb_internal_encoding();
        }
        $collator = _api_get_collator($language);
        if (is_object($collator)) {
            if (api_is_utf8($encoding)) {
                $sort_flag = ($sort_flag == SORT_LOCALE_STRING) ? SORT_STRING : $sort_flag;
<<<<<<< HEAD
                return collator_asort($collator, $array, _api_get_collator_sort_flag($sort_flag));
=======
                return collator_sort($collator, $array, _api_get_collator_sort_flag($sort_flag));
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
            } elseif ($sort_flag == SORT_STRING || $sort_flag == SORT_LOCALE_STRING) {
                global $_api_collator, $_api_encoding;
                $_api_collator = $collator;
                $_api_encoding = $encoding;
                return uasort($array, '_api_cmp');
            }
        }
    }
    return asort($array, $sort_flag);
}

/**
 * Sorts an array with maintaining index association, elements will be arranged from the highest to the lowest (in reverse order).
 * @param array $array                    The input array.
 * @param int $sort_flag (optional)        Shows how elements of the array to be compared.
 * @param string $language (optional)    The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return bool                            Returns TRUE on success, FALSE on error.
 * Note: $sort_flag may have the following values:
 * SORT_REGULAR - internal PHP-rules for comparison will be applied, without preliminary changing types;
 * SORT_NUMERIC - items will be compared as numbers;
 * SORT_STRING - items will be compared as strings. If intl extension is enabled, then comparison will be language-sensitive using internally a created ICU locale;
 * SORT_LOCALE_STRING - items will be compared as strings depending on the current POSIX locale. If intl extension is enabled, then comparison will be language-sensitive using internally a created ICU locale.
 * This function is aimed at replacing the function arsort() for sorting human-language strings.
 * @link http://php.net/manual/en/function.arsort.php
 */
function api_arsort(&$array, $sort_flag = SORT_REGULAR, $language = null, $encoding = null)
{
    if (INTL_INSTALLED) {
        if (empty($encoding)) {
            $encoding = _api_mb_internal_encoding();
        }
        $collator = _api_get_collator($language);
        if (is_object($collator)) {
            if ($sort_flag == SORT_STRING || $sort_flag == SORT_LOCALE_STRING) {
                global $_api_collator, $_api_encoding;
                $_api_collator = $collator;
                $_api_encoding = $encoding;
                return uasort($array, '_api_rcmp');
            }
        }
    }
    return arsort($array, $sort_flag);
}

/**
 * Sorts an array using natural order algorithm.
 * @param array $array                    The input array.
 * @param string $language (optional)    The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return bool                            Returns TRUE on success, FALSE on error.
 * This function is aimed at replacing the function natsort() for sorting human-language strings.
 * @link http://php.net/manual/en/function.natsort.php
 */
function api_natsort(&$array, $language = null, $encoding = null)
{
    if (INTL_INSTALLED) {
        if (empty($encoding)) {
            $encoding = _api_mb_internal_encoding();
        }
        $collator = _api_get_alpha_numerical_collator($language);
        if (is_object($collator)) {
            global $_api_collator, $_api_encoding;
            $_api_collator = $collator;
            $_api_encoding = $encoding;
            return uasort($array, '_api_cmp');
        }
    }
    return natsort($array);
}

/**
 * A reverse function from php-core function strnatcmp(), performs string comparison in reverse natural (alpha-numerical) order.
 * @param string $string1		The first string.
 * @param string $string2		The second string.
 * @return int					Returns 0 if $string1 = $string2; >0 if $string1 < $string2; <0 if $string1 > $string2.
 */
function _api_strnatrcmp($string1, $string2) {
    return strnatcmp($string2, $string1);
}

<<<<<<< HEAD
/**
 * Sorts an array using natural order algorithm in reverse order.
 * @param array $array                    The input array.
 * @param string $language (optional)    The language in which comparison is to be made. If language is omitted, interface language is assumed then.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return bool                            Returns TRUE on success, FALSE on error.
 */
function api_natrsort(&$array, $language = null, $encoding = null)
{
    return uasort($array, '_api_strnatrcmp');
}

=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
/**
 * Common sting operations with arrays
 */

/**
 * Checks if a value exists in an array, a case insensitive version of in_array() function with extended multibyte support.
 * @param mixed $needle                    The searched value. If needle is a string, the comparison is done in a case-insensitive manner.
 * @param array $haystack                The array.
 * @param bool $strict (optional)        If is set to TRUE then the function will also check the types of the $needle in the $haystack. The default value if FALSE.
 * @param string $encoding (optional)    The used internally by this function character encoding. If it is omitted, the platform character set will be used by default.
 * @return bool                            Returns TRUE if $needle is found in the array, FALSE otherwise.
 * @link http://php.net/manual/en/function.in-array.php
 */
function api_in_array_nocase($needle, $haystack, $strict = false, $encoding = null)
{
    if (is_array($needle)) {
        foreach ($needle as $item) {
            if (api_in_array_nocase($item, $haystack, $strict, $encoding)) {
                return true;
            }
        }
        return false;
    }
    if (!is_string($needle)) {
        return in_array($needle, $haystack, $strict);
    }
    $needle = api_strtolower($needle, $encoding);
    if (!is_array($haystack)) {
        return false;
    }
    foreach ($haystack as $item) {
        if ($strict && !is_string($item)) {
            continue;
        }
        if (api_strtolower($item, $encoding) == $needle) {
            return true;
        }
    }
    return false;
}

/**
 * This function returns the encoding, currently used by the system.
 * @return string    The system's encoding, set in the configuration file
 */
function api_get_system_encoding()
{
    global $configuration;
    return isset($configuration['platform_charset']) ? $configuration['platform_charset'] : 'utf-8';
}

/**
 * Checks whether a specified encoding is supported by this API.
 * @param string $encoding    The specified encoding.
 * @return bool                Returns TRUE when the specified encoding is supported, FALSE othewise.
 */
function api_is_encoding_supported($encoding)
{
    static $supported = array();
    if (!isset($supported[$encoding])) {
        $supported[$encoding] = _api_mb_supports($encoding) || _api_iconv_supports(
            $encoding
        ) || _api_convert_encoding_supports($encoding);
    }
    return $supported[$encoding];
}


/**
 * Detects encoding of plain text.
 * @param string $string                The input text.
 * @param string $language (optional)    The language of the input text, provided if it is known.
 * @return string                        Returns the detected encoding.
 */
function api_detect_encoding($string, $language = null)
{
    return mb_detect_encoding($string);
}

/**
 * Checks a string for UTF-8 validity.
 *
 */
function api_is_valid_utf8($string)
{
    return u::isUtf8($string);
}

/**
 * Return true a date is valid

 * @param string $date example: 2014-06-30 13:05:05
 * @param string $format example: "Y-m-d H:i:s"
 *
 * @return bool
 */
function api_is_valid_date($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

/**
 * Returns returns person name convention for a given language.
 * @param string $language	The input language.
 * @param string $type		The type of the requested convention. It may be 'format' for name order convention or 'sort_by' for name sorting convention.
 * @return mixed			Depending of the requested type, the returned result may be string or boolean; null is returned on error;
 */
<<<<<<< HEAD
function _api_get_person_name_convention($language, $type)
{
    global $app;
    $conventions = $app['name_order_conventions'];
=======
function api_get_non_utf8_encoding($language = null)
{
    $language_is_supported = api_is_language_supported($language);
    if (!$language_is_supported || empty($language)) {
        $language = api_get_interface_language(false, true);
    }

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    $language = api_purify_language_id($language);

    switch ($type) {
        case 'format':
            return is_string($conventions[$language]['format']) ? $conventions[$language]['format'] : '%t %f %l';
        case 'sort_by':
            return is_bool($conventions[$language]['sort_by']) ? $conventions[$language]['sort_by'] : true;
    }
    return null;
}

/**
 * Replaces non-valid formats for person names with the default (English) format.
 * @param string $format	The input format to be verified.
 * @return bool				Returns the same format if is is valid, otherwise returns a valid English format.
 */
<<<<<<< HEAD
function _api_validate_person_name_format($format)
{
    if (empty($format) ||
        stripos($format, '%f') === false ||
        stripos($format, '%l') === false
    ) {
        return '%t %f %l';
=======
function api_get_valid_encodings() {
    $encodings = & _api_non_utf8_encodings();
    if (!is_array($encodings)) {
        $encodings = array('english', array('ISO-8859-15'));
    }

    $result1 = array();
    $result2 = array();
    $result3 = array();

    foreach ($encodings as $value) {
        if (!empty($value)) {
            $encoding = api_refine_encoding_id(trim($value[0]));
            if (!empty($encoding)) {
                if (strpos($encoding, 'ISO-') === 0) {
                    $result1[] = $encoding;
                } elseif (strpos($encoding, 'WINDOWS-') === 0) {
                    $result2[] = $encoding;
                } else {
                    $result3[] = $encoding;
                }
            }
        }
    }

    $result1 = array_unique($result1);
    $result2 = array_unique($result2);
    $result3 = array_unique($result3);
    natsort($result1);
    natsort($result2);
    natsort($result3);
    return array_merge(array('UTF-8'), $result1, $result2, $result3);
}

/**
 * Detects encoding of plain text.
 * @param string $string				The input text.
 * @param string $language (optional)	The language of the input text, provided if it is known.
 * @return string						Returns the detected encoding.
 */
function api_detect_encoding($string, $language = null) {
    // Testing against valid UTF-8 first.
    if (api_is_valid_utf8($string)) {
        return 'UTF-8';
    }
    $result = null;
    $delta_points_min = LANGUAGE_DETECT_MAX_DELTA;
    // Testing non-UTF-8 encodings.
    $encodings = api_get_valid_encodings();
    foreach ($encodings as & $encoding) {
        if (api_is_encoding_supported($encoding) && !api_is_utf8($encoding)) {
            $stringToParse = api_substr($string, 0, LANGUAGE_DETECT_MAX_LENGTH, $encoding);

            $strintToParse2 = _api_generate_n_grams(
                $stringToParse,
                $encoding
            );

            $result_array = _api_compare_n_grams(
                $strintToParse2,
                $encoding
            );

            if (!empty($result_array)) {
                list($key, $delta_points) = each($result_array);
                if ($delta_points < $delta_points_min) {
                    $pos = strpos($key, ':');
                    $result_encoding = api_refine_encoding_id(substr($key, $pos + 1));
                    if (api_equal_encodings($encoding, $result_encoding)) {
                        if ($string == api_utf8_decode(api_utf8_encode($string, $encoding), $encoding)) {
                            $delta_points_min = $delta_points;
                            $result = $encoding;
                        }
                    }
                }
            }
        }
    }
    // "Broken" UTF-8 texts are to be detected as UTF-8.
    // This functionality is enabled when language of the text is known.
    $language = api_purify_language_id((string)$language);
    if (!empty($language)) {
        $encoding = 'UTF-8';
        $result_array = & _api_compare_n_grams(_api_generate_n_grams(api_substr($string, 0, LANGUAGE_DETECT_MAX_LENGTH, $encoding), $encoding), $encoding);
        if (!empty($result_array)) {
            list($key, $delta_points) = each($result_array);
            if ($delta_points < $delta_points_min) {
                $pos = strpos($key, ':');
                $result_encoding = api_refine_encoding_id(substr($key, $pos + 1));
                $result_language = substr($key, 0, $pos);
                if ($language == $result_language && api_is_utf8($result_encoding)) {
                    $delta_points_min = $delta_points;
                    $result = $encoding;
                }
            }
        }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    }
    return $format;
}

/**
 * Removes leading, trailing and duplicate whitespace and/or commas in a full person name.
 * Cleaning is needed for the cases when not all parts of the name are available or when the name is constructed using a "dirty" pattern.
 * @param string $person_name	The input person name.
 * @return string				Returns cleaned person name.
 */
function _api_clean_person_name($person_name)
{
    return preg_replace(
        array('/\s+/', '/, ,/', '/,+/', '/^[ ,]/', '/[ ,]$/'),
        array(' ', ', ', ',', '', ''),
        $person_name
    );
}

/**
 * Returns an array of translated week days and months, short and normal names.
 * @param string $language (optional)	If it is omitted, the current interface language is assumed.
 * @return array						Returns a multidimensional array with translated week days and months.
 */
function &_api_get_day_month_names($language = null)
{
    static $date_parts = array();
    if (empty($language)) {
        $language = api_get_interface_language();
    }
<<<<<<< HEAD
    if (!isset($date_parts[$language])) {
        $week_day = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        $month = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        for ($i = 0; $i < 7; $i++) {
            $date_parts[$language]['days_short'][] = get_lang($week_day[$i].'Short', '', $language);
            $date_parts[$language]['days_long'][] = get_lang($week_day[$i].'Long', '', $language);
        }
        for ($i = 0; $i < 12; $i++) {
            $date_parts[$language]['months_short'][] = get_lang($month[$i].'Short', '', $language);
            $date_parts[$language]['months_long'][] = get_lang($month[$i].'Long', '', $language);
        }
    }
    return $date_parts[$language];
=======
    return !preg_match('/[^\x00-\x7F]/S', $string);
}

/**
 * Return true a date is valid

 * @param string $date example: 2014-06-30 13:05:05
 * @param string $format example: "Y-m-d H:i:s"
 *
 * @return bool
 */
function api_is_valid_date($date, $format = 'Y-m-d H:i:s') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
/**
 * Return the encoding country code for jquery datepicker
 * used for exemple in main/exercice/exercise_report.php
 */
function get_datepicker_langage_code() {
    $languaje   = 'en-GB';
    $platform_isocode = strtolower(api_get_language_isocode());

    // languages supported by jqgrid see files in main/inc/lib/javascript/jqgrid/js/i18n
    $datapicker_langs = array('af', 'ar', 'ar-DZ', 'az', 'bg', 'bs', 'ca', 'cs', 'cy-GB', 'da', 'de', 'el', 'en-AU', 'en-GB', 'en-NZ', 'eo', 'es', 'et', 'eu', 'fa', 'fi', 'fo', 'fr', 'fr-CH', 'gl', 'he', 'hi', 'hr', 'hu', 'hy', 'id', 'is', 'it', 'ja', 'ka', 'kk', 'km', 'ko', 'lb', 'lt', 'lv', 'mk', 'ml', 'ms', 'nl', 'nl-BE', 'no', 'pl', 'pt', 'pt-BR', 'rm', 'ro', 'ru', 'sk', 'sl', 'sq', 'sr', 'sr-SR', 'sv', 'ta', 'th', 'tj', 'tr', 'uk', 'vi', 'zh-CN', 'zh-HK', 'zh-TW');
    if (in_array($platform_isocode, $datapicker_langs)) {
        $languaje = $platform_isocode;
    }
    return $languaje;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
}
