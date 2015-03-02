<?php
/* For licensing terms, see /license.txt */
/**
 * @package chamilo.calendar
 */

/**
 * INIT SECTION
 */
use \ChamiloSession as Session;

// name of the language file that needs to be included
$language_file = array('agenda', 'group');

// use anonymous mode when accessing this course tool
$use_anonymous = true;
require_once '../inc/global.inc.php';

// Functions for the agenda tool
require 'agenda.inc.php';

$current_course_tool = TOOL_CALENDAR_EVENT;
$course_info = api_get_course_info();

if (!empty($course_info)) {
    api_protect_course_script(true);
}

$action = isset($_GET['action']) ? $_GET['action'] : null;
$origin = isset($_GET['origin']) ? $_GET['origin'] : null;

$this_section = SECTION_COURSES;
$url = null;
if (empty($action)) {
    if (!empty($course_info)) {
        $url = api_get_path(WEB_CODE_PATH).'calendar/agenda_js.php?type=course'.'&'.api_get_cidreq();
    } else {
        $url = api_get_path(WEB_CODE_PATH).'calendar/agenda_js.php?';
    }
    header("Location: $url");
    exit;
}

<<<<<<< HEAD
/*
  TREATING THE PARAMETERS
  1. viewing month only or everything
  2. sort ascending or descending
  3. showing or hiding the send-to-specific-groups-or-users form
  4. filter user or group
 */
// 3. showing or hiding the send-to-specific-groups-or-users form
$setting_allow_individual_calendar = true;
if (empty($_POST['To']) and empty($_SESSION['allow_individual_calendar'])) {
    $_SESSION['allow_individual_calendar'] = "hide";
}
$allow_individual_calendar_status = $_SESSION['allow_individual_calendar'];
if (!empty($_POST['To']) and ($allow_individual_calendar_status == "hide")) {
    $_SESSION['allow_individual_calendar'] = "show";
}
if (!empty($_GET['sort']) and ($allow_individual_calendar_status == "show")) {
    $_SESSION['allow_individual_calendar'] = "hide";
}

// 4. filter user or group
if (!empty($_GET['user']) or !empty($_GET['group'])) {
    $_SESSION['user'] = (int)$_GET['user'];
    $_SESSION['group'] = (int)$_GET['group'];
}
if ((!empty($_GET['user']) and $_GET['user'] == "none") or (!empty($_GET['group']) and $_GET['group'] == "none")) {
    Session::erase("user");
    Session::erase("group");
}

$group_id = api_get_group_id();

//It comes from the group tools. If it's define it overwrites $_SESSION['group']

$htmlHeadXtra[] = to_javascript();
$htmlHeadXtra[] = user_group_filter_javascript();

