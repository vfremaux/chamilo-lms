<?php
/* For licensing terms, see /license.txt */
/**
 * Courses reporting
 * @package chamilo.reporting
 */
/**
 * Code
 */
ob_start();
$nameTools = 'Cours';
// name of the language file that needs to be included
$language_file = array('admin', 'registration', 'index', 'tracking');
$cidReset = true;

require_once '../inc/global.inc.php';
<<<<<<< HEAD
=======
require_once api_get_path(LIBRARY_PATH).'export.lib.inc.php';
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

$this_section = SECTION_TRACKING;

$sessionId = isset($_GET['session_id']) ? intval($_GET['session_id']) : null;

api_block_anonymous_users();
$interbreadcrumb[] = array ("url" => "index.php", "name" => get_lang('MySpace'));

if (isset($_GET["id_session"]) && $_GET["id_session"] != "") {
    $interbreadcrumb[] = array("url" => "session.php", "name" => get_lang('Sessions'));
}

if (isset($_GET["user_id"]) && $_GET["user_id"] != "" && isset($_GET["type"]) && $_GET["type"] == "coach") {
    $interbreadcrumb[] = array("url" => "coaches.php", "name" => get_lang('Tutors'));
}

if (isset($_GET["user_id"]) && $_GET["user_id"] != "" && isset($_GET["type"]) && $_GET["type"] == "student") {
    $interbreadcrumb[] = array("url" => "student.php", "name" => get_lang('Students'));
}

if (isset($_GET["user_id"]) && $_GET["user_id"] != "" && !isset($_GET["type"])) {
    $interbreadcrumb[] = array("url" => "teachers.php", "name" => get_lang('Teachers'));
}

function count_courses()
{
	global $nb_courses;
	return $nb_courses;
}

//checking if the current coach is the admin coach
$show_import_icon = false;

if (api_get_setting('add_users_by_coach') == 'true') {
    if (!api_is_platform_admin()) {
        $sql = 'SELECT id_coach
                FROM '.Database::get_main_table(TABLE_MAIN_SESSION).'
                WHERE id='.$sessionId;
        $rs = Database::query($sql);
        if (Database::result($rs, 0, 0) != $_user['user_id']) {
            api_not_allowed(true);
        } else {
            $show_import_icon = true;
        }
    }
}

Display :: display_header($nameTools);

