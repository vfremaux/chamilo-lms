<?php
/* For licensing terms, see /license.txt */

/**
 *    This file allows creating new svg and png documents with an online editor.
 *
 * @package chamilo.document
 *
 * @author Juan Carlos Raña Trabado
 * @since 5/mar/2011
 */
/**
 * Code
 */
require_once '../../../inc/global.inc.php';

api_protect_course_script();
api_block_anonymous_users();
<<<<<<< HEAD

if (!isset($_GET['filename']) || !isset($_GET['filepath']) || !isset($_GET['dir']) || !isset($_GET['course_code']) || !isset($_GET['nano_group_id']) || !isset($_GET['nano_session_id']) || !isset($_GET['nano_user_id'])) {
    echo 'Error. Not allowed';
    exit;
}
=======

if (!isset($_GET['filename']) || !isset($_GET['filepath']) || !isset($_GET['dir']) || !isset($_GET['course_code']) || !isset($_GET['nano_group_id']) || !isset($_GET['nano_session_id']) || !isset($_GET['nano_user_id'])) {
    echo 'Error. Not allowed';
    exit;
}

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
if (!is_uploaded_file($_FILES['voicefile']['tmp_name'])) {
    exit;
}

//clean
$nano_user_id = Security::remove_XSS($_GET['nano_user_id']);
$nano_group_id = Security::remove_XSS($_GET['nano_group_id']);
$nano_session_id = Security::remove_XSS($_GET['nano_session_id']);

$filename = Security::remove_XSS($_GET['filename']);
$filename = urldecode($filename);
$filepath = Security::remove_XSS(urldecode($_GET['filepath']));
$dir = Security::remove_XSS(urldecode($_GET['dir']));

$course_code = Security::remove_XSS(urldecode($_GET['course_code']));
$_course = api_get_course_info($course_code);

$filename = trim($_GET['filename']);
$filename = Security::remove_XSS($filename);
$filename = Database::escape_string($filename);
<<<<<<< HEAD
$filename = api_replace_dangerous_char($filename, $strict = 'loose'); // or strict
$filename = FileManager::disable_dangerous_file($filename);
=======
$filename = replace_dangerous_char($filename, $strict = 'loose'); // or strict
$filename = disable_dangerous_file($filename);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

$title = trim(str_replace('_chnano_.', '.', $filename)); //hide nanogong wav tag at title
$title = str_replace('_', ' ', $title);

$documentPath = $filepath . $filename;

if ($nano_user_id != api_get_user_id() || api_get_user_id() == 0 || $nano_user_id == 0) {
    echo 'Not allowed';
    exit;
}

// Do not use here check Fileinfo method because return: text/plain

if (!file_exists($documentPath)) {
    //add document to disk
    move_uploaded_file($_FILES['voicefile']['tmp_name'], $documentPath);

<<<<<<< HEAD
// Check if there is enough space in the course to save the file
if (!DocumentManager::enough_space(filesize($_FILES['voicefile']['tmp_name']), DocumentManager::get_course_quota())) {
    die(get_lang('UplNotEnoughSpace'));
}

if (!file_exists($documentPath)) {
    //add document to disk
    move_uploaded_file($_FILES['voicefile']['tmp_name'], $documentPath);

=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    //add document to database
    $current_session_id = $nano_session_id;
    $groupId = $nano_group_id;
    $file_size = filesize($documentPath);
    $relativeUrlPath = $dir;
<<<<<<< HEAD
    $doc_id = FileManager::add_document($_course, $relativeUrlPath.$filename, 'file', filesize($documentPath), $title);
=======
    $doc_id = add_document($_course, $relativeUrlPath . $filename, 'file', filesize($documentPath), $title);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    api_item_property_update(
        $_course,
        TOOL_DOCUMENT,
        $doc_id,
        'DocumentAdded',
        $nano_user_id,
        $groupId,
        null,
        null,
        null,
        $current_session_id
    );
} else {
    return get_lang('FileExistRename');
<<<<<<< HEAD
}
=======
}
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
