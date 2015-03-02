<?php
/* For licensing terms, see /license.txt */
/**
 *    Code for Qti2 import integration.
 * @package chamilo.exercise
 * @author Ronny Velasquez
 * @version $Id: qti2.php  2010-03-12 12:14:25Z $
 */
/**
 * Code
 */
// name of the language file that needs to be included
$language_file = 'exercice';

// including the global Chamilo file
require_once '../inc/global.inc.php';

// including additional libraries
require_once 'exercise.class.php';
require_once 'question.class.php';
require_once 'answer.class.php';

// section (for the tabs)
$this_section = SECTION_COURSES;

// access restriction: only teachers are allowed here
if (!api_is_allowed_to_edit(null, true)) {
    api_not_allowed();
}

// the breadcrumbs
<<<<<<< HEAD
$interbreadcrumb[] = array("url" => "exercice.php", "name" => get_lang('Exercices'));
=======
$interbreadcrumb[]= array (
    "url" => api_get_path(WEB_CODE_PATH)."exercice/exercice.php?".api_get_cidreq(),
    "name" => get_lang('Exercices')
);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
$is_allowedToEdit = api_is_allowed_to_edit(null, true);

/**
 * This function displays the form for import of the zip file with qti2
 */
function ch_qti2_display_form()
{
<<<<<<< HEAD
    $name_tools = get_lang('ImportQtiQuiz');
    $form = '<div class="actions">';
    $form .= '<a href="exercice.php?show=test">'.Display :: return_icon(
        'back.png',
        get_lang('BackToExercisesList'),
        '',
        ICON_SIZE_MEDIUM
    ).'</a>';
    $form .= '</div>';
    $form_validator = new FormValidator('qti_upload', 'post', api_get_self()."?".api_get_cidreq(
    ), null, array('enctype' => 'multipart/form-data'));
    $form_validator->addElement('header', $name_tools);
    $form_validator->addElement('file', 'userFile', get_lang('DownloadFile'));
    $form_validator->addElement('style_submit_button', 'submit', get_lang('Send'), 'class="upload"');
    $form .= $form_validator->return_form();

    echo $form;
=======
	$name_tools = get_lang('ImportQtiQuiz');
	$form  = '<div class="actions">';
	$form .= '<a href="'.api_get_path(WEB_CODE_PATH).'exercice/exercice.php?show=test&'.api_get_cidreq().'">'.
            Display :: return_icon('back.png', get_lang('BackToExercisesList'),'',ICON_SIZE_MEDIUM).'</a>';
	$form .= '</div>';
    $formValidator = new FormValidator(
        'qti_upload',
        'post',
        api_get_self()."?".api_get_cidreq(),
        null,
        array('enctype' => 'multipart/form-data')
    );
    $formValidator->addElement('header', $name_tools);
    $formValidator->addElement('file', 'userFile', get_lang('DownloadFile'));
    $formValidator->addElement('style_submit_button', 'submit', get_lang('Send'), 'class="upload"');
    $form .= $formValidator->return_form();
	echo $form;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
}

/**
 * This function will import the zip file with the respective qti2
 * @param array $uploaded_file ($_FILES)
 */
function ch_qti2_import_file($array_file)
{
<<<<<<< HEAD
    $unzip = 0;
    $lib_path = api_get_path(LIBRARY_PATH);
    $process = FileManager::process_uploaded_file($array_file);
    if (preg_match('/\.zip$/i', $array_file['name'])) {
        // if it's a zip, allow zip upload
        $unzip = 1;
    }
    if ($process && $unzip == 1) {
        $main_path = api_get_path(SYS_CODE_PATH);
        require_once $main_path.'exercice/export/exercise_import.inc.php';
=======
	$unzip = 0;
	$lib_path = api_get_path(LIBRARY_PATH);
	require_once $lib_path.'fileUpload.lib.php';
	require_once $lib_path.'fileManage.lib.php';
	$process = process_uploaded_file($array_file);
	if (preg_match('/\.zip$/i', $array_file['name'])) {
		// if it's a zip, allow zip upload
		$unzip = 1;
	}

	if ($process && $unzip == 1) {
		$main_path = api_get_path(SYS_CODE_PATH);
		require_once $main_path.'exercice/export/exercise_import.inc.php';
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        require_once $main_path.'exercice/export/qti2/qti2_classes.php';

        $imported = import_exercise($array_file['name']);

        if ($imported) {
<<<<<<< HEAD
            header('Location: exercice.php?'.api_get_cidreq());
=======
        	header('Location: '.api_get_path(WEB_CODE_PATH).'exercice/exercice.php?'.api_get_cidreq());
            exit;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        } else {
            Display::display_error_message(get_lang('UplNoFileUploaded'));

            return false;
        }
    }
}

// Display header
Display::display_header(get_lang('ImportQtiQuiz'), 'Exercises');

// import file
if ((api_is_allowed_to_edit(null, true))) {
    if (isset($_POST['submit'])) {
        ch_qti2_import_file($_FILES['userFile']);
    }
}

// display qti form
ch_qti2_display_form();

// display the footer
Display::display_footer();
