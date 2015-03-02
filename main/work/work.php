<?php
/* For licensing terms, see /license.txt */
/**
 *	@package chamilo.work
 **/

<<<<<<< HEAD
/**
 * 	STUDENT PUBLICATIONS MODULE
 *
 * Note: for a more advanced module, see the dropbox tool.
 * This one is easier with less options.
 * This tool is better used for publishing things,
 * sending in assignments is better in the dropbox.
 *
 * GOALS
 * *****
 * Allow student to quickly send documents immediately visible on the Course
 *
 * The script does 5 things:
 *
 * 	1. Upload documents
 * 	2. Give them a name
 * 	3. Modify data about documents
 * 	4. Delete link to documents and simultaneously remove them
 * 	5. Show documents list to students and visitors
 *
 * On the long run, the idea is to allow sending realvideo . Which means only
 * establish a correspondence between RealServer Content Path and the user's
 * documents path.
 *
 *
 */

/* INIT SECTION */

$language_file = array('exercice', 'work', 'document', 'admin', 'gradebook');
=======
/* INIT SECTION */

use ChamiloSession as Session;

$language_file = array('exercice', 'work', 'document', 'admin', 'gradebook', 'tracking');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

require_once '../inc/global.inc.php';
$current_course_tool  = TOOL_STUDENTPUBLICATION;

api_protect_course_script(true);

require_once 'work.lib.php';

$course_id      = api_get_course_int_id();
$course_info    = api_get_course_info();
$user_id 	    = api_get_user_id();
$id_session     = api_get_session_id();

// Section (for the tabs)
$this_section = SECTION_COURSES;
$work_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$my_folder_data = get_work_data_by_id($work_id);

$curdirpath = '';
$htmlHeadXtra[] = api_get_jqgrid_js();
$htmlHeadXtra[] = to_javascript_work();

$_course = api_get_course_info();

/*	Constants and variables */

$tool_name = get_lang('StudentPublications');
$course_code = api_get_course_id();
$session_id = api_get_session_id();
$group_id = api_get_group_id();

$item_id 		        = isset($_REQUEST['item_id']) ? intval($_REQUEST['item_id']) : null;
$parent_id 		        = isset($_REQUEST['parent_id']) ? Database::escape_string($_REQUEST['parent_id']) : '';
$origin 		        = isset($_REQUEST['origin']) ? Security::remove_XSS($_REQUEST['origin']) : '';
$submitGroupWorkUrl     = isset($_REQUEST['submitGroupWorkUrl']) ? Security::remove_XSS($_REQUEST['submitGroupWorkUrl']) : '';
$title 			        = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
$description 	        = isset($_REQUEST['description']) ? $_REQUEST['description'] : '';
$uploadvisibledisabled  = isset($_REQUEST['uploadvisibledisabled']) ? Database::escape_string($_REQUEST['uploadvisibledisabled']) : $course_info['show_score'];
<<<<<<< HEAD

//directories management
$sys_course_path 	= api_get_path(SYS_COURSE_PATH);
$course_dir 		= $sys_course_path . $_course['path'];
=======
$course_dir 		= api_get_path(SYS_COURSE_PATH).$_course['path'];
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
$base_work_dir 		= $course_dir . '/work';
$link_target_parameter = ""; // e.g. "target=\"_blank\"";
$display_list_users_without_publication = isset($_GET['list']) && Security::remove_XSS($_GET['list']) == 'without' ? true : false;

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
//Download folder
if ($action == 'downloadfolder') {
    require 'downloadfolder.inc.php';
}

<<<<<<< HEAD
/*	More init stuff */

if (isset ($_POST['cancelForm']) && !empty ($_POST['cancelForm'])) {
    header('Location: '.api_get_self().'?origin='.$origin.'&amp;gradebook='.$gradebook);
    exit;
}

// If the POST's size exceeds 8M (default value in php.ini) the $_POST array is emptied
// If that case happens, we set $submitWork to 1 to allow displaying of the error message
// The redirection with header() is needed to avoid apache to show an error page on the next request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !sizeof($_POST)) {
    if (strstr($_SERVER['REQUEST_URI'], '?')) {
        header('Location: ' . $_SERVER['REQUEST_URI'] . '&submitWork=1');
        exit();
    } else {
        header('Location: ' . $_SERVER['REQUEST_URI'] . '?submitWork=1');
        exit();
    }
}

