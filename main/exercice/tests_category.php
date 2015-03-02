<?php
/* For licensing terms, see /license.txt */

/**
  hubert.borderiou
  Manage tests category page
 */
// name of the language file that needs to be included
$language_file = 'exercice';
$nameTools = "";

require_once '../inc/global.inc.php';
require_once 'question.class.php';

$this_section = SECTION_COURSES;

if (!(api_is_allowed_to_edit() || api_is_question_manager())) {
    api_not_allowed(true);
}

$type = isset($_GET['type']) ? Security::remove_XSS($_GET['type']) : 'simple';

if ($type == 'global' && !(api_is_platform_admin() || api_is_question_manager())) {
    api_not_allowed(true);
}

$url = api_get_path(WEB_AJAX_PATH).'exercise.ajax.php?type='.$type;
$htmlHeadXtra[] = '
<script>
	function confirmDelete(in_txt, in_id) {
		var oldbgcolor = document.getElementById(in_id).style.backgroundColor;
		document.getElementById(in_id).style.backgroundColor="#AAFFB0";
		if (confirm(in_txt)) {
			return true;
		} else {
			document.getElementById(in_id).style.backgroundColor = oldbgcolor;
			return false;
		}
	}
<<<<<<< HEAD
=======
</script>';

// name of the language file that needs to be included
$language_file = 'exercice';
$nameTools = "";
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    function check() {
        $("#parent_id option:selected").each(function() {
            var id = $(this).val();
            var name = $(this).text();
            if (id != "" ) {
                $.ajax({
                    async: false,
                    url: "'.$url.'&a=exercise_category_exists",
                    data: "id="+id,
                    success: function(return_value) {
                        if (return_value == 0 ) {
                            alert("'.get_lang('CategoryDoesNotExists').'");
                            //Deleting select option tag
                            $("#parent_id").find("option").remove();

                            $(".holder li").each(function () {
                                if ($(this).attr("rel") == id) {
                                    $(this).remove();
                                }
                            });
                        }
                    }
                });
            }
        });
    }

<<<<<<< HEAD
    $(function() {
        $("#parent_id").fcbkcomplete({
            json_url: "'.$url.'&a=search_category_parent",
            maxitems: 1,
            addontab: false,
            input_min_size: 1,
            cache: false,
            complete_text:"'.get_lang('StartToType').'",
            firstselected: false,
            onselect: check,
            filter_selected: true,
            newel: true
        });
    });
</script>';
=======
if (!api_is_allowed_to_edit()) {
    api_not_allowed(true);
}
$category = new Testcategory();
$courseId = api_get_course_int_id();
$sessionId = api_get_session_id();
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

// Breadcrumbs
$interbreadcrumb[] = array("url" => "exercice.php", "name" => get_lang('Exercices'));
Display::display_header(get_lang('Category'));

// Action handling: add, edit and remove
if (isset($_GET['action']) && $_GET['action'] == 'addcategory') {
<<<<<<< HEAD
    add_category_form($_GET['action'], $type);
} else if (isset($_GET['action']) && $_GET['action'] == 'addcategoryglobal') {
    add_category_form($_GET['action'], $type);
=======
    add_category_form($_GET['action']);
    display_add_category();
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
} else if (isset($_GET['action']) && $_GET['action'] == 'editcategory') {
    edit_category_form($_GET['action'], $type);
} else if (isset($_GET['action']) && $_GET['action'] == 'deletecategory') {
<<<<<<< HEAD
    delete_category_form($_GET['action'], $type);
} else {
    display_add_category($type);
    display_categories($type);
=======
    delete_category_form($_GET['action']);
    display_add_category();
} else {
    display_add_category();
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
}
echo $category->displayCategories($courseId, $sessionId);

Display::display_footer();

