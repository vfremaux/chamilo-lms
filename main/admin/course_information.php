<?php
/* For licensing terms, see /license.txt */
/**
 * This script gives information about a course
 * @author Bart Mollet
 * @package chamilo.admin
 */
/**
 * INIT SECTION
 */

// name of the language file that needs to be included
$language_file = 'admin';
$cidReset = true;
require_once '../inc/global.inc.php';
$this_section = SECTION_PLATFORM_ADMIN;

api_protect_admin_script();
/**
 *
 */
function get_course_usage($course_code, $session_id = 0)
{
    $table       = Database::get_main_table(TABLE_MAIN_COURSE);
    $course_code = Database::escape_string($course_code);
    $sql         = "SELECT * FROM $table WHERE code='".$course_code."'";
    $res         = Database::query($sql);
    $course      = Database::fetch_object($res);
    // Learnpaths
    $table   = Database :: get_course_table(TABLE_LP_MAIN);
    $usage[] = array(
        get_lang(ucfirst(TOOL_LEARNPATH)),
        CourseManager::count_rows_course_table($table, $session_id, $course->id)
    );
    // Forums
    $table   = Database :: get_course_table(TABLE_FORUM);
    $usage[] = array(get_lang('Forums'), CourseManager::count_rows_course_table($table, $session_id, $course->id));
    // Quizzes
    $table   = Database :: get_course_table(TABLE_QUIZ_TEST);
    $usage[] = array(
        get_lang(ucfirst(TOOL_QUIZ)),
        CourseManager::count_rows_course_table($table, $session_id, $course->id)
    );
    // Documents
    $table   = Database :: get_course_table(TABLE_DOCUMENT);
    $usage[] = array(
        get_lang(ucfirst(TOOL_DOCUMENT)),
        CourseManager::count_rows_course_table($table, $session_id, $course->id)
    );
    // Groups
    $table   = Database :: get_course_table(TABLE_GROUP);
    $usage[] = array(
        get_lang(ucfirst(TOOL_GROUP)),
        CourseManager::count_rows_course_table($table, $session_id, $course->id)
    );
    // Calendar
    $table   = Database :: get_course_table(TABLE_AGENDA);
    $usage[] = array(
        get_lang(ucfirst(TOOL_CALENDAR_EVENT)),
        CourseManager::count_rows_course_table($table, $session_id, $course->id)
    );
    // Link
    $table   = Database::get_course_table(TABLE_LINK);
    $usage[] = array(
        get_lang(ucfirst(TOOL_LINK)),
        CourseManager::count_rows_course_table($table, $session_id, $course->id)
    );
    // Announcements
    $table   = Database::get_course_table(TABLE_ANNOUNCEMENT);
    $usage[] = array(
        get_lang(ucfirst(TOOL_ANNOUNCEMENT)),
        CourseManager::count_rows_course_table($table, $session_id, $course->id)
    );
    return $usage;
}

if (!isset ($_GET['code'])) {
    api_not_allowed();
}
<<<<<<< HEAD
$interbreadcrumb[] = array ("url" => 'index.php', "name" => get_lang('PlatformAdmin'));
$interbreadcrumb[] = array ("url" => 'course_list.php', "name" => get_lang('Courses'));

$course_info = api_get_course_info($_GET['code']);
$tool_name = $course_info['title'].' ('.$course_info['visual_code'].')';
Display::display_header($tool_name);
=======
$interbreadcrumb[] = array("url" => 'index.php', "name" => get_lang('PlatformAdmin'));
$interbreadcrumb[] = array("url" => 'course_list.php', "name" => get_lang('Courses'));
$table_course = Database :: get_main_table(TABLE_MAIN_COURSE);
$code = Database::escape_string($_GET['code']);
$sql = "SELECT * FROM $table_course WHERE code = '".$code."'";
$res = Database::query($sql);
$course = Database::fetch_object($res);
$tool_name = $course->title.' ('.$course->visual_code.')';
Display::display_header($tool_name);
?>
    <div class="actions">
        <a href="<?php echo api_get_path(WEB_COURSE_PATH).$course->directory; ?>">
            <?php Display::display_icon('home.png', get_lang('CourseHomepage'), array(), ICON_SIZE_MEDIUM); ?>
        </a>
    </div>
<?php
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