$group_id = api_get_group_id();

=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
$display_upload_form = false;
if ($action == 'upload_form') {
    $display_upload_form = true;
}

/*	Header */
if (!empty($_GET['gradebook']) && $_GET['gradebook'] == 'view') {
    $_SESSION['gradebook'] = Security::remove_XSS($_GET['gradebook']);
    $gradebook =	$_SESSION['gradebook'];
} elseif (empty($_GET['gradebook'])) {
    unset($_SESSION['gradebook']);
    $gradebook = '';
}

if (!empty($gradebook) && $gradebook == 'view') {
    $interbreadcrumb[] = array ('url' => '../gradebook/' . $_SESSION['gradebook_dest'],'name' => get_lang('ToolGradebook'));
}

if (!empty($group_id)) {
    $group_properties  = GroupManager::get_group_properties($group_id);
    $show_work = false;

    if (api_is_allowed_to_edit(false, true)) {
        $show_work = true;
    } else {
        // you are not a teacher
        $show_work = GroupManager::user_has_access($user_id, $group_id, GroupManager::GROUP_TOOL_WORK);
    }

    if (!$show_work) {
        api_not_allowed();
    }

    $interbreadcrumb[] = array ('url' => '../group/group.php', 'name' => get_lang('Groups'));
    $interbreadcrumb[] = array ('url' => '../group/group_space.php?gidReq='.$group_id, 'name' => get_lang('GroupSpace').' '.$group_properties['name']);
    $interbreadcrumb[] = array ('url' =>'work.php?gidReq='.$group_id,'name' => get_lang('StudentPublications'));
    $url_dir = 'work.php?&id=' . $work_id;
    if (!empty($my_folder_data)) {
        $interbreadcrumb[] = array ('url' => $url_dir, 'name' =>  $my_folder_data['title']);
    }

    if ($action == 'upload_form') {
        $interbreadcrumb[] = array ('url' => 'work.php','name' => get_lang('UploadADocument'));
    }

    if ($action == 'create_dir') {
        $interbreadcrumb[] = array ('url' => 'work.php','name' => get_lang('CreateAssignment'));
    }
} else {
    if ($origin != 'learnpath') {

        if (isset($_GET['id']) && !empty($_GET['id']) || $display_upload_form || $action == 'settings' || $action == 'create_dir') {
            $interbreadcrumb[] = array ('url' => 'work.php', 'name' => get_lang('StudentPublications'));
        } else {
            $interbreadcrumb[] = array ('url' => '#', 'name' => get_lang('StudentPublications'));
        }

        if (!empty($my_folder_data)) {
            $interbreadcrumb[] = array ('url' => 'work.php?id=' . $work_id, 'name' =>  $my_folder_data['title']);
        }

        if ($action == 'upload_form') {
            $interbreadcrumb[] = array ('url' => '#', 'name' => get_lang('UploadADocument'));
        }
        if ($action == 'settings') {
            $interbreadcrumb[] = array ('url' => '#', 'name' => get_lang('EditToolOptions'));
        }
        if ($action == 'create_dir') {
            $interbreadcrumb[] = array ('url' => '#','name' => get_lang('CreateAssignment'));
        }
    }
}

// Stats
event_access_tool(TOOL_STUDENTPUBLICATION);

$is_allowed_to_edit = api_is_allowed_to_edit();
$student_can_edit_in_session = api_is_allowed_to_session_edit(false, true);

/*	Display links to upload form and tool options */
if (!in_array($action, array('add', 'create_dir'))) {
    $token = Security::get_token();
}
$courseInfo = api_get_course_info();

<<<<<<< HEAD
display_action_links($work_id, $curdirpath, $action);

// for teachers
=======
$currentUrl = api_get_path(WEB_CODE_PATH).'work/work.php?'.api_get_cidreq();
$content = null;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