// setting the name of the tool
$nameTools = get_lang('Agenda'); // language variable in trad4all.inc.php
// showing the header if we are not in the learning path, if we are in
// the learning path, we do not include the banner so we have to explicitly
// include the stylesheet, which is normally done in the header
if (!empty($group_id)) {
    $group_properties = GroupManager :: get_group_properties($group_id);
    $interbreadcrumb[] = array("url" => "../group/group.php", "name" => get_lang('Groups'));
    $interbreadcrumb[] = array(
        "url" => "../group/group_space.php?gidReq=".$group_id,
        "name" => get_lang('GroupSpace').' '.$group_properties['name']
    );
    Display::display_header($nameTools, 'Agenda');
} elseif (empty($origin) or $origin != 'learnpath') {
    Display::display_header($nameTools, 'Agenda');
=======
/* 	Resource linker */
$_SESSION['source_type'] = 'Agenda';
require_once '../resourcelinker/resourcelinker.inc.php';
require_once api_get_path(LIBRARY_PATH).'fileUpload.lib.php';
$group_id = api_get_group_id();
$eventId = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
$type = $event_type = isset($_GET['type']) ? $_GET['type'] : null;

$htmlHeadXtra[] = "<script>

function plus_repeated_event() {
    if (document.getElementById('options2').style.display == 'none') {
        document.getElementById('options2').style.display = 'block';
    } else {
        document.getElementById('options2').style.display = 'none';
    }
}
    $(function() {
        var checked = $('input[name=repeat]').attr('checked');
        if (checked) {
            $('#options2').show();
        }
    });
</script>
";

// setting the name of the tool
$nameTools = get_lang('Agenda');

event_access_tool(TOOL_CALENDAR_EVENT);

// permission stuff - also used by loading from global in agenda.inc.php
$is_allowed_to_edit = api_is_allowed_to_edit(false, true) OR (api_get_course_setting('allow_user_edit_agenda') && !api_is_anonymous());
$agenda = new Agenda();
$agenda->type = $type;
$actions = $agenda->displayActions('calendar');

if ($type == 'fromjs') {
    $id_list = explode('_', $eventId);
    $eventId = $id_list[1];
    $event_type = $id_list[0];
}

if (!api_is_allowed_to_edit(null, true) && $event_type == 'course') {
    api_not_allowed(true);
}
if ($event_type == 'course') {
    $agendaUrl = api_get_path(WEB_CODE_PATH).'calendar/agenda_js.php?'.api_get_cidreq().'&type=course';
} else {
    $agendaUrl = api_get_path(WEB_CODE_PATH).'calendar/agenda_js.php?&type='.$event_type;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
}
$course_info = api_get_course_info();
$agenda->type = $event_type;

<<<<<<< HEAD
/*
  TRACKING
 */
event_access_tool(TOOL_CALENDAR_EVENT);

/*   			SETTING SOME VARIABLES
 */
// Variable definitions
// Defining the shorts for the days. We use camelcase because these are arrays of language variables
$DaysShort = api_get_week_days_short();
// Defining the days of the week to allow translation of the days. We use camelcase because these are arrays of language variables
$DaysLong = api_get_week_days_long();
// Defining the months of the year to allow translation of the months. We use camelcase because these are arrays of language variables
$MonthsLong = api_get_months_long();

// Database table definitions
$TABLEAGENDA = Database::get_course_table(TABLE_AGENDA);
$TABLE_ITEM_PROPERTY = Database::get_course_table(TABLE_ITEM_PROPERTY);
$tbl_user = Database::get_main_table(TABLE_MAIN_USER);
$tbl_courseUser = Database::get_main_table(TABLE_MAIN_COURSE_USER);
$tbl_group = Database::get_course_table(TABLE_GROUP);
$tbl_groupUser = Database::get_course_table(TABLE_GROUP_USER);

/*   			ACCESS RIGHTS */
// permission stuff - also used by loading from global in agenda.inc.php
$is_allowed_to_edit = api_is_allowed_to_edit(false, true) OR (api_get_course_setting(
    'allow_user_edit_agenda'
) && !api_is_anonymous());
=======
$message = null;
$content = null;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

if (api_is_allowed_to_edit(false, true) OR
    (api_get_course_setting('allow_user_edit_agenda') &&
    !api_is_anonymous() &&
    api_is_allowed_to_session_edit(false, true)) OR
    GroupManager::user_has_access(api_get_user_id(), $group_id,  GroupManager::GROUP_TOOL_CALENDAR) &&
    GroupManager::is_tutor_of_group(api_get_user_id(), $group_id)
) {
    switch ($action) {
        case 'add':
            $actionName = get_lang('Add');
            $form = $agenda->getForm(array('action' => 'add'));

            if ($form->validate()) {
                $values = $form->getSubmitValues();

                $sendEmail = isset($values['add_announcement']) ? true : false;
                $allDay = isset($values['all_day']) ? 'true' : 'false';

                $sendAttachment = isset($_FILES['user_upload']) ? true : false;
                $attachment = $sendAttachment ? $_FILES['user_upload'] : null;
                $attachmentComment = isset($values['file_comment']) ? $values['file_comment'] : null;

                $startDate = $values['date_range_start'];
                $endDate = $values['date_range_end'];

                $eventId = $agenda->add_event(
                    $startDate,
                    $endDate,
                    $allDay,
                    $values['title'],
                    $values['content'],
                    $values['users_to_send'],
                    $sendEmail,
                    null,
                    $attachment,
                    $attachmentComment
                );

                if (!empty($values['repeat']) && !empty($eventId)) {
                    // End date is always set as 23:59:59
                    $endDate = substr($values['repeat_end_day'], 0, 10).' 23:59:59';
                    $agenda->addRepeatedItem(
                        $eventId,
                        $values['repeat_type'],
                        $endDate,
                        $values['users_to_send']
                    );
                }
                $message = Display::return_message(get_lang('AddSuccess'), 'confirmation');
                if ($sendEmail) {
                    $message .= Display::return_message(get_lang('AdditionalMailWasSentToSelectedUsers'), 'confirmation');
                }
                Session::write('message', $message);
                header("Location: $agendaUrl");
                exit;
            } else {
                $content = $form->return_form();
            }
            break;
        case 'edit':
            $actionName = get_lang('Edit');
            $event = $agenda->get_event($eventId);

            if (empty($event)) {
                api_not_allowed(true);
            }

            $event['action'] = 'edit';
            $event['id'] = $eventId;

<<<<<<< HEAD
if (!empty($_GET['year'])) {
    $select_year = (int)$_GET['year'];
}
if (!empty($_GET['month'])) {
    $select_month = (int)$_GET['month'];
}
if (!empty($_GET['day'])) {
    $select_day = (int)$_GET['day'];
}
=======
            $form = $agenda->getForm($event);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

            if ($form->validate()) {
                $values = $form->getSubmitValues();

                $allDay = isset($values['all_day']) ? 'true' : 'false';
                $startDate = $values['date_range_start'];
                $endDate = $values['date_range_end'];

                $sendAttachment = isset($_FILES['user_upload']) ? true : false;
                $attachment = $sendAttachment ? $_FILES['user_upload'] : null;
                $attachmentComment = isset($values['file_comment']) ? $values['file_comment'] : null;

<<<<<<< HEAD
if (api_is_allowed_to_edit(false, true) OR
    (api_get_course_setting('allow_user_edit_agenda') && !api_is_anonymous()) && api_is_allowed_to_session_edit(
        false,
        true
    ) OR
    GroupManager::user_has_access(
        api_get_user_id(),
        $group_id,
        GroupManager::GROUP_TOOL_CALENDAR
    ) && GroupManager::is_tutor_of_group(api_get_user_id(), $group_id)
) {
    echo display_courseadmin_links();
}
=======
                // This is a sub event. Delete the current and create another BT#7803
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

                if (!empty($event['parent_event_id'])) {
                    $agenda->delete_event($eventId);

                    $eventId = $agenda->add_event(
                        $startDate,
                        $endDate,
                        $allDay,
                        $values['title'],
                        $values['content'],
                        $values['users_to_send'],
                        false,
                        null,
                        $attachment,
                        $attachmentComment
                    );

                    $message = Display::return_message(get_lang('Updated'), 'confirmation');
                    Session::write('message', $message);
                    header("Location: $agendaUrl");
                    exit;
                }

                // Editing normal event.

                $agenda->edit_event(
                    $eventId,
                    $startDate,
                    $endDate,
                    $allDay,
                    $values['title'],
                    $values['content'],
                    $values['users_to_send'],
                    $attachment,
                    $attachmentComment
                );

                if (!empty($values['repeat']) && !empty($eventId)) {
                    // End date is always set as 23:59:59
                    $endDate = substr($values['repeat_end_day'], 0, 10).' 23:59:59';
                    $agenda->addRepeatedItem(
                        $eventId,
                        $values['repeat_type'],
                        $endDate,
                        $values['users_to_send']
                    );
                }

                $deleteAttachment = isset($values['delete_attachment']) ? true : false;

<<<<<<< HEAD
if (api_is_allowed_to_edit(false, true) or
    (
        api_get_course_setting('allow_user_edit_agenda') &&
        !api_is_anonymous() &&
        api_is_allowed_to_session_edit(false, true)
    ) or
    GroupManager::user_has_access(api_get_user_id(), $group_id, GroupManager::GROUP_TOOL_CALENDAR) &&
    GroupManager::is_tutor_of_group(api_get_user_id(), $group_id)
) {
    switch ($action) {
        case 'add':
            if (isset($_POST['submit_event']) && $_POST['submit_event']) {

                $startDate = Text::return_datetime_from_array($_POST['start_date']);
                $endDate = Text::return_datetime_from_array($_POST['end_date']);
                $repeatEndDay = Text::return_datetime_from_array($_POST['repeat_end_day']);

                $fileComment = isset($_POST['file_comment']) ? $_POST['file_comment'] : null;
                $fileAttachment = isset($_FILES['user_upload']) ? $_FILES['user_upload'] : null;
                $agenda = new Agenda();
                $agenda->setType('course');

                $repeatSettings = array();

                if (!empty($_POST['repeat'])) {
                    $repeatSettings = array(
                        'repeat_type' => $_POST['repeat_type'],
                        'repeat_end' => $repeatEndDay
                    );
                    /*
                    $res = agenda_add_repeat_item(
                        $course_info,
                        $id,
                        $_POST['repeat_type'],
                        $repeatEndDay,
                        $_POST['users'],
                        $safe_file_comment
                    );*/
                }

                $id = $agenda->add_event(
                    $startDate,
                    $endDate,
                    null,
                    null,
                    $_POST['title'],
                    $_POST['content'],
                    $_POST['users'],
                    false,
                    null,
                    array('comment' => $fileComment, 'file' => $fileAttachment),
                    $repeatSettings
                );
                Display::display_confirmation_message(get_lang('AddSuccess'));
            } else {
                show_add_form();
            }
            break;
        case "announce":
            //copying the agenda item into an announcement
            if (!(api_is_course_coach() && !api_is_element_in_the_session(TOOL_AGENDA, $event_id))) {
                // a coach can only delete an element belonging to his session
                $ann_id = store_agenda_item_as_announcement($event_id);
                $tool_group_link = (isset($_SESSION['toolgroup']) ? '&toolgroup='.$_SESSION['toolgroup'] : '');
                Display::display_normal_message(
                    get_lang(
                        'CopiedAsAnnouncement'
                    ).'&nbsp;<a href="../announcements/announcements.php?id='.$ann_id.$tool_group_link.'">'.get_lang(
                        'NewAnnouncement'
                    ).'</a>',
                    false
                );
=======
                if ($deleteAttachment && isset($event['attachment']) && !empty($event['attachment'])) {
                    $agenda->deleteAttachmentFile(
                        $event['attachment']['id'],
                        $agenda->course
                    );
                }

                $message = Display::return_message(get_lang('Updated'), 'confirmation');
                Session::write('message', $message);
                header("Location: $agendaUrl");
                exit;
            } else {
                $content = $form->return_form();

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
            }
            break;
        case 'importical':
            $form = $agenda->getImportCalendarForm();
            $content = $form->return_form();

            if ($form->validate()) {
                $ical_name = $_FILES['ical_import']['name'];
                $ical_type = $_FILES['ical_import']['type'];
                $ext = substr($ical_name, (strrpos($ical_name, ".") + 1));

                if ($ext === 'ics' || $ext === 'ical' || $ext === 'icalendar' || $ext === 'ifb') {
                    $result = $agenda->importEventFile($course_info, $_FILES['ical_import']);
                    $is_ical = true;
                } else {
                    $is_ical = false;
                }

                if (!$is_ical) {
                    $message = Display::return_message(get_lang('IsNotiCalFormatFile'), 'error');
                    $form = $agenda->getImportCalendarForm();
                    $content = $form->return_form();
                    break;
                } else {
                    $message = Display::return_message(get_lang('AddSuccess'), 'error');
                    $content = $result;
                }
                Session::write('message', $message);
            }
            break;
        case "delete":
<<<<<<< HEAD
            if (!(api_is_course_coach() && !api_is_element_in_the_session(TOOL_AGENDA, $event_id))) {
                // a coach can only delete an element belonging to his session
                delete_agenda_item($event_id);
                $action = 'view';
            }
            break;
        case "showhide":
            if (!(api_is_course_coach() && !api_is_element_in_the_session(TOOL_AGENDA, $event_id))) {
                // a coach can only delete an element belonging to his session
                showhide_agenda_item($event_id);
                $action = 'view';
            }
            if (!empty($_GET['agenda_id'])) {
                display_one_agenda_item($_GET['agenda_id']);
            }
            break;
        case "delete_attach": //delete attachment file
            $id_attach = $_GET['id_attach'];
            if (!empty($id_attach)) {
                delete_attachment_file($id_attach);
                $action = 'view';
=======
            if (!(api_is_course_coach() && !api_is_element_in_the_session(TOOL_AGENDA, $eventId) )) {
                // a coach can only delete an element belonging to his session
                $content = $agenda->delete_event($eventId);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
            }
            break;
    }
}

if (!empty($group_id)) {
    $group_properties = GroupManager :: get_group_properties($group_id);
    $interbreadcrumb[] = array(
        "url" => api_get_path(WEB_CODE_PATH)."group/group.php",
        "name" => get_lang('Groups')
    );
    $interbreadcrumb[] = array(
        "url" => api_get_path(WEB_CODE_PATH)."group/group_space.php?gidReq=".$group_id,
        "name" => get_lang('GroupSpace').' '.$group_properties['name']
    );
}
if (!empty($actionName)) {
    $interbreadcrumb[] = array(
        "url" => $url,
        "name" => get_lang('Agenda')
    );
}

// Tool introduction
$introduction = Display::return_introduction_section(TOOL_CALENDAR_EVENT);

$message = Session::read('message');
Session::erase('message');

$tpl = new Template($actionName);
$tpl->assign('content', $content);
$tpl->assign('actions', $actions);

// Loading main Chamilo 1 col template
$tpl->display_one_col_template();