$a_courses = array();
if (api_is_drh() || api_is_session_admin() || api_is_platform_admin()) {
<<<<<<< HEAD

	$title = '';
	if (empty($id_session)) {
		if (isset($_GET['user_id'])) {
			$user_id = intval($_GET['user_id']);
			$user_info = api_get_user_info($user_id);
			$title = get_lang('AssignedCoursesTo').' '.api_get_person_name($user_info['firstname'], $user_info['lastname']);
			$courses  = CourseManager::get_course_list_of_user_as_course_admin($user_id);
		} else {
			$title = get_lang('YourCourseList');
			$courses = CourseManager::get_courses_followed_by_drh($_user['user_id']);
		}
	} else {
		$session_name = api_get_session_name($id_session);
		$title = api_htmlentities($session_name,ENT_QUOTES,$charset).' : '.get_lang('CourseListInSession');
		$courses = Tracking::get_courses_list_from_session($id_session);
	}

	$a_courses = array_keys($courses);

	if (!api_is_session_admin()) {
		$menu_items[] = Display::url(Display::return_icon('stats.png', get_lang('MyStats'),'',ICON_SIZE_MEDIUM),api_get_path(WEB_CODE_PATH)."auth/my_progress.php" );
		$menu_items[] = Display::url(Display::return_icon('user.png', get_lang('Students'), array(), 32), "index.php?view=drh_students&amp;display=yourstudents");
		$menu_items[] = Display::url(Display::return_icon('teacher.png', get_lang('Trainers'), array(), 32), 'teachers.php');
		$menu_items[] = Display::return_icon('course_na.png', get_lang('Courses'), array(), 32);
		$menu_items[] = Display::url(Display::return_icon('session.png', get_lang('Sessions'), array(), 32), 'session.php');
	}

	echo '<div class="actions">';
	$nb_menu_items = count($menu_items);
	if ($nb_menu_items > 1) {
		foreach ($menu_items as $key => $item) {
			echo $item;
		}
	}
	if (count($a_courses) > 0) {
		echo '<span style="float:right">';
		echo Display::url(Display::return_icon('printer.png', get_lang('Print'), array(), 32), 'javascript: void(0);', array('onclick'=>'javascript: window.print();'));
		echo '</span>';
	}
	echo '</div>';
	echo Display::page_header($title);
=======
    $title = '';
    if (empty($sessionId)) {
        if (isset($_GET['user_id'])) {
            $user_id = intval($_GET['user_id']);
            $user_info = api_get_user_info($user_id);
            $title = get_lang('AssignedCoursesTo').' '.api_get_person_name($user_info['firstname'], $user_info['lastname']);
            $courses  = CourseManager::get_course_list_of_user_as_course_admin($user_id);
        } else {
            $title = get_lang('YourCourseList');
            $courses = CourseManager::get_courses_followed_by_drh(api_get_user_id());
        }
    } else {
        $session_name = api_get_session_name($sessionId);
        $title = api_htmlentities($session_name, ENT_QUOTES, $charset).' : '.get_lang('CourseListInSession');
        $courses = Tracking::get_courses_list_from_session($sessionId);
    }

    $a_courses = array_keys($courses);

    if (!api_is_session_admin()) {
        $menu_items[] = Display::url(Display::return_icon('stats.png', get_lang('MyStats'),'',ICON_SIZE_MEDIUM),api_get_path(WEB_CODE_PATH)."auth/my_progress.php" );
        $menu_items[] = Display::url(Display::return_icon('user.png', get_lang('Students'), array(), ICON_SIZE_MEDIUM), "index.php?view=drh_students&amp;display=yourstudents");
        $menu_items[] = Display::url(Display::return_icon('teacher.png', get_lang('Trainers'), array(), ICON_SIZE_MEDIUM), 'teachers.php');
        $menu_items[] = Display::url(Display::return_icon('course_na.png', get_lang('Courses'), array(), ICON_SIZE_MEDIUM), '#');
        $menu_items[] = Display::url(Display::return_icon('session.png', get_lang('Sessions'), array(), ICON_SIZE_MEDIUM), 'session.php');

        if (api_can_login_as($user_id)) {
            $link = '<a href="'.api_get_path(WEB_CODE_PATH).'admin/user_list.php?action=login_as&amp;user_id='.$user_id.'&amp;sec_token='.Security::get_existing_token().'">'.
                    Display::return_icon('login_as.png', get_lang('LoginAs'), null, ICON_SIZE_MEDIUM).'</a>&nbsp;&nbsp;';
            $menu_items[] = $link;
        }
    }

    echo '<div class="actions">';
    $nb_menu_items = count($menu_items);
    if ($nb_menu_items > 1) {
        foreach ($menu_items as $key => $item) {
            echo $item;
        }
    }
    if (count($a_courses) > 0) {
        echo '<span style="float:right">';
        echo Display::url(
            Display::return_icon('printer.png', get_lang('Print'), array(), 32),
            'javascript: void(0);',
            array('onclick'=>'javascript: window.print();')
        );
        echo '</span>';
    }

    echo '</div>';
    echo Display::page_header($title);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
}

// Database Table Definitions
$tbl_session_course_user = Database::get_main_table(TABLE_MAIN_SESSION_COURSE_USER);
$tbl_user_course = Database::get_main_table(TABLE_MAIN_COURSE_USER);

if ($show_import_icon) {
    echo "<div align=\"right\">";
    echo '<a href="user_import.php?id_session='.$sessionId.'&action=export&amp;type=xml">'.
            Display::return_icon('excel.gif', get_lang('ImportUserListXMLCSV')).'&nbsp;'.get_lang('ImportUserListXMLCSV').'</a>';
    echo "</div><br />";
}

function get_count_courses()
{
    $userId = api_get_user_id();
    $sessionId = isset($_GET['session_id']) ? intval($_GET['session_id']) : null;
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : null;
    $drhLoaded = false;

    if (api_is_drh()) {
        if (api_drh_can_access_all_session_content()) {
            if (empty($sessionId)) {
                $count = SessionManager::getAllCoursesFollowedByUser(
                    $userId,
                    null,
                    null,
                    null,
                    null,
                    null,
                    true,
                    $keyword
                );
            } else {
                $count = SessionManager::getCourseCountBySessionId(
                    $sessionId,
                    $keyword
                );
            }
            $drhLoaded = true;
        }
    }

    if ($drhLoaded == false) {
        $count = CourseManager::getCoursesFollowedByUser(
            $userId,
            COURSEMANAGER,
            null,
            null,
            null,
            null,
            true,
            $keyword
        );
    }

    return $count;
}

