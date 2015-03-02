<?php
/* For licensing terms, see /license.txt */
/**
 * @package chamilo.calendar
 */
/**
 * INIT SECTION
 */
<<<<<<< HEAD
=======
use \ChamiloSession as Session;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

// name of the language file that needs to be included
$language_file = array('agenda', 'group', 'announcements');

// use anonymous mode when accessing this course tool
$use_anonymous = true;

// Calendar type
<<<<<<< HEAD

$type = isset($_REQUEST['type']) && in_array($_REQUEST['type'], array('personal', 'course', 'admin')) ?  $_REQUEST['type'] : 'personal';
=======
$type = isset($_REQUEST['type']) && in_array($_REQUEST['type'], array('personal', 'course', 'admin', 'platform')) ? $_REQUEST['type'] : 'personal';
$userId = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : null;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

if ($type == 'personal') {
    $cidReset = true; // fixes #5162
}

require_once '../inc/global.inc.php';
require_once 'agenda.inc.php';

<<<<<<< HEAD
$current_course_tool  = TOOL_CALENDAR_EVENT;

=======
$current_course_tool = TOOL_CALENDAR_EVENT;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
$this_section = SECTION_MYAGENDA;

$htmlHeadXtra[] = api_get_jquery_libraries_js(array('jquery-ui','jquery-ui-i18n'));
$htmlHeadXtra[] = api_get_js('qtip2/jquery.qtip.min.js');
$htmlHeadXtra[] = api_get_js('fullcalendar/fullcalendar.min.js');
$htmlHeadXtra[] = api_get_js('fullcalendar/gcal.js');
$htmlHeadXtra[] = api_get_css(api_get_path(WEB_LIBRARY_JS_PATH).'fullcalendar/fullcalendar.css');
$htmlHeadXtra[] = api_get_css(api_get_path(WEB_LIBRARY_JS_PATH).'qtip2/jquery.qtip.min.css');

if (api_is_platform_admin() && ($type == 'admin' || $type == 'platform')) {
    $type = 'admin';
}

if (isset($_REQUEST['cidReq']) && !empty($_REQUEST['cidReq'])) {
    $type = 'course';
}

$agenda = new Agenda();
$agenda->type = $type;

$is_group_tutor = false;
$session_id = api_get_session_id();
$group_id = api_get_group_id();

if (!empty($group_id)) {
    $is_group_tutor = GroupManager::is_tutor_of_group(api_get_user_id(), $group_id);
<<<<<<< HEAD
    $group_properties  = GroupManager :: get_group_properties($group_id);
    $interbreadcrumb[] = array ("url" => "../group/group.php", "name" => get_lang('Groups'));
    $interbreadcrumb[] = array ("url"=>"../group/group_space.php?gidReq=".$group_id, "name"=> get_lang('GroupSpace').' '.$group_properties['name']);
}

$app['title'] = get_lang('Agenda');
$tpl = $app['template'];
=======
    $group_properties = GroupManager::get_group_properties($group_id);
    $interbreadcrumb[] = array(
        "url" => api_get_path(WEB_CODE_PATH)."group/group.php?".api_get_cidreq(),
        "name" => get_lang('Groups')
    );
    $interbreadcrumb[] = array(
        "url" => api_get_path(WEB_CODE_PATH)."group/group_space.php?".api_get_cidreq(),
        "name" => get_lang('GroupSpace').' '.$group_properties['name']
    );
}

$tpl = new Template(get_lang('Agenda'));
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
$tpl->assign('use_google_calendar', 0);

$can_add_events = 0;

switch($type) {
    case 'admin':
        api_protect_admin_script();
        $this_section = SECTION_PLATFORM_ADMIN;
        if (api_is_platform_admin()) {
            $can_add_events = 1;
        }
        break;
    case 'course':
        api_protect_course_script(true);
        $this_section = SECTION_COURSES;
        if (api_is_allowed_to_edit()) {
            $can_add_events = 1;
        }
        if (!empty($group_id)) {
            if ($is_group_tutor) {
                $can_add_events = 1;
            }
        }
        break;
    case 'personal':
        if (api_is_anonymous()) {
            api_not_allowed(true);
        }
        $extra_field_data = UserManager::get_extra_user_data_by_field(api_get_user_id(), 'google_calendar_url');
        if (!empty($extra_field_data) && isset($extra_field_data['google_calendar_url']) && !empty($extra_field_data['google_calendar_url'])) {
            $tpl->assign('use_google_calendar', 1);
            $tpl->assign('google_calendar_url', $extra_field_data['google_calendar_url']);
        }
        $this_section = SECTION_MYAGENDA;
        if (!api_is_anonymous()) {
            $can_add_events = 1;
        }
        break;
}


