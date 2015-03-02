<?php
/* For licensing terms, see /license.txt */
/**
 * 	@package chamilo.admin
 */
/**
 * Code
 */
// name of the language file that needs to be included
$language_file = 'admin';
$cidReset = true;
require_once '../inc/global.inc.php';
require_once api_get_path(LIBRARY_PATH).'course_category.lib.php';
$this_section = SECTION_PLATFORM_ADMIN;

api_protect_admin_script();
<<<<<<< HEAD
$category = Database::escape_string($_GET['category']);

$action = isset($_GET['action']) ? $_GET['action'] : null;
=======
$category = isset($_GET['category']) ? $_GET['category'] : null;

if (!empty($category)) {
    $parentInfo = getCategory($category);
}
$categoryId = isset($_GET['id']) ? Security::remove_XSS($_GET['id']) : null;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

if (!empty($categoryId)) {
    $categoryInfo = getCategory($categoryId);
}
$action = isset($_GET['action']) ? $_GET['action'] : null;

$errorMsg = '';
<<<<<<< HEAD
$id = isset($_GET['id']) ? $_GET['id'] : null;

$categoryCode = isset($_POST['categoryCode']) ? $_POST['categoryCode'] : null;
$categoryName = isset($_POST['categoryName']) ? $_POST['categoryName'] : null;

$formSent = isset($_POST['formSent']) ? $_POST['formSent'] : false;

$canHaveCourses = 0;