echo '<div class="actions">';
echo '<a href="'.api_get_path(WEB_COURSE_PATH).$course_info['directory'].'">'.
        Display::display_icon('home.png', get_lang('CourseHomepage'), array(), ICON_SIZE_MEDIUM).'</a>';
echo '</div>';

echo Display::page_header($tool_name);

echo Display::page_subheader(get_lang('CourseUsage'));

$id_session = isset($_GET['id_session']) ? $_GET['id_session'] : 0;
<<<<<<< HEAD
$table = new SortableTableFromArray(get_course_usage($course_info['code'], $id_session), 0, 20,'usage_table');
$table->set_additional_parameters(array ('code' => Security::remove_XSS($_GET['code'])));
$table->set_other_tables(array('user_table','class_table'));
$table->set_header(0,get_lang('Tool'), true);
$table->set_header(1,get_lang('NumberOfItems'), true);
=======
$table      = new SortableTableFromArray(get_course_usage($course->code, $id_session), 0, 20, 'usage_table');
$table->set_additional_parameters(array('code' => Security::remove_XSS($_GET['code'])));
$table->set_other_tables(array('user_table', 'class_table'));
$table->set_header(0, get_lang('Tool'), true);
$table->set_header(1, get_lang('NumberOfItems'), true);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
$table->display();

/**
 * Show all users subscribed in this course
 */
<<<<<<< HEAD
echo Display::page_subheader(get_lang('Users'));
$table_course_user = Database :: get_main_table(TABLE_MAIN_COURSE_USER);
$table_user = Database :: get_main_table(TABLE_MAIN_USER);
$sql = "SELECT *, cu.status as course_status FROM $table_course_user cu, $table_user u
        WHERE cu.user_id = u.user_id AND cu.c_id = '".$course_info['real_id']."' AND cu.relation_type <> ".COURSE_RELATION_TYPE_RRHH." ";