// For teachers
switch ($action) {
    case 'settings':
        //if posts
        if ($is_allowed_to_edit && !empty($_POST['changeProperties'])) {
<<<<<<< HEAD
            // Changing the tool setting: default visibility of an uploaded document
            // @todo
            $query = "UPDATE ".$main_course_table." SET show_score='" . $uploadvisibledisabled . "' WHERE code='" . api_get_course_id() . "'";
            $res = Database::query($query);

            /**
             * Course data are cached in session so we need to update both the database
             * and the session data
             */
            $_course['show_score'] = $uploadvisibledisabled;
            Session::write('_course', $course);

            // changing the tool setting: is a student allowed to delete his/her own document
            // database table definition
            $table_course_setting = Database :: get_course_table(TOOL_COURSE_SETTING);

            // counting the number of occurrences of this setting (if 0 => add, if 1 => update)
            $query = "SELECT * FROM " . $table_course_setting . " WHERE c_id = $course_id AND variable = 'student_delete_own_publication'";
            $result = Database::query($query);
            $number_of_setting = Database::num_rows($result);

            if ($number_of_setting == 1) {
                $query = "UPDATE " . $table_course_setting . " SET value='" . Database::escape_string($_POST['student_delete_own_publication']) . "'
                        WHERE variable='student_delete_own_publication' AND c_id = $course_id";
                Database::query($query);
            } else {
                $query = "INSERT INTO " . $table_course_setting . " (c_id, variable, value, category) VALUES
                ($course_id, 'student_delete_own_publication','" . Database::escape_string($_POST['student_delete_own_publication']) . "','work')";
                Database::query($query);
            }
            Display::display_confirmation_message(get_lang('Saved'));
=======
            updateSettings($course, $_POST['show_score'], $_POST['student_delete_own_publication']);
            Session::write('message', Display::return_message(get_lang('Saved'), 'success'));
            header('Location: '.$currentUrl);
            exit;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        }
        $studentDeleteOwnPublication = api_get_course_setting('student_delete_own_publication') == 1 ? 1 : 0;
        /*	Display of tool options */
        $content = settingsForm(
            array(
                'show_score' => $course_info['show_score'],
                'student_delete_own_publication' =>  $studentDeleteOwnPublication
            )
        );
        break;
    case 'add':
<<<<<<< HEAD
        //$check = Security::check_token('post');
        //show them the form for the directory name

        if ($is_allowed_to_edit && in_array($action, array('create_dir','add'))) {
            //create the form that asks for the directory name
            $form = new FormValidator('form1', 'post', api_get_self().'?action=create_dir&'. api_get_cidreq());

            $form->addElement('header', get_lang('CreateAssignment').$token);
            $form->addElement('hidden', 'action', 'add');
            $form->addElement('hidden', 'curdirpath', Security :: remove_XSS($curdirpath));
            // $form->addElement('hidden', 'sec_token', $token);

            $form->addElement('text', 'new_dir', get_lang('AssignmentName'));
            $form->addRule('new_dir', get_lang('ThisFieldIsRequired'), 'required');

            $form->add_html_editor('description', get_lang('Description'), false, false, getWorkDescriptionToolbar());

            $form->addElement('advanced_settings', 'add_work', get_lang('AdvancedParameters'));
            $form->addElement('html', '<div id="add_work_options" style="display: none;">');

            // QualificationOfAssignment
            $form->addElement('text', 'qualification_value', get_lang('QualificationNumeric'));

            if (Gradebook::is_active()) {
                $form->addElement('checkbox', 'make_calification', null, get_lang('MakeQualifiable'), array('id' =>'make_calification_id', 'onclick' => "javascript: if(this.checked){document.getElementById('option1').style.display='block';}else{document.getElementById('option1').style.display='none';}"));
=======
    case 'create_dir':
        if (!$is_allowed_to_edit) {
            api_not_allowed();
        }
        $form = new FormValidator('form1', 'post', api_get_path(WEB_CODE_PATH).'work/work.php?action=create_dir&'. api_get_cidreq());
        $form->addElement('header', get_lang('CreateAssignment'));
        $form->addElement('hidden', 'action', 'add');
        $defaults = isset($_POST) ? $_POST : array();
        $form = getFormWork($form, $defaults);
        $form->addElement('style_submit_button', 'submit', get_lang('CreateDirectory'));

        if ($form->validate()) {

            $result = addDir($_POST, $user_id, $_course, $group_id, $id_session);
            if ($result) {
                $message = Display::return_message(get_lang('DirectoryCreated'), 'success');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
            } else {
                $message = Display::return_message(get_lang('CannotCreateDir'), 'error');
            }

            Session::write('message', $message);
            header('Location: '.$currentUrl);
            exit;
        } else {
            $content = $form->return_form();
        }
        break;
    case 'delete_dir':
        if ($is_allowed_to_edit) {
            $work_to_delete = get_work_data_by_id($_REQUEST['id']);
            $result = deleteDirWork($_REQUEST['id']);
            if ($result) {
                $message = Display::return_message(get_lang('DirDeleted') . ': '.$work_to_delete['title'], 'success');
                Session::write('message', $message);
                header('Location: '.$currentUrl);
                exit;
            }
        }
        break;
    case 'move':
        /*	Move file form request */
        if ($is_allowed_to_edit) {
            if (!empty($item_id)) {
                $content = generateMoveForm($item_id, $curdirpath, $course_info, $group_id, $session_id);
            }
        }
        break;
    case 'move_to':
        /* Move file command */
        if ($is_allowed_to_edit) {
            $move_to_path = get_work_path($_REQUEST['move_to_id']);

            if ($move_to_path==-1) {
                $move_to_path = '/';
            } elseif (substr($move_to_path, -1, 1) != '/') {
                $move_to_path = $move_to_path .'/';
            }

            // Security fix: make sure they can't move files that are not in the document table
            if ($path = get_work_path($item_id)) {
                if (move($course_dir.'/'.$path, $base_work_dir . $move_to_path)) {
                    // Update db
<<<<<<< HEAD
                    update_work_url($item_id, 'work' . $move_to_path, $_REQUEST['move_to_id']);
                    api_item_property_update($_course, 'work', $_REQUEST['move_to_id'], 'FolderUpdated', $user_id);

                    Display :: display_confirmation_message(get_lang('DirMv'));
=======
                    updateWorkUrl($item_id, 'work' . $move_to_path, $_REQUEST['move_to_id']);
                    api_item_property_update($_course, 'work', $_REQUEST['move_to_id'], 'FolderUpdated', $user_id);

                    $message = Display::return_message(get_lang('DirMv'), 'success');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
                } else {
                    $message = Display::return_message(get_lang('Impossible'), 'error');
                }
            } else {
                $message = Display::return_message(get_lang('Impossible'), 'error');
            }
            Session::write('message', $message);
            header('Location: '.$currentUrl);
            exit;
        }
<<<<<<< HEAD

        /*	MAKE VISIBLE WORK COMMAND */
        if ($is_allowed_to_edit && $action == 'make_visible') {
            if (!empty($item_id)) {
                if (isset($item_id) && $item_id == 'all') {
                } else {
                    $sql = "UPDATE " . $work_table . "	SET accepted = 1 WHERE c_id = $course_id AND id = '" . $item_id . "'";
                    Database::query($sql);
                    api_item_property_update($course_info, 'work', $item_id, 'visible', api_get_user_id());
                    Display::display_confirmation_message(get_lang('FileVisible'));
                }
            }
        }

        if ($is_allowed_to_edit && $action == 'make_invisible') {

            /*	MAKE INVISIBLE WORK COMMAND */
            if (!empty($item_id)) {
                if (isset($item_id) && $item_id == 'all') {
                } else {
                    $sql = "UPDATE  " . $work_table . " SET accepted = 0
                            WHERE c_id = $course_id AND id = '" . $item_id . "'";
                    Database::query($sql);
                    api_item_property_update($course_info, 'work', $item_id, 'invisible', api_get_user_id());
                    Display::display_confirmation_message(get_lang('FileInvisible'));
                }
            }
        }

        /*	Delete dir command */

        if ($is_allowed_to_edit && !empty($_REQUEST['delete_dir'])) {
            $delete_dir_id = intval($_REQUEST['delete_dir']);
            $locked = api_resource_is_locked_by_gradebook($delete_dir_id, LINK_STUDENTPUBLICATION);

            if ($locked == false) {

                $work_to_delete = get_work_data_by_id($delete_dir_id);
                del_dir($delete_dir_id);

                // gets calendar_id from student_publication_assigment
                $sql = "SELECT add_to_calendar FROM $TSTDPUBASG WHERE c_id = $course_id AND publication_id ='$delete_dir_id'";
                $res = Database::query($sql);
                $calendar_id = Database::fetch_row($res);

                // delete from agenda if it exists
                if (!empty($calendar_id[0])) {
                    $t_agenda   = Database::get_course_table(TABLE_AGENDA);
                    $sql = "DELETE FROM $t_agenda WHERE c_id = $course_id AND id ='".$calendar_id[0]."'";
                    Database::query($sql);
                }
                $sql = "DELETE FROM $TSTDPUBASG WHERE c_id = $course_id AND publication_id ='$delete_dir_id'";
                Database::query($sql);

                $link_info = is_resource_in_course_gradebook(api_get_course_id(), 3 , $delete_dir_id, api_get_session_id());
                $link_id = $link_info['id'];
                if ($link_info !== false) {
                    remove_resource_from_course_gradebook($link_id);
                }
                Display :: display_confirmation_message(get_lang('DirDeleted') . ': '.$work_to_delete['title']);
            } else {
                Display::display_warning_message(get_lang('ResourceLockedByGradebook'));
            }
        }

        /*	DELETE WORK COMMAND */

        if ($action == 'delete' && $item_id) {

            $file_deleted = false;
            $is_author = user_is_author($item_id);
            $work_data = get_work_data_by_id($item_id);
            $locked = api_resource_is_locked_by_gradebook($work_data['parent_id'], LINK_STUDENTPUBLICATION);

            if (($is_allowed_to_edit && $locked == false) || ($locked == false AND $is_author && api_get_course_setting('student_delete_own_publication') == 1 && $work_data['qualificator_id'] == 0)) {
                //we found the current user is the author
                $queryString1 	= "SELECT url, contains_file FROM ".$work_table." WHERE c_id = $course_id AND id = $item_id";
                $result1 		= Database::query($queryString1);
                $row 			= Database::fetch_array($result1);


                if (Database::num_rows($result1) > 0) {
                    $queryString2 	= "UPDATE " . $work_table . "  SET active = 2 WHERE c_id = $course_id AND id = $item_id";
                    $queryString3 	= "DELETE FROM  ".$TSTDPUBASG ." WHERE c_id = $course_id AND publication_id = $item_id";
                    Database::query($queryString2);
                    Database::query($queryString3);

                    api_item_property_update($_course, 'work', $item_id, 'DocumentDeleted', $user_id);
                    $work = $row['url'];

                    if ($row['contains_file'] == 1) {
                        if (!empty($work)) {
                            if (api_get_setting('permanently_remove_deleted_files') == 'true') {
                                my_delete($currentCourseRepositorySys.'/'.$work);
                                Display::display_confirmation_message(get_lang('TheDocumentHasBeenDeleted'));
                                $file_deleted = true;
                            } else {
                                $extension = pathinfo($work, PATHINFO_EXTENSION);
                                $new_dir = $work.'_DELETED_'.$item_id.'.'.$extension;

                                if (file_exists($currentCourseRepositorySys.'/'.$work)) {
                                    rename($currentCourseRepositorySys.'/'.$work, $currentCourseRepositorySys.'/'.$new_dir);
                                    Display::display_confirmation_message(get_lang('TheDocumentHasBeenDeleted'));
                                    $file_deleted = true;
                                }
                            }
                        }
                    } else {
                        $file_deleted = true;
                    }
                }
            }

            if (!$file_deleted) {
                Display::display_error_message(get_lang('YouAreNotAllowedToDeleteThisDocument'));
            }
        }

=======
        break;
    case 'list':
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        /*	Display list of student publications */
        if (!empty($my_folder_data['description'])) {
            $content = '<p><div><strong>'.
                get_lang('Description').':</strong><p>'.Security::remove_XSS($my_folder_data['description'], STUDENT).
                '</p></div></p>';
        }
        if (api_is_allowed_to_edit()) {
            // Work list
            $content .= '<div class="row">';
            $content .= '<div class="span9">';
            $content .= showTeacherWorkGrid();
            $content .= '</div>';
            $content .= '<div class="span3">';
            $content .= showStudentList($work_id);
            $content .= '</div>';
        } else {
            $content .= showStudentWorkGrid();
        }
    break;
}

Display :: display_header(null);
Display::display_introduction_section(TOOL_STUDENTPUBLICATION);

if ($origin == 'learnpath') {
    echo '<div style="height:15px">&nbsp;</div>';
}

display_action_links($work_id, $curdirpath, $action);

$message = Session::read('message');
echo $message;
Session::erase('message');
echo $content;

Display::display_footer();