function get_courses($from, $limit, $column, $direction)
{
    $userId = api_get_user_id();
    $sessionId = isset($_GET['session_id']) ? intval($_GET['session_id']) : 0;
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : null;

    $drhLoaded = false;
    if (api_is_drh()) {
        if (api_drh_can_access_all_session_content()) {
            $courses = SessionManager::getAllCoursesFollowedByUser(
                $userId,
                $sessionId,
                $from,
                $limit,
                $column,
                $direction,
                false,
                $keyword
            );
            $drhLoaded = true;
        }
    }

    if ($drhLoaded == false) {
        $courses = CourseManager::getCoursesFollowedByUser(
            $userId,
            COURSEMANAGER,
            $from,
            $limit,
            $column,
            $direction,
            false,
            $keyword
        );
    }

    $courseList = array();
    if (!empty($courses)) {
        foreach ($courses as $data) {
            $courseCode = $data['code'];
            $courseInfo = api_get_course_info($courseCode);
            $userList = CourseManager::get_user_list_from_course_code($data['code'], $sessionId);
            $userIdList = array();
            if (!empty($userList)) {
                foreach ($userList as $user) {
                    $userIdList[] = $user['user_id'];
                }
            }

            $messagesInCourse = 0;
            $assignmentsInCourse = 0;
            $avgTimeSpentInCourse = 0;
            $avgProgressInCourse = 0;

            if (count($userIdList) > 0) {
                $countStudents = count($userIdList);
                // tracking data
                $avgProgressInCourse = Tracking :: get_avg_student_progress($userIdList, $courseCode, array(), $sessionId);
                $avgScoreInCourse = Tracking :: get_avg_student_score($userIdList, $courseCode, array(), $sessionId);
                $avgTimeSpentInCourse = Tracking :: get_time_spent_on_the_course($userIdList, $courseCode, $sessionId);
                $messagesInCourse = Tracking :: count_student_messages($userIdList, $courseCode, $sessionId);
                $assignmentsInCourse = Tracking :: count_student_assignments($userIdList, $courseCode, $sessionId);
                $avgTimeSpentInCourse = api_time_to_hms($avgTimeSpentInCourse / $countStudents);
                $avgProgressInCourse = round($avgProgressInCourse / $countStudents, 2);

                if (is_numeric($avgScoreInCourse)) {
                    $avgScoreInCourse = round($avgScoreInCourse / $countStudents, 2).'%';
                }
            }

            $thematic = new Thematic();
            $tematic_advance = $thematic->get_total_average_of_thematic_advances($courseCode, $sessionId);
            $tematicAdvanceProgress = '-';
            if (!empty($tematic_advance)) {
                $tematicAdvanceProgress = '<a title="'.get_lang('GoToThematicAdvance').'" href="'.api_get_path(WEB_CODE_PATH).'course_progress/index.php?cidReq='.$courseCode.'&id_session='.$sessionId.'">'.
                    $tematic_advance.'%</a>';
            }

            $courseIcon = '<a href="'.api_get_path(WEB_CODE_PATH).'tracking/courseLog.php?cidReq='.$courseCode.'&id_session='.$sessionId.'">
                        <img src="'.api_get_path(WEB_IMG_PATH).'2rightarrow.gif" border="0" />
                      </a>';
            $title = Display::url(
                $data['title'],
                $courseInfo['course_public_url'].'?id_session='.$sessionId
            );
            $courseList[] = array(
                $title,
                $countStudents,
                is_null($avgTimeSpentInCourse) ? '-' : $avgTimeSpentInCourse,
                $tematicAdvanceProgress,
                is_null($avgProgressInCourse) ? '-' : $avgProgressInCourse.'%',
                is_null($avgScoreInCourse) ? '-' : $avgScoreInCourse,
                is_null($messagesInCourse) ? '-' : $messagesInCourse,
                is_null($assignmentsInCourse) ? '-' : $assignmentsInCourse,
                $courseIcon
            );
        }
    }

    return $courseList;
}


$table = new SortableTable(
    'tracking_course',
    'get_count_courses',
    'get_courses',
    1,
    10
);