$res = Database::query($sql);
$is_western_name_order = api_is_western_name_order();
if (Database::num_rows($res) > 0) {
	$users = array ();
	while ($obj = Database::fetch_object($res)) {
		$user = array ();
		$user[] = $obj->official_code;
		if ($is_western_name_order) {
			$user[] = $obj->firstname;
			$user[] = $obj->lastname;
		} else {
			$user[] = $obj->lastname;
			$user[] = $obj->firstname;
		}
		$user[] = Display :: encrypted_mailto_link($obj->email, $obj->email);
		$user[] = $obj->course_status == 5 ? get_lang('Student') : get_lang('Teacher');
		$user[] = '<a href="user_information.php?user_id='.$obj->user_id.'">'.Display::return_icon('synthese_view.gif',get_lang('UserInfo')).'</a>';
		$users[] = $user;
	}
	$table = new SortableTableFromArray($users,0,20,'user_table');
	$table->set_additional_parameters(array ('code' => $course_info['code']));
	$table->set_other_tables(array('usage_table','class_table'));
	$table->set_header(0,get_lang('OfficialCode'), true);
	if ($is_western_name_order) {
		$table->set_header(1,get_lang('FirstName'), true);
		$table->set_header(2,get_lang('LastName'), true);
	} else {
		$table->set_header(1,get_lang('LastName'), true);
		$table->set_header(2,get_lang('FirstName'), true);
	}
	$table->set_header(3,get_lang('Email'), true);
	$table->set_header(4,get_lang('Status'), true);
	$table->set_header(5,'', false);
	$table->display();
} else {
	echo Display::display_warning_message(get_lang('NoUsersInCourse'));
=======
echo Display::page_header(get_lang('Users'));
$table_course_user     = Database :: get_main_table(TABLE_MAIN_COURSE_USER);
$table_user            = Database :: get_main_table(TABLE_MAIN_USER);
$sql                   = "SELECT *,cu.status as course_status FROM $table_course_user cu, $table_user u WHERE cu.user_id = u.user_id AND cu.course_code = '".$code."' AND cu.relation_type <> ".COURSE_RELATION_TYPE_RRHH." ";
$res                   = Database::query($sql);
$is_western_name_order = api_is_western_name_order();
if (Database::num_rows($res) > 0) {
    $users = array();
    while ($obj = Database::fetch_object($res)) {
        $user   = array();
        $user[] = $obj->official_code;
        if ($is_western_name_order) {
            $user[] = $obj->firstname;
            $user[] = $obj->lastname;
        } else {
            $user[] = $obj->lastname;
            $user[] = $obj->firstname;
        }
        $user[]  = Display :: encrypted_mailto_link($obj->email, $obj->email);
        $user[]  = $obj->course_status == 5 ? get_lang('Student') : get_lang('Teacher');
        $user[]  = '<a href="user_information.php?user_id='.$obj->user_id.'">'.Display::return_icon('synthese_view.gif', get_lang('UserInfo')).'</a>';
        $users[] = $user;
    }
    $table = new SortableTableFromArray($users, 0, 20, 'user_table');
    $table->set_additional_parameters(array('code' => $code));
    $table->set_other_tables(array('usage_table', 'class_table'));
    $table->set_header(0, get_lang('OfficialCode'), true);
    if ($is_western_name_order) {
        $table->set_header(1, get_lang('FirstName'), true);
        $table->set_header(2, get_lang('LastName'), true);
    } else {
        $table->set_header(1, get_lang('LastName'), true);
        $table->set_header(2, get_lang('FirstName'), true);
    }
    $table->set_header(3, get_lang('Email'), true);
    $table->set_header(4, get_lang('Status'), true);
    $table->set_header(5, '', false);
    $table->display();
} else {
    echo get_lang('NoUsersInCourse');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
}

$session_list = SessionManager::get_session_by_course($course_info['real_id']);

$url = api_get_path(WEB_CODE_PATH);
if (!empty($session_list)) {
<<<<<<< HEAD
    foreach($session_list as &$session)  {
        $session[0] = Display::url($session[0], $url.'admin/resume_session.php?id_session='.$session['id'] );
        unset($session[1]);
    }
    echo Display::page_subheader(get_lang('Sessions'));
    $table = new SortableTableFromArray($session_list, 0, 20,'user_table');
=======
    foreach ($session_list as &$session) {
        $session[0] = Display::url($session[0], $url.'admin/resume_session.php?id_session='.$session['id']);
        unset($session[1]);
    }
    echo Display::page_header(get_lang('Sessions'));
    $table = new SortableTableFromArray($session_list, 0, 20, 'user_table');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    $table->display();
}

$extra_field = new ExtraField('course');
$extra_fields = $extra_field->get_all();

<<<<<<< HEAD
if (!empty($extra_fields)) {
    echo Display::page_subheader(get_lang('ExtraFields'));
    echo '<table class="data_table">';
    foreach ($extra_fields as $field) {
        if ($field['field_visible'] != '1') {
            continue;
        }
        $obj = new ExtraFieldValue($extra_field->type);
        $result = $obj->get_values_by_handler_and_field_id($course_info['code'], $field['id'], true);

        $value = null;
        if ($result) {
            $value = $result['field_value'];
            if (is_bool($value)) {
                $value = $value ? get_lang('Yes') : get_lang('No');
            }
        } else {
            $value = '-';
        }
        echo "<tr>";
        echo "<td> {$field['field_display_text']} </td>";
        echo "<td> $value </td>";
        echo "</tr>";
    }
    echo "</table>";
}

=======
/*@todo This should be dissapear classes are a deprecated feature*/
/*
//Show all classes subscribed in this course

$table_course_class = Database :: get_main_table(TABLE_MAIN_COURSE_CLASS);
$table_class 		= Database :: get_main_table(TABLE_MAIN_CLASS);
$sql = "SELECT * FROM $table_course_class cc, $table_class c WHERE cc.class_id = c.id AND cc.course_code = '".$code."'";
$res = Database::query($sql);
if (Database::num_rows($res) > 0) {
	$data = array ();
	while ($class = Database::fetch_object($res)) {
		$row = array ();
		$row[] = $class->name;
		$row[] = '<a href="class_information.php?id='.$class->id.'">'.Display::return_icon('synthese_view.gif', get_lang('Edit')).'</a>';
		$data[] = $row;
	}
	echo '<p><b>'.get_lang('AdminClasses').'</b></p>';
	echo '<blockquote>';
	$table = new SortableTableFromArray($data,0,20,'class_table');
	$table->set_additional_parameters(array ('code' => $_GET['code']));
	$table->set_other_tables(array('usage_table','user_table'));
	$table->set_header(0,get_lang('Title'));
	$table->set_header(1,'');
	$table->display();
	echo '</blockquote>';
} else {
	echo '<p>'.get_lang('NoClassesForThisCourse').'</p>';
}*/
/*	FOOTER	*/
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
Display::display_footer();