// FUNCTIONS
// form to edit a category
<<<<<<< HEAD
function edit_category_form($in_action, $type = 'simple') {
    $in_action = Security::remove_XSS($in_action);
    if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
        $category_id = Security::remove_XSS($_GET['category_id']);

        $objcat = new Testcategory($category_id);

        // initiate the object
        $form = new FormValidator('note', 'post', api_get_self().'?'.api_get_cidreq().'&action='.$in_action.'&category_id='.$category_id."&type=".$type);

        $objcat->getForm($form, 'edit');
=======
/**
 * @todo move to testcategory.class.php
 * @param string $in_action
 */
function edit_category_form($in_action) {
    $in_action = Security::remove_XSS($in_action);
    if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
        $category_id = Security::remove_XSS($_GET['category_id']);
        $objcat = new Testcategory($category_id);

        // initiate the object
        $form = new FormValidator('note', 'post', api_get_self() . '?action=' . $in_action . '&category_id=' . $category_id);

        // settting the form elements
        $form->addElement('header', get_lang('EditCategory'));
        $form->addElement('hidden', 'category_id');
        $form->addElement('text', 'category_name', get_lang('CategoryName'), array('size' => '95'));
        $form->add_html_editor('category_description', get_lang('CategoryDescription'), false, false, array('ToolbarSet' => 'test_category', 'Width' => '90%', 'Height' => '200'));
        $form->addElement('style_submit_button', 'SubmitNote', get_lang('ModifyCategory'), 'class="add"');

        // setting the defaults
        $defaults = array();
        $defaults["category_id"] = $objcat->id;
        $defaults["category_name"] = $objcat->name;
        $defaults["category_description"] = $objcat->description;
        $form->setDefaults($defaults);

        // setting the rules
        $form->addRule('category_name', get_lang('ThisFieldIsRequired'), 'required');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

        // The validation or display
        if ($form->validate()) {
            $check = Security::check_token('post');
            if ($check) {
                $values = $form->getSubmitValues();
                $v_id = $values['category_id'];
                $v_name = $values['category_name'];
                $v_description = $values['category_description'];
                $parent_id = isset($values['parent_id']) ? $values['parent_id'] : null;
                $visibility = isset($values['visibility']) ? $values['visibility'] : 1;
                $objcat = new Testcategory($v_id, $v_name, $v_description, $parent_id, $type, null, $visibility);
                if ($objcat->modifyCategory()) {
                    Display::display_confirmation_message(get_lang('MofidfyCategoryDone'));
                } else {
                    Display::display_confirmation_message(get_lang('ModifyCategoryError'));
                }
            }
            Security::clear_token();
<<<<<<< HEAD
            display_add_category($type);
            display_categories($type);
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        } else {
            display_goback($type);
            $token = Security::get_token();
            $form->addElement('hidden', 'sec_token');
            $form->setConstants(array('sec_token' => $token));
            $form->display();
<<<<<<< HEAD
            display_categories($type);
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        }
    } else {
        Display::display_error_message(get_lang('CannotEditCategory'));
    }
}

// process to delete a category
<<<<<<< HEAD
function delete_category_form($in_action, $type = 'simple')
{
    $in_action = Security::remove_XSS($in_action);
=======
function delete_category_form($in_action) {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
        $category_id = Security::remove_XSS($_GET['category_id']);
        $catobject = new Testcategory($category_id);
        if ($catobject->removeCategory()) {
            Display::display_confirmation_message(get_lang('DeleteCategoryDone'));
        } else {
            Display::display_error_message(get_lang('CannotDeleteCategoryError'));
        }
    } else {
        Display::display_error_message(get_lang('CannotDeleteCategoryError'));
    }
<<<<<<< HEAD
    display_add_category($type);
    display_categories($type);
}

// Form to add a category
function add_category_form($in_action, $type = 'simple')
{
    $in_action = Security::remove_XSS($in_action);
    // Initiate the object
    $form = new FormValidator('note', 'post', api_get_self().'?'.api_get_cidreq().'&action='.$in_action."&type=".$type);
=======
}

/**
 * form to add a category
 * @todo move to testcategory.class.php
 * @param string $in_action
 */
function add_category_form($in_action) {
    $in_action = Security::remove_XSS($in_action);
    // initiate the object
    $form = new FormValidator('note', 'post', api_get_self() . '?action=' . $in_action);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    // Setting the form elements
    $form->addElement('header', get_lang('AddACategory'));
    $form->addElement('text', 'category_name', get_lang('CategoryName'), array('class' => 'span6'));
    $form->add_html_editor('category_description', get_lang('CategoryDescription'), false, false, array('ToolbarSet' => 'test_category', 'Width' => '90%', 'Height' => '200'));
    $form->addElement('select', 'parent_id', get_lang('Parent'), array(), array('id' => 'parent_id'));
    $form->addElement('style_submit_button', 'SubmitNote', get_lang('AddTestCategory'), 'class="add"');

    // Setting the rules
    $form->addRule('category_name', get_lang('ThisFieldIsRequired'), 'required');
    // The validation or display
    if ($form->validate()) {
        $check = Security::check_token('post');
        if ($check) {
            $values = $form->getSubmitValues();
            $parent_id = isset($values['parent_id']) && isset($values['parent_id'][0]) ? $values['parent_id'][0] : null;
            $objcat = new Testcategory(0, $values['category_name'], $values['category_description'], $parent_id, $type, api_get_course_int_id());
            if ($objcat->addCategoryInBDD()) {
                Display::display_confirmation_message(get_lang('AddCategoryDone'));
            } else {
                Display::display_confirmation_message(get_lang('AddCategoryNameAlreadyExists'));
            }
        }
        Security::clear_token();
<<<<<<< HEAD
        display_add_category($type);
        display_categories($type);
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    } else {
        display_goback($type);
        $token = Security::get_token();
        $form->addElement('hidden', 'sec_token');
        $form->setConstants(array('sec_token' => $token));
        $form->display();
    }
}

// Display add category button