<<<<<<< HEAD
if (is_array($a_courses)) {
	foreach ($a_courses as $courseId) {
        $courseId = intval($courseId);
		$nb_students_in_course = 0;
		$course = api_get_course_info_by_id($courseId);
        $course_code = $course['code'];
		$avg_assignments_in_course = $avg_messages_in_course = $avg_progress_in_course = $avg_score_in_course = $avg_time_spent_in_course = 0;

		// students directly subscribed to the course
		if (empty($id_session)) {
			$sql = "SELECT user_id FROM $tbl_user_course as course_rel_user
			        WHERE course_rel_user.status='5' AND course_rel_user.c_id =".$courseId;
		} else {
			$sql = "SELECT id_user as user_id FROM $tbl_session_course_user srcu
			        WHERE  srcu.c_id='$courseId' AND id_session = '$id_session' AND srcu.status<>2";
		}

		$rs = Database::query($sql);
		$users = array();
		while ($row = Database::fetch_array($rs)) {
            $users[] = $row['user_id'];
        }

		if (count($users) > 0) {
			$nb_students_in_course = count($users);
			// tracking datas
			$avg_progress_in_course = Tracking :: get_avg_student_progress ($users, $courseId, array(), $id_session);
			$avg_score_in_course = Tracking :: get_avg_student_score ($users, $courseId, array(), $id_session);
			$avg_time_spent_in_course = Tracking :: get_time_spent_on_the_course ($users, $courseId, $id_session);
			$messages_in_course = Tracking :: count_student_messages ($users, $courseId, $id_session);
			$assignments_in_course = Tracking :: count_student_assignments ($users, $courseId, $id_session);

			$avg_time_spent_in_course = api_time_to_hms($avg_time_spent_in_course / $nb_students_in_course);
			$avg_progress_in_course = round($avg_progress_in_course / $nb_students_in_course, 2);

			if (is_numeric($avg_score_in_course)) {
				$avg_score_in_course = round($avg_score_in_course / $nb_students_in_course, 2).'%';
			}

		} else {
			$avg_time_spent_in_course = null;
			$avg_progress_in_course = null;
			$avg_score_in_course = null;
			$messages_in_course = null;
			$assignments_in_course = null;
		}

		$tematic_advance_progress = 0;
		$thematic = new Thematic($course);
		$tematic_advance = $thematic->get_total_average_of_thematic_advances($course_code, $id_session);

		if (!empty($tematic_advance)) {
			$tematic_advance_csv = $tematic_advance_progress.'%';
			$tematic_advance_progress = '<a title="'.get_lang('GoToThematicAdvance').'" href="'.api_get_path(WEB_CODE_PATH).'course_progress/index.php?cidReq='.$course_code.'&id_session='.$id_session.'">'.$tematic_advance.'%</a>';
		} else {
			$tematic_advance_progress = '-';
		}

		$table_row = array();
		$table_row[] = $course['title'];
		$table_row[] = $nb_students_in_course;
		$table_row[] = is_null($avg_time_spent_in_course)?'-':$avg_time_spent_in_course;
		$table_row[] = $tematic_advance_progress;
		$table_row[] = is_null($avg_progress_in_course) ? '-' : $avg_progress_in_course.'%';
		$table_row[] = is_null($avg_score_in_course) ? '-' : $avg_score_in_course;
		$table_row[] = is_null($messages_in_course)?'-':$messages_in_course;
		$table_row[] = is_null($assignments_in_course)?'-':$assignments_in_course;
		$table_row[] = '<a href="../tracking/courseLog.php?cidReq='.$course_code.'&id_session='.$id_session.'"><img src="'.api_get_path(WEB_IMG_PATH).'2rightarrow.gif" border="0" /></a>';

		$csv_content[] = array (
			$course['title'],
			$nb_students_in_course,
			$avg_time_spent_in_course,
			$tematic_advance_csv,
			is_null($avg_progress_in_course) ? null : $avg_progress_in_course.'%',
			is_null($avg_score_in_course) ? null : $avg_score_in_course,
			$messages_in_course,
			$assignments_in_course,
		);

		$table -> addRow($table_row, 'align="right"');
	}

	// $csv_content = array_merge($csv_header, $csv_content); // Before this statement you are allowed to sort (in different way) the array $csv_content.
}
//$table -> setColAttributes(0);
//$table -> setColAttributes(7);
$table -> display();
=======
$table->set_header(0, get_lang('CourseTitle'), false);
$table->set_header(1, get_lang('NbStudents'), false);
$table->set_header(2, get_lang('TimeSpentInTheCourse').Display :: return_icon('info3.gif', get_lang('TimeOfActiveByTraining'), array('align' => 'absmiddle', 'hspace' => '3px')), false);
$table->set_header(3, get_lang('ThematicAdvance'), false);
$table->set_header(4, get_lang('AvgStudentsProgress').Display :: return_icon('info3.gif', get_lang('AvgAllUsersInAllCourses'), array('align' => 'absmiddle', 'hspace' => '3px')), false);
$table->set_header(5, get_lang('AvgCourseScore').Display :: return_icon('info3.gif', get_lang('AvgAllUsersInAllCourses'), array('align' => 'absmiddle', 'hspace' => '3px')), false);
$table->set_header(6, get_lang('AvgMessages'), false);
$table->set_header(7, get_lang('AvgAssignments'), false);
$table->set_header(8, get_lang('Details'), false);

$form = new FormValidator('search_course', 'get', api_get_path(WEB_CODE_PATH).'mySpace/course.php');
$form->addElement('text', 'keyword', get_lang('Keyword'));
$form->addElement('button', 'submit', get_lang('Search'));
$form->addElement('hidden', 'session_id', $sessionId);

$keyword = isset($_GET['keyword']) ? Security::remove_XSS($_GET['keyword']) : null;

$params = array(
    'session_id' => $sessionId,
    'keyword' => $keyword
);
$table->set_additional_parameters($params);

$form->setDefaults($params);
$form->display();
$table->display();
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

Display :: display_footer();