if (!empty($action)) {
    if ($action == 'delete') {
        if (api_get_multiple_access_url()) {
            if (api_get_current_access_url_id() == 1) {
                deleteNode($id);
=======
if (!empty($action)) {
    if ($action == 'delete') {
        if (api_get_multiple_access_url()) {
            if (api_get_current_access_url_id() == 1 ||
                (isset($_configuration['enable_multiple_url_support_for_course_category']) &&
                $_configuration['enable_multiple_url_support_for_course_category'])
            ) {
                deleteNode($categoryId);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
                header('Location: ' . api_get_self() . '?category=' . Security::remove_XSS($category));
                exit();
            }
        } else {
            deleteNode($categoryId);
            header('Location: ' . api_get_self() . '?category=' . Security::remove_XSS($category));
            exit();
        }
<<<<<<< HEAD
    } elseif (($action == 'add' || $action == 'edit') && $_POST['formSent']) {
        $_POST['categoryCode'] = trim($_POST['categoryCode']);
        $_POST['categoryName'] = trim($_POST['categoryName']);

        if (!empty($_POST['categoryCode']) && !empty($_POST['categoryName'])) {
            if ($action == 'add') {
                $ret = addNode($_POST['categoryCode'], $_POST['categoryName'], $_POST['canHaveCourses'], $category);
            } else {
                $ret = editNode($_POST['categoryCode'], $_POST['categoryName'], $_POST['canHaveCourses'], $id);
            }

            if ($ret) {
                $action = '';
            } else {
                $errorMsg = get_lang('CatCodeAlreadyUsed');
            }
=======
    } elseif (($action == 'add' || $action == 'edit') && isset($_POST['formSent']) && $_POST['formSent']) {
        if ($action == 'add') {
            $ret = addNode($_POST['code'], $_POST['name'], $_POST['auth_course_child'], $category);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        } else {
            $ret = editNode($_POST['code'], $_POST['name'], $_POST['auth_course_child'], $categoryId);
        }
        if ($ret) {
            $action = '';
        } else {
            $errorMsg = get_lang('CatCodeAlreadyUsed');
        }
<<<<<<< HEAD
    } elseif ($action == 'edit') {

        if (!empty($id)) {
            $categoryCode = $id;
            $sql = "SELECT name, auth_course_child FROM $tbl_category WHERE code='$id'";
            $result = Database::query($sql);
            list($categoryName, $canHaveCourses) = Database::fetch_row($result);
            $canHaveCourses = ($canHaveCourses == 'FALSE') ? 0 : 1;
        }
    } elseif ($action == 'moveUp') {
        moveNodeUp($id, $_GET['tree_pos'], $category);

=======
    } elseif ($action == 'moveUp') {
        moveNodeUp($categoryId, $_GET['tree_pos'], $category);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        header('Location: ' . api_get_self() . '?category=' . Security::remove_XSS($category));
        exit();
    }
}

$tool_name = get_lang('AdminCategories');
$interbreadcrumb[] = array('url' => 'index.php', "name" => get_lang('PlatformAdmin'));

Display::display_header($tool_name);

if ($action == 'add' || $action == 'edit') {
<<<<<<< HEAD
    if ((api_get_multiple_access_url() && api_get_current_access_url_id() == 1) || !api_get_multiple_access_url() ) { ?>
    <div class="actions">
        <a href="<?php echo api_get_self(); ?>?category=<?php echo Security::remove_XSS($category); ?>"><?php echo Display::return_icon('folder_up.png', get_lang("Back"), '', ICON_SIZE_MEDIUM);
    if (!empty($category)) echo ' (' . Security::remove_XSS($category) . ')'; ?></a>
    </div>
    <?php
    $form_title = ($action == 'add') ? get_lang('AddACategory') : get_lang('EditNode');
    if (!empty($category)) {
        $form_title .= ' ' . get_lang('Into') . ' ' . Security::remove_XSS($category);
    }
    $form = new FormValidator('course_category');
    $form->addElement('header', '', $form_title);
    $form->display();
    ?>
    <form method="post" action="<?php echo api_get_self(); ?>?action=<?php echo Security::remove_XSS($action); ?>&category=<?php echo Security::remove_XSS($category); ?>&amp;id=<?php echo $id; ?>">
        <input type="hidden" name="formSent" value="1" />
        <table border="0" cellpadding="5" cellspacing="0">
    <?php
    if (!empty($errorMsg)) {
        ?>
                <tr>
                    <td colspan="2">

        <?php
        Display::display_normal_message($errorMsg); //main API
        ?>

                    </td>
                </tr>

        <?php
    }
    ?>

            <tr>
                <td nowrap="nowrap"><?php echo get_lang("CategoryCode"); ?> :</td>
                <td><input type="text" name="categoryCode" size="20" maxlength="20" value="<?php echo api_htmlentities(stripslashes($categoryCode), ENT_QUOTES, $charset); ?>" /></td>
            </tr>
            <tr>
                <td nowrap="nowrap"><?php echo get_lang("CategoryName"); ?> :</td>
                <td><input type="text" name="categoryName" size="20" maxlength="100" value="<?php echo api_htmlentities(stripslashes($categoryName), ENT_QUOTES, $charset); ?>" /></td>
            </tr>
            <tr>
                <td nowrap="nowrap"><?php echo get_lang("AllowCoursesInCategory"); ?></td>
                <td>
                    <input class="checkbox" type="radio" name="canHaveCourses" value="0" <?php if (($action == 'edit' && !$canHaveCourses) || ($action == 'add' && $formSent && !$canHaveCourses)) echo 'checked="checked"'; ?> /><?php echo get_lang("No"); ?>
                    <input class="checkbox" type="radio" name="canHaveCourses" value="1" <?php if (($action == 'edit' && $canHaveCourses) || ($action == 'add' && !$formSent || $canHaveCourses)) echo 'checked="checked"'; ?> /><?php echo get_lang("Yes"); ?>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
    <?php
    if (!empty($id)) {
        $class = "save";
        $text = get_lang('CategoryMod');
    } else {
        $class = "add";
        $text = get_lang('AddCategory');
    }
    ?>
                <td><button type="submit" class="<?php echo $class; ?>" value="<?php echo $text; ?>" ><?php echo $text; ?></button></td>
            </tr>
        </table>
    </form>

<?php  } elseif (api_get_multiple_access_url() && api_get_current_access_url_id() != 1) {
           Display :: display_error_message(get_lang('CourseCategoriesAreGlobal'));
       }
            } else {
    if ($delError == 0) {           ?>
    <div class="actions">
                <?php
                if (!empty($category) && empty($action)) {
                    $myquery = "SELECT parent_id FROM $tbl_category WHERE code='$category'";
                    $result = Database::query($myquery);
                    $parent_id = 0;
                    if (Database::num_rows($result) > 0) {
                        $parent_id = Database::fetch_array($result);
                    }

                    $parent_id['parent_id'] ? $link = ' (' . $parent_id['parent_id'] . ')' : $link = '';
                    ?>

            <a href="<?php echo api_get_self(); ?>?category=<?php echo $parent_id['parent_id']; ?>"><?php echo Display::return_icon('folder_up.png', get_lang("Back"), '', ICON_SIZE_MEDIUM);
        if (!empty($parent_id)) echo $link ?></a>

            <?php
        }
        ?>
        <?php
        if (!empty($category)) {
            $CategoryInto = ' ' . get_lang('Into') . ' ' . Security::remove_XSS($category);
        } else {
            $CategoryInto = '';
        }
        ?>
        <a href="<?php echo api_get_self(); ?>?category=<?php echo Security::remove_XSS($category); ?>&amp;action=add"><?php echo Display::return_icon('new_folder.png', get_lang("AddACategory") . $CategoryInto, '', ICON_SIZE_MEDIUM); ?></a>
    </div>
    <ul>

        <?php
        if (count($Categories) > 0) {
            foreach ($Categories as $enreg) {
                ?>
                <li>
                    <a href="<?php echo api_get_self(); ?>?category=<?php echo Security::remove_XSS($enreg['code']); ?>"><?php Display::display_icon('folder_document.gif', get_lang('OpenNode')); ?></a>
                    <a href="<?php echo api_get_self(); ?>?category=<?php echo Security::remove_XSS($category); ?>&amp;action=edit&amp;id=<?php echo Security::remove_XSS($enreg['code']); ?>"><?php Display::display_icon('edit.gif', get_lang('EditNode')); ?></a>
                    <a href="<?php echo api_get_self(); ?>?category=<?php echo Security::remove_XSS($category); ?>&amp;action=delete&amp;id=<?php echo Security::remove_XSS($enreg['code']); ?>" onclick="javascript:if (!confirm('<?php echo addslashes(get_lang('ConfirmYourChoice')); ?>'))
                                return false;"><?php Display::display_icon('delete.gif', get_lang('DeleteNode')); ?></a>
                    <a href="<?php echo api_get_self(); ?>?category=<?php echo Security::remove_XSS($category); ?>&amp;action=moveUp&amp;id=<?php echo Security::remove_XSS($enreg['code']); ?>&amp;tree_pos=<?php echo $enreg['tree_pos']; ?>"><?php Display::display_icon('up.gif', get_lang('UpInSameLevel')); ?></a>
                <?php echo $enreg['name']; ?>
                    (<?php echo $enreg['children_count']; ?> <?php echo get_lang('CategoriesNumber'); ?> - <?php echo $enreg['nbr_courses']; ?> <?php echo get_lang('Courses'); ?>)
                </li>
            <?php
        }
        unset($Categories);
    } else {
        echo get_lang("NoCategories");
    }
    } else {
        Display :: display_error_message(get_lang('CourseCategoriesAreGlobal'));
        
    }?>
    </ul>



    <?php
}

Display::display_footer();


function deleteNode($node) {
    global $tbl_category, $tbl_course;
    $node = Database::escape_string($node);

    $result = Database::query("SELECT parent_id,tree_pos FROM $tbl_category WHERE code='$node'");

    if ($row = Database::fetch_array($result)) {
        if (!empty($row['parent_id'])) {
            Database::query("UPDATE $tbl_course SET category_code='" . $row['parent_id'] . "' WHERE category_code='$node'");
            Database::query("UPDATE $tbl_category SET parent_id='" . $row['parent_id'] . "' WHERE parent_id='$node'");
        } else {
            Database::query("UPDATE $tbl_course SET category_code='' WHERE category_code='$node'");
            Database::query("UPDATE $tbl_category SET parent_id=NULL WHERE parent_id='$node'");
        }

        Database::query("UPDATE $tbl_category SET tree_pos=tree_pos-1 WHERE tree_pos > '" . $row['tree_pos'] . "'");
        Database::query("DELETE FROM $tbl_category WHERE code='$node'");

        if (!empty($row['parent_id'])) {
            updateFils($row['parent_id']);
        }
=======
    if ((api_get_multiple_access_url() && api_get_current_access_url_id() == 1) ||
        !api_get_multiple_access_url() ||
        (isset($_configuration['enable_multiple_url_support_for_course_category']) &&
         $_configuration['enable_multiple_url_support_for_course_category'])
    ) {
        echo '<div class="actions">';
        echo Display::url(
            Display::return_icon('folder_up.png', get_lang("Back"), '', ICON_SIZE_MEDIUM),
            api_get_path(WEB_CODE_PATH).'admin/course_category.php?category='.$category
        );
        echo '</div>';

        $form_title = ($action == 'add') ? get_lang('AddACategory') : get_lang('EditNode');
        if (!empty($category)) {
            $form_title .= ' ' . get_lang('Into') . ' ' . Security::remove_XSS($category);
        }
        $url = api_get_self().'?action='.Security::remove_XSS($action).'&category='.Security::remove_XSS($category).'&id='.$categoryId;
        $form = new FormValidator('course_category', 'post', $url);
        $form->addElement('header', '', $form_title);
        $form->addElement('hidden', 'formSent', 1);
        $form->addElement('text', 'code', get_lang("CategoryCode"));
        $form->addElement('text', 'name', get_lang("CategoryName"));
        $form->addRule('name', get_lang('PleaseEnterCategoryInfo'), 'required');
        $form->addRule('code', get_lang('PleaseEnterCategoryInfo'), 'required');
        $group = array(
            $form->createElement('radio', 'auth_course_child', get_lang("AllowCoursesInCategory"), get_lang('Yes'), 'TRUE'),
            $form->createElement('radio', 'auth_course_child', null, get_lang('No'), 'FALSE')
        );
        $form->addGroup($group, null, get_lang("AllowCoursesInCategory"));

        if (!empty($categoryInfo)) {
            $class = "save";
            $text = get_lang('CategoryMod');
            $form->setDefaults($categoryInfo);
        } else {
            $class = "add";
            $text = get_lang('AddCategory');
            $form->setDefaults(array('auth_course_child' => 'TRUE'));
        }
        $form->addElement('button', 'submit', $text);
        $form->display();
    } elseif (api_get_multiple_access_url() && api_get_current_access_url_id() != 1) {
        // If multiple URLs and not main URL, prevent edition and inform user
        Display::display_warning_message(get_lang('CourseCategoriesAreGlobal'));
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    }
} else {
    // If multiple URLs and not main URL, prevent deletion and inform user
    if ($action == 'delete' && api_get_multiple_access_url() && api_get_current_access_url_id() != 1) {
        Display::display_warning_message(get_lang('CourseCategoriesAreGlobal'));
    }
<<<<<<< HEAD

    $result = Database::query("SELECT MAX(tree_pos) AS maxTreePos FROM $tbl_category");

    $row = Database::fetch_array($result);

    $tree_pos = $row['maxTreePos'] + 1;

    $code = CourseManager::generate_course_code($code);
    Database::query("INSERT INTO $tbl_category(name,code,parent_id,tree_pos,children_count,auth_course_child) VALUES('$name','$code'," . (empty($parent_id) ? "NULL" : "'$parent_id'") . ",'$tree_pos','0','$canHaveCourses')");

    updateFils($parent_id);

    return true;
}

function editNode($code, $name, $canHaveCourses, $old_code) {
    global $tbl_category, $tbl_course;

    $canHaveCourses = $canHaveCourses ? 'TRUE' : 'FALSE';
    $code = Database::escape_string($code);
    $name = Database::escape_string($name);
    $old_code = Database::escape_string($old_code);

    if ($code != $old_code) {
        $result = Database::query("SELECT 1 FROM $tbl_category WHERE code='$code'");
        if (Database::num_rows($result)) {
            return false;
        }
    }
    $code = CourseManager::generate_course_code($code);
    Database::query("UPDATE $tbl_category SET name='$name', code='$code',auth_course_child='$canHaveCourses' WHERE code='$old_code'");
    $sql = "UPDATE $tbl_course SET category_code = '$code' WHERE category_code = '$old_code' ";
    Database::query($sql);

    return true;
}

function moveNodeUp($code, $tree_pos, $parent_id) {
    global $tbl_category;
    $code = Database::escape_string($code);
    $tree_pos = Database::escape_string($tree_pos);
    $parent_id = Database::escape_string($parent_id);

    $result = Database::query("SELECT code,tree_pos FROM $tbl_category WHERE parent_id " . (empty($parent_id) ? "IS NULL" : "='$parent_id'") . " AND tree_pos<'$tree_pos' ORDER BY tree_pos DESC LIMIT 0,1");
=======
    echo '<div class="actions">';
    $link = null;
    if (!empty($parentInfo)) {
        $parentCode = $parentInfo['parent_id'];
        echo Display::url(
            Display::return_icon('back.png', get_lang("Back"), '', ICON_SIZE_MEDIUM),
            api_get_path(WEB_CODE_PATH).'admin/course_category.php?category='.$parentCode
        );
    }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    if (empty($parentInfo) || $parentInfo['auth_cat_child'] == 'TRUE') {
        echo Display::url(
            Display::return_icon('new_folder.png', get_lang("AddACategory"), '', ICON_SIZE_MEDIUM),
            api_get_path(WEB_CODE_PATH).'admin/course_category.php?action=add&category='.$category
        );
    }

    echo '</div>';
    if (!empty($parentInfo)) {
        echo Display::page_subheader($parentInfo['name'].' ('.$parentInfo['code'].')');
    }
    echo listCategories($category);
}

<<<<<<< HEAD
function compterFils($pere, $cpt) {
    global $tbl_category;
    $pere = Database::escape_string($pere);
    $result = Database::query("SELECT code FROM $tbl_category WHERE parent_id='$pere'");

    while ($row = Database::fetch_array($result)) {
        $cpt = compterFils($row['code'], $cpt);
    }

    return ($cpt + 1);
}
=======
Display::display_footer();
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
