<?php
/* For licensing terms, see /license.txt */

/**
 *   HOME PAGE FOR EACH COURSE
 *
 *	This page, included in every course's index.php is the home
 *	page. To make administration simple, the teacher edits his
 *	course from the home page. Only the login detects that the
 *	visitor is allowed to activate, deactivate home page links,
 *	access to the teachers tools (statistics, edit forums...).
 *
 *	@package chamilo.course_home
 */
function return_block($title, $content)
{
    $html = '<div class="page-header">
                <h3>'.$title.'</h3>
            </div>
            '.$content.'</div>';
    return $html;
}

$session_id = api_get_session_id();
global $app;
$urlGenerator = $app['url_generator'];

$content = null;

// Start of tools for CourseAdmins (teachers/tutors)
$totalList = array();
if ($session_id == 0 && api_is_course_admin() && api_is_allowed_to_edit(null, true)) {
<<<<<<< HEAD
    $list = CourseHome::get_tools_category(TOOL_AUTHORING);
	$result = CourseHome::show_tools_category($urlGenerator, $list);
    $content .= return_block(get_lang('Authoring'), $result['content']);
    $totalList = $result['tool_list'];
=======
	$content .=  '<div class="courseadminview" style="border:0px; margin-top: 0px;padding:0px;">
		<div class="normal-message" id="id_normal_message" style="display:none">';
			$content .=  '<img src="'.api_get_path(WEB_PATH).'main/inc/lib/javascript/indicator.gif"/>&nbsp;&nbsp;';
			$content .=  get_lang('PleaseStandBy');
            $content .=  '</div>
		<div class="confirmation-message" id="id_confirmation_message" style="display:none"></div>
	</div>';


	if (api_get_setting('show_session_data') == 'true' && $session_id > 0) {
        $content .= '<div class="courseadminview">
            <span class="viewcaption">'.get_lang('SessionData').'</span>
            <table class="course_activity_home">'.CourseHome::show_session_data($session_id).'
            </table>
        </div>';
	}
    $my_list = CourseHome::get_tools_category(TOOL_AUTHORING);
	$items = CourseHome::show_tools_category($my_list);
    $content .= return_block(get_lang('Authoring'),  $items, 'course-tools-author');

    $my_list = CourseHome::get_tools_category(TOOL_INTERACTION);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    $list = CourseHome::get_tools_category(TOOL_INTERACTION);
    $list2 = CourseHome::get_tools_category(TOOL_COURSE_PLUGIN);
    $list = array_merge($list, $list2);
    $result =  CourseHome::show_tools_category($urlGenerator, $list);
    $totalList = array_merge($totalList, $result['tool_list']);

    $content .= return_block(get_lang('Interaction'), $result['content']);

<<<<<<< HEAD
    $list = CourseHome::get_tools_category(TOOL_ADMIN_PLATFORM);
    $totalList = array_merge($totalList, $list);
    $result = CourseHome::show_tools_category($urlGenerator, $list);
=======
    $content .= return_block(get_lang('Interaction'),  $items, 'course-tools-interaction');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    $totalList = array_merge($totalList, $result['tool_list']);

<<<<<<< HEAD
    $content .= return_block(get_lang('Administration'), $result['content']);
=======
    $content .= return_block(get_lang('Administration'),  $items , 'course-tools-administration');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

} elseif (api_is_coach()) {

    $content .=  '<div class="row">';
    $list = CourseHome::get_tools_category(TOOL_STUDENT_VIEW);
    $content .= CourseHome::show_tools_category($urlGenerator, $result['content']);
    $totalList = array_merge($totalList, $result['tool_list']);
    $content .= '</div>';
} else {
    $list = CourseHome::get_tools_category(TOOL_STUDENT_VIEW);
    if (count($list) > 0) {
        $content .= '<div class="row">';
        $result = CourseHome::show_tools_category($urlGenerator, $list);
        $content .= $result['content'];
        $totalList = array_merge($totalList, $result['tool_list']);
        $content .= '</div>';
    }
}

<<<<<<< HEAD
return array(
    'content' => $content,
    'tool_list' => $totalList
);
=======
function return_block($title, $content, $class) {
    $html = '<div class="row course-title-tools"><div class="span12"><div class="page-header"><h3>'.$title.'</h3></div></div></div><div class="row '.$class.'">'.$content.'</div>';
    return $html;
}
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
