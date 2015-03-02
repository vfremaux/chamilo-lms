<?php
/* For licensing terms, see /license.txt */

<<<<<<< HEAD
=======
require_once '../../../inc/global.inc.php';
require_once api_get_path(LIBRARY_PATH).'fileUpload.lib.php';
require_once api_get_path(LIBRARY_PATH).'document.lib.php';

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
// Add security from Chamilo
api_protect_course_script();
api_block_anonymous_users();

# Save the audio to a URL-accessible directory for playback.
parse_str($_SERVER['QUERY_STRING'], $params);

if (isset($params['waminame']) && isset($params['wamidir']) && isset($params['wamiuserid'])) {
<<<<<<< HEAD
    $waminame = $params['waminame'];
    $wamidir = $params['wamidir'];
=======
    $waminame   = $params['waminame'];
    $wamidir    = $params['wamidir'];
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    $wamiuserid = $params['wamiuserid'];
} else {
    api_not_allowed();
    die();
}

if ($wamiuserid != api_get_user_id() || api_get_user_id() == 0 || $wamiuserid == 0) {
    api_not_allowed();
    die();
}
<<<<<<< HEAD

=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

//clean
$waminame = Security::remove_XSS($waminame);
$waminame = Database::escape_string($waminame);
<<<<<<< HEAD
$waminame = addslashes(trim($waminame));
$waminame = api_replace_dangerous_char($waminame, 'strict');
$waminame = FileManager::disable_dangerous_file($waminame);
$wamidir = Security::remove_XSS($wamidir);

=======
$waminame = replace_dangerous_char($waminame, 'strict');
$waminame = disable_dangerous_file($waminame);
$wamidir  = Security::remove_XSS($wamidir);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
$content = file_get_contents('php://input');

if (empty($content)) {
    exit;
}

$ext = explode('.', $waminame);
$ext = strtolower($ext[sizeof($ext) - 1]);

if ($ext != 'wav') {
    die();
}

//Do not use here check Fileinfo method because return: text/plain

<<<<<<< HEAD
$dirBaseDocuments = api_get_path(SYS_COURSE_PATH).$_course['path'].'/document';
$saveDir = $dirBaseDocuments.$wamidir;
$current_session_id = api_get_session_id();
$groupId = $_SESSION['_gid'];

//avoid duplicates
$waminame_to_save = $waminame;
$title_to_save = str_replace('_', ' ', $waminame);
$waminame_noex = basename($waminame, ".wav");
=======
$dirBaseDocuments   = api_get_path(SYS_COURSE_PATH).$_course['path'].'/document';
$saveDir            = $dirBaseDocuments.$wamidir;
$current_session_id = api_get_session_id();
$groupId            = api_get_group_id();

//avoid duplicates
$waminame_to_save = $waminame;
$title_to_save    = str_replace('_', ' ', $waminame);
$waminame_noex    = basename($waminame, ".wav");
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
if (file_exists($saveDir.'/'.$waminame_noex.'.'.$ext)) {
    $i = 1;
    while (file_exists($saveDir.'/'.$waminame_noex.'_'.$i.'.'.$ext)) {
        $i++;
    }
    $waminame_to_save = $waminame_noex.'_'.$i.'.'.$ext;
<<<<<<< HEAD
    $title_to_save = $waminame_noex.'_'.$i.'.'.$ext;
    $title_to_save = str_replace('_', ' ', $title_to_save);
=======
    $title_to_save    = $waminame_noex.'_'.$i.'.'.$ext;
    $title_to_save    = str_replace('_', ' ', $title_to_save);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
}

$documentPath = $saveDir.'/'.$waminame_to_save;
<<<<<<< HEAD

//make a temporal file for get the file size
$tmpfname = tempnam("/tmp", "CTF");
$handle = fopen($tmpfname, "w");
fwrite($handle, $content);
fclose($handle);
// Check if there is enough space in the course to save the file
if (!DocumentManager::enough_space(filesize($tmpfname), DocumentManager::get_course_quota())) {
    unlink($tmpfname);
    die(get_lang('UplNotEnoughSpace'));
}
//erase temporal file
unlink($tmpfname);


=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
// Add to disk
$fh = fopen($documentPath, 'w') or die("can't open file");
fwrite($fh, $content);
fclose($fh);

<<<<<<< HEAD
error_log($documentPath);
=======

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
$fileInfo = pathinfo($documentPath);
$courseInfo = api_get_course_info();

$file = array(
    'file' => array(
        'name' => $fileInfo['basename'],
        'tmp_name' => $documentPath,
        'size' => filesize($documentPath),
        'from_file' => true
    )
);

$output = true;
$documentData = DocumentManager::upload_document($file, $wamidir, null, null, 0, 'overwrite', false, $output);

if (!empty($documentData)) {
    $newDocId = $documentData['id'];
    $newMp3DocumentId = DocumentManager::addAndConvertWavToMp3($documentData, $courseInfo, api_get_user_id());

    if ($newMp3DocumentId) {
        $newDocId = $newMp3DocumentId;
    }

    if (isset($_REQUEST['lp_item_id']) && !empty($_REQUEST['lp_item_id'])) {
        $lpItemId = $_REQUEST['lp_item_id'];
        /** @var learnpath $lp */
        $lp = isset($_SESSION['oLP']) ? $_SESSION['oLP'] : null;

        if (!empty($lp)) {
            $lp->set_modified_on();
            $lpItem = new learnpathItem($lpItemId);
            $lpItem->add_audio_from_documents($newDocId);
        }
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