function display_add_category($type) {
    echo '<div class="actions">';
    echo '<a href="exercice.php?' . api_get_cidreq() . '">' . Display::return_icon('back.png', get_lang('GoBackToQuestionList'), '', ICON_SIZE_MEDIUM) . '</a>';
    $icon = "question_category.gif";
    if ($type == 'global') {
        $icon = "folder_global_category_new.png";
    }
    echo '<a href="'.api_get_self().'?'.api_get_cidreq().'&action=addcategory&type='.$type.'">'.Display::return_icon($icon, get_lang('AddACategory'), array(), ICON_SIZE_MEDIUM).'</a>';
    echo '</div>';
    echo "<br/>";
    if ($type == 'simple') {
        echo "<fieldset><legend>" . get_lang('QuestionCategory') . "</legend></fieldset>";
    } else {
        echo "<fieldset><legend>".get_lang('QuestionGlobalCategory')."</legend></fieldset>";
    }
}

<<<<<<< HEAD
// Display category list

function display_categories($type = 'simple') {
    global $app;

    $options = array(
        'decorate' => true,
        'rootOpen' => '<ul>',
        'rootClose' => '</ul>',
        'childOpen' => '<li>',
        'childClose' => '</li>',
        'nodeDecorator' => function($row) use ($type) {
            $category_id = $row['iid'];
            $courseId =  $row['cId'];

            $tmpobj = new Testcategory($category_id);
            $nb_question = $tmpobj->getCategoryQuestionsNumber();

            $nb_question_label = $nb_question == 1 ? $nb_question . ' ' . get_lang('Question') : $nb_question . ' ' . get_lang('Questions');
            $nb_question_label = Display::label($nb_question_label, 'info');

            $actions = null;
            if ($courseId == 0 && $type == 'simple') {
                $actions .= Display::return_icon('edit_na.png', get_lang('Edit'), array(), ICON_SIZE_SMALL);
            } else {
                $actions .= '<a href="'.api_get_self().'?action=editcategory&category_id='.$category_id.'&type='.$type.'">'.Display::return_icon('edit.png', get_lang('Edit'), array(), ICON_SIZE_SMALL).'</a>';
            }

            if ($nb_question > 0 && $courseId == 0 && $type == 'simple') {
                $actions .= '<a href="javascript:void(0)" onclick="alert(\'' . protectJSDialogQuote(get_lang('CannotDeleteCategory')) . '\')">';
                $actions .= Display::return_icon('delete_na.png', get_lang('CannotDeleteCategory'), array(), ICON_SIZE_SMALL);
                $actions .='</a>';
            } else {
                $rowname = protectJSDialogQuote($row['title']);
                $actions .= ' <a href="'.api_get_self().'?action=deletecategory&amp;category_id='.$category_id.'&type='.$type.'"';
                $actions .= 'onclick="return confirmDelete(\''.protectJSDialogQuote(get_lang('DeleteCategoryAreYouSure').'['.$rowname).'] ?\', \'id_cat'.$category_id.'\');">';
                $actions .= Display::return_icon('delete.png', get_lang('Delete'), array(), ICON_SIZE_SMALL) . '</a>';
            }

            return $row['title'].' '.$nb_question_label.' '.$actions;
        }
        //'representationField' => 'slug',
        //'html' => true
    );

    // @todo put this in a function
    $repo = $app['orm.em']->getRepository('ChamiloLMS\Entity\CQuizCategory');

    $query = null;
    if ($type == 'global') {
        $query = $app['orm.em']
            ->createQueryBuilder()
            ->select('node')
            ->from('ChamiloLMS\Entity\CQuizCategory', 'node')
            ->where('node.cId = 0')
            ->orderBy('node.root, node.lft', 'ASC')
            ->getQuery();
    } else {
        $query = $app['orm.em']
            ->createQueryBuilder()
            ->select('node')
            ->from('ChamiloLMS\Entity\CQuizCategory', 'node')
            ->where('node.cId = :courseId')
            //->add('orderBy', 'node.title ASC')
            ->orderBy('node.root, node.lft', 'ASC')
            ->setParameter('courseId', api_get_course_int_id())
            ->getQuery();

    }
    $htmlTree = $repo->buildTree($query->getArrayResult(), $options);
    /*
    $htmlTree = $repo->childrenHierarchy(
        null, //starting from root nodes
        false, //load all children, not only direct
        $options
    );*/
    echo $htmlTree;
    return true;
}

=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
// display goback to category list page link
function display_goback($type) {
    $type = Security::remove_XSS($type);
    echo '<div class="actions">';
    echo '<a href="'.api_get_self().'?type='.$type.'">'.Display::return_icon('back.png', get_lang('BackToCategoryList'), array(), 32).'</a>';
    echo '</div>';
}
<<<<<<< HEAD

// To allowed " in javascript dialog box without bad surprises
// replace " with two '
function protectJSDialogQuote($in_txt) {
    $res = $in_txt;
    $res = str_replace("'", "\'", $res);
    $res = str_replace('"', "\'\'", $res); // super astuce pour afficher les " dans les boite de dialogue
    return $res;
}
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