//Setting translations
$day_short 		= api_get_week_days_short();
$days 			= api_get_week_days_long();
$months 		= api_get_months_long();
$months_short 	= api_get_months_short();

//Setting calendar translations
$tpl->assign('month_names', json_encode($months));
$tpl->assign('month_names_short', json_encode($months_short));
$tpl->assign('day_names', json_encode($days));
$tpl->assign('day_names_short', json_encode($day_short));
<<<<<<< HEAD
$tpl->assign(
    'button_text',
    json_encode(
        array(
            'today' => get_lang('Today'),
            'month'	=> get_lang('Month'),
            'week'	=> get_lang('Week'),
            'day' => get_lang('Day')
        )
    )
=======
$tpl->assign('button_text',
    json_encode(array(
        'today' => get_lang('Today'),
        'month' => get_lang('Month'),
        'week' => get_lang('Week'),
        'day' => get_lang('Day')
    ))
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
);

//see http://docs.jquery.com/UI/Datepicker/$.datepicker.formatDate

$tpl->assign('js_format_date', 'D d M yy');
$region_value = api_get_language_isocode();

if ($region_value == 'en') {
    $region_value = 'en-GB';
}
<<<<<<< HEAD

$tpl->assign('region_value', 	$region_value);
$tpl->assign('export_ical_confidential_icon', Display::return_icon('export.png', get_lang('ExportiCalConfidential')));

$actions = null;
$filter = null;
if (api_is_allowed_to_edit(false,true) OR
    (api_get_course_setting('allow_user_edit_agenda') && !api_is_anonymous()) &&
    api_is_allowed_to_session_edit(false,true) OR
    $is_group_tutor
) {
    if ($type == 'course') {
        if (isset($_GET['user_id'])) {
            $filter = $_GET['user_id'];
        }
        $actions = display_courseadmin_links($filter);
    }
	$tpl->assign('actions', $actions);
}
=======
$tpl->assign('region_value', $region_value);

$export_icon = api_get_path(WEB_IMG_PATH).'img/export.png';
$export_icon_low = api_get_path(WEB_IMG_PATH).'img/export_low_fade.png';
$export_icon_high = api_get_path(WEB_IMG_PATH).'img/export_high_fade.png';

$tpl->assign(
    'export_ical_confidential_icon',
    Display::return_icon($export_icon_high, get_lang('ExportiCalConfidential'))
);

$actions = $agenda->displayActions('calendar', $userId);

$tpl->assign('actions', $actions);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

//Calendar Type : course, admin, personal
$tpl->assign('type', $type);

$type_event_class = $type.'_event';
$type_label = get_lang(ucfirst($type).'Calendar');
if ($type == 'course' && !empty($group_id)) {
    $type_event_class = 'group_event';
    $type_label = get_lang('GroupCalendar');
}

$defaultView = api_get_setting('default_calendar_view');

if (empty($defaultView)) {
    $defaultView = 'month';
}

/* month, basicWeek, agendaWeek, agendaDay */

$tpl->assign('default_view', $defaultView);

if ($type == 'course' && !empty($session_id)) {
    $type_event_class = 'session_event';
    $type_label = get_lang('SessionCalendar');
}

$tpl->assign('type_label', $type_label);
$tpl->assign('type_event_class', $type_event_class);

// Current user can add event?
$tpl->assign('can_add_events', $can_add_events);

// Setting AJAX caller
if (!empty($userId)) {
    $agenda_ajax_url = api_get_path(WEB_AJAX_PATH).'agenda.ajax.php?user_id='.$userId.'&type='.$type;
} else {
    $agenda_ajax_url = api_get_path(WEB_AJAX_PATH).'agenda.ajax.php?type='.$type;
}
$tpl->assign('web_agenda_ajax_url', $agenda_ajax_url);

<<<<<<< HEAD
$course_code  = api_get_course_id();
$select = null;
if ((api_is_allowed_to_edit() || $is_group_tutor) && $course_code != '-1' && $type == 'course') {
    $order = 'lastname';
    if (api_is_western_name_order()) {
        $order = 'firstname';
    }
    if (!empty($group_id)) {
        $group_list  = array($group_id => $group_properties);
        $user_list  = GroupManager::get_subscribed_users($group_id);
    } else {
        $user_list  = CourseManager::get_user_list_from_course_code(api_get_course_id(), api_get_session_id(), null, $order);
        $group_list = CourseManager::get_group_list_of_course(api_get_course_id(), api_get_session_id());
=======
//if ((api_is_allowed_to_edit() || $is_group_tutor) && $course_code != '-1' && $type == 'course') {

    $form = new FormValidator('form', 'get', null, null, array('id' => 'add_event_form'));
    $form->addElement('html', '<div id="visible_to_input">');

    $sendTo = $agenda->parseAgendaFilter($userId);
    $addOnlyItemsInSendTo = true;

    if ($sendTo['everyone']) {
        $addOnlyItemsInSendTo = false;
    }

    $agenda->showToForm($form, $sendTo, array(), $addOnlyItemsInSendTo);
    $form->addElement('html', '</div>');

    $form->addElement('html', '<div id="visible_to_read_only" style="display: none">');
    $form->addElement('label', get_lang('To'), '<div id="visible_to_read_only_users"></div>');
    $form->addElement('html', '</div>');

    $form->addElement('label', get_lang('Agenda'), '<div id ="color_calendar"></div>');
    $form->addElement('label', get_lang('Date'), '<span id="start_date"></span><span id="end_date"></span>');
    $form->addElement('text', 'title', get_lang('Title'), array('id' => 'title'));
    $form->addElement('textarea', 'content', get_lang('Description'), array('id' => 'content'));
    if ($agenda->type == 'course') {
        $form->addElement('html', '<div id="add_as_announcement_div" style="display: none">');
        $form->addElement('checkbox', 'add_as_annonuncement', null, get_lang('AddAsAnnouncement'));
        $form->addElement('html', '</div>');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    }

    $tpl->assign('form_add', $form->return_form());
//}

<<<<<<< HEAD
$form = new FormValidator('form-simple', '', null);
$form->addElement('label', get_lang('Date'), '<span id="simple_start_date"></span><span id="simple_end_date"></span>');
$form->addElement('label', get_lang('Title'), '<div id="simple_title"></div>');
$form->addElement('label', get_lang('Description'), '<div id="simple_content"></div>');
$tpl->assign('form_simple', $form->return_form());

$form = new FormValidator('add_event_form', null, null);
if (!empty($select)) {
    $form->addElement(
        'label',
        get_lang('To'),
        $select,
        array('id' => 'visible_to_input', 'style' => 'display:none')
    );
}

$form->addElement(
    'label',
    get_lang('To'),
    '<div id="visible_to_read_only_users"></div>',
    array('id' => 'visible_to_read_only', 'style' => 'display:none')
);

$form->addElement('label', get_lang('Agenda'), '<div id="color_calendar"></div>');
$form->addElement('label', get_lang('Date'), '<span id="start_date"></span><span id="end_date"></span>');
$form->addElement(
    'label',
    get_lang('Title'),
    '<input type="text" name="title" id="title" size="40" /><span id="title_edit"></span>'
);
$form->addElement(
    'label',
    get_lang('Description'),
    '<textarea name="content" id="content" class="span3" rows="5"></textarea>
    <span id="content_edit"></span>'
);

if ($type == 'course') {
    $form->addElement('html', '<div id="add_as_announcement_div">');
    $form->addElement('checkbox', 'add_as_annonuncement', array(null, null, get_lang('AddAsAnnouncement').' ('.get_lang('SendEmail').')'));
    $form->addElement('html', '</div>');
}
$tpl->assign('form_add', $form->return_form());

$tpl->display('default/agenda/month.tpl');
=======
// Loading Agenda template.
$content = $tpl->fetch('default/agenda/month.tpl');

$message = Session::read('message');
$tpl->assign('message', $message);
Session::erase('message');

$tpl->assign('content', $content);

// Loading main Chamilo 1 col template
$tpl->display_one_col_template();
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
