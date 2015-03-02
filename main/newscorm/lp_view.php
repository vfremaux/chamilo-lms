<?php
/* For licensing terms, see /license.txt */

/**
 * This file was originally the copy of document.php, but many modifications happened since then ;
 * the direct file view is not needed anymore, if the user uploads a scorm zip file, a directory
 * will be automatically created for it, and the files will be uncompressed there for example ;
 *
 * @package chamilo.learnpath
 * @author Yannick Warnier <ywarnier@beeznest.org> - redesign
 * @author Denes Nagy, principal author
 * @author Isthvan Mandak, several new features
 * @author Roan Embrechts, code improvements and refactoring
 */
/**
 * Code
 */

use \ChamiloSession as Session;

$use_anonymous = true;

$_SESSION['whereami'] = 'lp/view';
$this_section = SECTION_COURSES;

if ($lp_controller_touched != 1) {
    header('location: lp_controller.php?action=view&item_id='.intval($_REQUEST['item_id']));
    exit;
}

/* Libraries */
require_once '../inc/global.inc.php';
require_once 'learnpath.class.php';
require_once 'learnpathItem.class.php';

//To prevent the template class
$show_learnpath = true;

api_protect_course_script();

$lp_id = intval($_GET['lp_id']);

// Check if the learning path is visible for student - (LP requisites)
if (!api_is_allowed_to_edit(null, true, true, false) && !learnpath::is_lp_visible_for_student($lp_id, api_get_user_id())) {
    api_not_allowed(true);
}

// Checking visibility (eye icon)
$visibility = api_get_item_visibility(
    api_get_course_info(),
    TOOL_LEARNPATH,
    $lp_id,
    $action,
    api_get_user_id(),
    api_get_session_id()
);
if (!api_is_allowed_to_edit(false, true, false, false) && intval($visibility) == 0) {
    api_not_allowed(true);
}

if (empty($_SESSION['oLP'])) {
    api_not_allowed(true);
}

$debug = 0;
/** @var learnpath $learnPath */
$learnPath = $_SESSION['oLP'];

$learnPath->setError(null);
$lp_item_id = $learnPath->get_current_item_id();
$lp_type = $learnPath->get_type();

$course_code = api_get_course_id();
$course_id = api_get_course_int_id();
$user_id = api_get_user_id();
$platform_theme = api_get_setting('stylesheets');
$my_style = $platform_theme;

<<<<<<< HEAD
//$htmlHeadXtra[] = $app['template']->fetch('default/javascript/newscorm/minipanel.tpl');
/*
if ($_SESSION['oLP']->mode == 'embedframe' || $_SESSION['oLP']->get_hide_toc_frame() == 1) {
    $htmlHeadXtra[] = '<script>
        $(document).ready(function(){
            toogle_minipanel();
        });
        </script>';
}*/
=======
$htmlHeadXtra[] = '<script src="'.api_get_path(WEB_LIBRARY_PATH).'javascript/jquery.lp_minipanel.js" type="text/javascript" language="javascript"></script>';
$htmlHeadXtra[] = '<script>
$(document).ready(function() {
	$("div#log_content_cleaner").bind("click", function() {
    	$("div#log_content").empty();
	});
	//jQuery("video:not(.skip), audio:not(.skip)").mediaelementplayer();
});
var chamilo_xajax_handler = window.oxajax;
</script>';

if ($_SESSION['oLP']->mode == 'embedframe' || $_SESSION['oLP']->get_hide_toc_frame()==1 ) {
    $htmlHeadXtra[] = '<script>
    $(document).ready(function() {
        toogle_minipanel();
    });
    </script>';
}
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

// Impress js
$lpId = $learnPath->get_id();
if ($learnPath->mode == 'impress') {
    $url = api_get_path(WEB_CODE_PATH)."newscorm/lp_impress.php?lp_id=$lpId&".api_get_cidreq();
    header("Location: $url");
    exit;
}

// Prepare variables for the test tool (just in case) - honestly, this should disappear later on.
$_SESSION['scorm_view_id'] = $learnPath->get_view_id();
$_SESSION['scorm_item_id'] = $lp_item_id;

// Reinit exercises variables to avoid spacename clashes (see exercise tool)
if (isset($exerciseResult) || isset($_SESSION['exerciseResult'])) {
    Session::erase('exerciseResult');
    Session::erase('objExercise');
    Session::erase('questionList');
}

/** @var Template $template */

$template = $app['template'];
$template->assign('course_code', $course_code);

$template->assign('lp_id', $lpId);
$get_toc_list = $learnPath->get_toc();
$type_quiz = false;

foreach ($get_toc_list as $toc) {
    if ($toc['id'] == $lp_item_id && ($toc['type'] == 'quiz')) {
        $type_quiz = true;
    }
}

if (!isset($src)) {
    $src = null;
    switch ($lp_type) {
        case 1:
            $learnPath->stop_previous_item();
            $htmlHeadXtra[] = '<script src="'.api_get_path(WEB_CODE_PATH).'newscorm/scorm_api.php" type="text/javascript"></script>';
            $prereq_check = $learnPath->prerequisites_match($lp_item_id);
            if ($prereq_check === true) {
                $src = $learnPath->get_link('http', $lp_item_id, $get_toc_list);

                // Prevents FF 3.6 + Adobe Reader 9 bug see BT#794 when calling a pdf file in a LP.
                $file_info = parse_url($src);
                $file_info = pathinfo($file_info['path']);
<<<<<<< HEAD
                if (isset($file_info['extension']) && api_strtolower(substr($file_info['extension'], 0, 3) == 'pdf')) {
                    $src = api_get_path(WEB_CODE_PATH).'newscorm/lp_view_item.php?lp_item_id='.$lp_item_id;
=======
                if (isset($file_info['extension']) &&
                    api_strtolower(substr($file_info['extension'], 0, 3) == 'pdf')
                ) {
                    $src = api_get_path(WEB_CODE_PATH).'newscorm/lp_view_item.php?lp_item_id='.$lp_item_id.'&'.api_get_cidreq();
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
                }
                $learnPath->start_current_item(); // starts time counter manually if asset
            } else {
                $src = 'blank.php?error=prerequisites';
            }
            break;
        case 2:
            // save old if asset
            $learnPath->stop_previous_item(); // save status manually if asset
            $htmlHeadXtra[] = '<script src="'.api_get_path(WEB_CODE_PATH).'newscorm/scorm_api.php" type="text/javascript" language="javascript"></script>';
            $prereq_check = $learnPath->prerequisites_match($lp_item_id);
            if ($prereq_check === true) {
                $src = $learnPath->get_link('http', $lp_item_id, $get_toc_list);
                $learnPath->start_current_item(); // starts time counter manually if asset
            } else {
                $src = 'blank.php?error=prerequisites';
            }
            break;
        case 3:
            // aicc
            $learnPath->stop_previous_item(); // save status manually if asset
            $htmlHeadXtra[] = '<script src="'.$learnPath->get_js_lib().'" type="text/javascript" language="javascript"></script>';
            $prereq_check = $learnPath->prerequisites_match($lp_item_id);
            if ($prereq_check === true) {
                $src = $learnPath->get_link('http', $lp_item_id, $get_toc_list);
                $learnPath->start_current_item(); // starts time counter manually if asset
            } else {
                $src = 'blank.php';
            }
            break;
        case 4:
            break;
    }
}

$autostart = 'true';

if (!$learnPath->check_attempts()) {
    api_not_allowed(true);
}

if ($type_quiz && !empty($_REQUEST['exeId']) && isset($lp_id) && isset($_GET['lp_item_id'])) {
    global $src;

    $learnPath->items[$learnPath->current]->write_to_db();

    $TBL_TRACK_EXERCICES = Database::get_main_table(TABLE_STATISTIC_TRACK_E_EXERCICES);
    $TBL_LP_ITEM_VIEW = Database::get_course_table(TABLE_LP_ITEM_VIEW);
    $TBL_LP_ITEM = Database::get_course_table(TABLE_LP_ITEM);
    $safe_item_id = intval($_GET['lp_item_id']);
    $safe_id = $lp_id;
    $safe_exe_id = intval($_REQUEST['exeId']);

    if ($safe_id == strval(intval($safe_id)) && $safe_item_id == strval(intval($safe_item_id))) {

<<<<<<< HEAD
        $sql = 'SELECT start_date, exe_date, exe_result, exe_weighting FROM '.$TBL_TRACK_EXERCICES.' WHERE exe_id = '.$safe_exe_id;
        if ($debug) {
            error_log($sql);
        }
=======
        $sql = 'SELECT start_date, exe_date, exe_result, exe_weighting FROM ' . $TBL_TRACK_EXERCICES . ' WHERE exe_id = '.$safe_exe_id;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        $res = Database::query($sql);
        $row_dates = Database::fetch_array($res);

        $time_start_date = api_strtotime($row_dates['start_date'], 'UTC');
        $time_exe_date = api_strtotime($row_dates['exe_date'], 'UTC');

        $mytime = ((int)$time_exe_date - (int)$time_start_date);
        $score = (float)$row_dates['exe_result'];
        $max_score = (float)$row_dates['exe_weighting'];

        $sql_upd_max_score = "UPDATE $TBL_LP_ITEM SET max_score = '$max_score' WHERE c_id = $course_id AND id = '".$safe_item_id."'";
<<<<<<< HEAD
        if ($debug) {
            error_log($sql_upd_max_score);
        }
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        Database::query($sql_upd_max_score);

        $sql_last_attempt = "SELECT id FROM $TBL_LP_ITEM_VIEW  WHERE c_id = $course_id AND lp_item_id = '$safe_item_id' AND lp_view_id = '".$learnPath->lp_view_id."' order by id desc limit 1";
        $res_last_attempt = Database::query($sql_last_attempt);
<<<<<<< HEAD
        if ($debug) {
            error_log($sql_last_attempt);
        }
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

        if (Database::num_rows($res_last_attempt)) {
            $row_last_attempt = Database::fetch_row($res_last_attempt);
            $lp_item_view_id = $row_last_attempt[0];
            $sql_upd_score = "UPDATE $TBL_LP_ITEM_VIEW SET status = 'completed' , score = $score, total_time = $mytime
                              WHERE id='".$lp_item_view_id."' AND c_id = $course_id ";

            if ($debug) {
                error_log($sql_upd_score);
            }
            Database::query($sql_upd_score);

            $update_query = "UPDATE $TBL_TRACK_EXERCICES SET orig_lp_item_view_id = $lp_item_view_id  WHERE exe_id = ".$safe_exe_id;
            Database::query($update_query);
        }
    }
    if (intval($_GET['fb_type']) > 0) {
        $src = 'blank.php?msg=exerciseFinished';
    } else {
        $src = api_get_path(WEB_CODE_PATH).'exercice/result.php?origin=learnpath&id='.$safe_exe_id;

        if ($debug) {
            error_log('Calling URL: '.$src);
        }
    }
    $autostart = 'false';
}

$learnPath->set_previous_item($lp_item_id);
$lpName = Security::remove_XSS($learnPath->get_name());

$save_setting = api_get_setting('show_navigation_menu');
global $_setting;
$_setting['show_navigation_menu'] = 'false';
$scorm_css_header = true;
// Sets the css theme of the LP this call is also use at the frames (toc, nav, message).
$lp_theme_css = $learnPath->get_theme();

// Check if audio recorder needs to be in studentview.
if (isset($_SESSION['status']) && $_SESSION['status'][$course_code] == 5) {
    $audio_recorder_studentview = true;
} else {
    $audio_recorder_studentview = false;
}

// Set flag to ensure lp_header.php is loaded by this script (flag is unset in lp_header.php).
$_SESSION['loaded_lp_view'] = true;

$display_none = '';
$margin_left = '305px';

// Media player code

$display_mode = $learnPath->mode;
$scorm_css_header = true;
$lp_theme_css = $learnPath->get_theme();

// Setting up the CSS theme if exists.
if (!empty ($lp_theme_css) && !empty ($mycourselptheme) && $mycourselptheme != -1 && $mycourselptheme == 1) {
    global $lp_theme_css;
} else {
    $lp_theme_css = $my_style;
}

$progress_bar = $learnPath->get_progress_bar('', -1, '', true);
$navigation_bar = $learnPath->get_navigation_bar();
$mediaplayer = $learnPath->get_mediaplayer($autostart);

$tbl_lp_item = Database::get_course_table(TABLE_LP_ITEM);
$show_audioplayer = false;
// Getting all the information about the item.
$sql = "SELECT audio FROM ".$tbl_lp_item." WHERE c_id = $course_id AND lp_id = '".$learnPath->lp_id."'";
$res_media = Database::query($sql);

if (Database::num_rows($res_media) > 0) {
<<<<<<< HEAD
    while ($row_media = Database::fetch_array($res_media)) {
=======
    while ($row_media= Database::fetch_array($res_media)) {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        if (!empty($row_media['audio'])) {
            $show_audioplayer = true;
            break;
        }
    }
}
<<<<<<< HEAD

$template->assign('api_is_allowed_to_edit', api_is_allowed_to_edit(null, true, false, false));
if ($is_allowed_to_edit) {
    global $interbreadcrumb;
    $interbreadcrumb[] = array(
        'url' => 'lp_controller.php?action=list&isStudentView=false',
        'name' => get_lang('LearningPaths')
    );
    $interbreadcrumb[] = array(
        'url' => api_get_self()."?action=add_item&type=step&lp_id=".$learnPath->lp_id."&isStudentView=false",
        'name' => $learnPath->get_name()
    );
    $interbreadcrumb[] = array('url' => '#', 'name' => get_lang('Preview'));
    $template->assign('breadcrumb', $app['template']->returnBreadcrumb($interbreadcrumb, null, null));
}

if ($learnPath->get_preview_image() != '') {
    $picture = getimagesize(api_get_path(SYS_COURSE_PATH).api_get_course_path().'/upload/learning_path/images/'.$learnPath->get_preview_image());
    $style = null;
    if ($picture['1'] < 96) {
        $style = ' style="padding-top:'.((94 - $picture['1']) / 2).'px;" ';
    }
    $size = ($picture['0'] > 104 && $picture['1'] > 96) ? ' width="104" height="96" ' : $style;
    $my_path = $learnPath->get_preview_image_path();
    $picture = $my_path;
} else {
    $picture = Display::return_icon('unknown_250_100.jpg', null, array(), ICON_SIZE_SMALL, false, true);
}

$app['template']->assign('course_url', api_get_cidreq());
$app['template']->assign('picture', $picture);
$app['template']->assign('navigation_bar', $navigation_bar);
$app['template']->assign('progress_bar', $progress_bar);
$app['template']->assign('author', $learnPath->get_author());
$app['template']->assign('mediaplayer', $mediaplayer);
$app['template']->assign('table_of_contents', $learnPath->get_html_toc($get_toc_list));
$app['template']->assign('lp_name', $lpName);

// hub 26-05-2010 Fullscreen or not fullscreen
$height = '100%';
if ($learnPath->mode == 'fullscreen') {
    $iframe = '<iframe id="content_id_blank" name="content_name_blank" src="blank.php" border="0" frameborder="0" style="width:100%;height:'.$height.'" allowFullScreen></iframe>';
} else {
    $iframe = '<iframe id="content_id" name="content_name" src="'.$src.'" border="0" frameborder="0" style="display: block; width:100%; height:'.$height.'" allowFullScreen></iframe>';
}
$app['template']->assign('iframe', $iframe);
$app['template']->assign('show_glossary', api_get_setting('show_glossary_in_extra_tools'));
$app['template']->assign('glossary_type', api_get_setting('show_glossary_in_documents'));

=======
echo '<div id="learning_path_main" style="width:100%;height:100%;">';
$is_allowed_to_edit = api_is_allowed_to_edit(null, true, false, false);
if ($is_allowed_to_edit) {
    echo '<div id="learning_path_breadcrumb_zone">';
    global $interbreadcrumb;
    $interbreadcrumb[] = array('url' => 'lp_controller.php?action=list&isStudentView=false', 'name' => get_lang('LearningPaths'));
    $interbreadcrumb[] = array('url' => api_get_self()."?action=add_item&type=step&lp_id=".$_SESSION['oLP']->lp_id."&isStudentView=false", 'name' => $_SESSION['oLP']->get_name());
    $interbreadcrumb[] = array('url' => '#', 'name' => get_lang('Preview'));
    echo return_breadcrumb($interbreadcrumb, null, null);
    echo '</div>';
}
    echo '<div id="learning_path_left_zone" style="'.$display_none.'"> ';
    echo '<div id="header">
            <table>
                <tr>
                    <td>';
                        echo '<a href="lp_controller.php?action=return_to_course_homepage&'.api_get_cidreq().'" target="_self" onclick="javascript: window.parent.API.save_asset();">
                            <img src="../img/btn_home.png" />
                        </a>
                    </td>
                    <td>';
                         if ($is_allowed_to_edit) {
                            echo '<a class="link no-border" href="lp_controller.php?isStudentView=false&action=return_to_course_homepage&'.api_get_cidreq().'" target="_self" onclick="javascript: window.parent.API.save_asset();">';
                         } else {
                            echo '<a class="link no-border" href="lp_controller.php?action=return_to_course_homepage&'.api_get_cidreq().'" target="_self" onclick="javascript: window.parent.API.save_asset();">';
                         }
                        echo get_lang('CourseHomepageLink').'
                        </a>
                    </td>
                </tr>
            </table>
        </div>';
?>
        <!-- end header -->

        <!-- Author image preview -->
        <div id="author_image">
            <div id="author_icon">
                <?php
                if ($_SESSION['oLP']->get_preview_image() != '') {
                    $picture = getimagesize(api_get_path(SYS_COURSE_PATH).api_get_course_path().'/upload/learning_path/images/'.$_SESSION['oLP']->get_preview_image());
                    $style = null;
                    if ($picture['1'] < 96) {
                        $style = ' style="padding-top:'.((94 -$picture['1'])/2).'px;" ';
                    }
                    $size = ($picture['0'] > 104 && $picture['1'] > 96 )? ' width="104" height="96" ': $style;
                    $my_path = $_SESSION['oLP']->get_preview_image_path();
                    echo '<img src="'.$my_path.'">';
                } else {
                    echo Display :: display_icon('unknown_250_100.jpg');
                }
                ?>
            </div>
            <div id="lp_navigation_elem">
                <?php echo $navigation_bar; ?>
                <div id="progress_bar">
                    <?php echo $progress_bar; ?>
                </div>
            </div>
        </div>
        <!-- end image preview Layout -->

        <div id="author_name">
            <?php echo $_SESSION['oLP']->get_author(); ?>
        </div>

        <!-- media player layout -->
        <?php
        if ($show_audioplayer) {
            echo '<div id="lp_media_file">';
            echo $mediaplayer;
            echo '</div>';
        }
        ?>
        <!-- end media player layout -->

        <!-- TOC layout -->
        <div id="toc_id" name="toc_name" style="overflow: auto; padding:0;margin-top:0px;width:100%;float:left">
            <div id="learning_path_toc">
                <?php echo $_SESSION['oLP']->get_html_toc($get_toc_list); ?>
            </div>
        </div>
        <!-- end TOC layout -->
    </div>
    <!-- end left zone -->

    <!-- right zone -->
    <div id="learning_path_right_zone" style="margin-left:<?php echo $margin_left;?>;height:100%">
    <?php
        // hub 26-05-2010 Fullscreen or not fullscreen
        $height = '100%';
        if ($_SESSION['oLP']->mode == 'fullscreen') {
            echo '<iframe id="content_id_blank" name="content_name_blank" src="blank.php" border="0" frameborder="0" style="width:100%;height:'.$height.'" ></iframe>';
        } else {
            echo '<iframe id="content_id" name="content_name" src="'.$src.'" border="0" frameborder="0" style="display: block; width:100%;height:'.$height.'"></iframe>';
        }
    ?>
    </div>
    <!-- end right Zone -->
</div>

<script>
    // Resize right and left pane to full height (HUB 20-05-2010).
    function updateContentHeight() {
        document.body.style.overflow = 'hidden';
        var IE = window.navigator.appName.match(/microsoft/i);
        var heightHeader = ($('#header').height())? $('#header').height() : 0 ;
        var heightAuthorImg = ($('#author_image').height())? $('#author_image').height() : 0 ;
        var heightAuthorName = ($('#author_name').height())? $('#author_name').height() : 0 ;
        var heightBreadcrumb = ($('#learning_path_breadcrumb_zone').height())? $('#learning_path_breadcrumb_zone').height() : 0 ;
        var heightControl = ($('#control').is(':visible'))? $('#control').height() : 0 ;
        var heightMedia = ($('#lp_media_file').length != 0)? $('#lp_media_file').height() : 0 ;
        var heightTitle = ($('#scorm_title').height())? $('#scorm_title').height() : 0 ;
        var heightAction = ($('#actions_lp').height())? $('#actions_lp').height() : 0 ;

        var heightTop = heightHeader + heightAuthorImg + heightAuthorName + heightMedia + heightTitle + heightAction + 100;
        heightTop = (heightTop < 230)? heightTop : 230;
        var innerHeight = (IE) ? document.body.clientHeight : window.innerHeight ;
        // -40 is a static adjustement for margin, spaces on the page

        $('#inner_lp_toc').css('height', innerHeight - heightTop - heightBreadcrumb - heightControl + "px");
        if ($('#content_id')) {
            $('#content_id').css('height', innerHeight - heightBreadcrumb - heightControl + "px");
        }
        if ($('#hide_bar')) {
            $('#hide_bar').css('height', innerHeight - heightBreadcrumb - heightControl + "px");
        }

    // Loads the glossary library.
    <?php
      if (api_get_setting('show_glossary_in_extra_tools') == 'true') {
           if (api_get_setting('show_glossary_in_documents') == 'ismanual') {
                ?>
            $.frameReady(function(){
                   //  $("<div>I am a div courses</div>").prependTo("body");
         }, "top.content_name",
          { load: [
                  {type:"script", id:"_fr1", src:"<?php echo api_get_path(WEB_LIBRARY_PATH); ?>javascript/jquery.min.js"},
                  {type:"script", id:"_fr4", src:"<?php echo api_get_path(WEB_LIBRARY_PATH); ?>javascript/jquery-ui/smoothness/jquery-ui-1.8.21.custom.min.js"},
                  {type:"stylesheet", id:"_fr5", src:"<?php echo api_get_path(WEB_LIBRARY_PATH); ?>javascript/jquery-ui/smoothness/jquery-ui-1.8.21.custom.css"},
                  {type:"script", id:"_fr2", src:"<?php echo api_get_path(WEB_LIBRARY_PATH); ?>javascript/jquery.highlight.js"}

          ] }
          );
    <?php
        } elseif (api_get_setting('show_glossary_in_documents') == 'isautomatic') {
      ?>
    $.frameReady(function(){
        //  $("<div>I am a div courses</div>").prependTo("body");
      },
        "top.content_name",
      {
      load: [
          {type:"script", id:"_fr1", src:"<?php echo api_get_path(WEB_LIBRARY_PATH); ?>javascript/jquery.min.js"},
          {type:"script", id:"_fr4", src:"<?php echo api_get_path(WEB_LIBRARY_PATH); ?>javascript/jquery-ui/smoothness/jquery-ui-1.8.21.custom.min.js"},
          {type:"stylesheet", id:"_fr5", src:"<?php echo api_get_path(WEB_LIBRARY_PATH); ?>javascript/jquery-ui/smoothness/jquery-ui-1.8.21.custom.css"},
          {type:"script", id:"_fr2", src:"<?php echo api_get_path(WEB_LIBRARY_PATH); ?>javascript/jquery.highlight.js"}
      ]}
      );
  <?php
       }
  }
  ?>}
    $(document).ready(function() {
        updateContentHeight();
        $('#hide_bar').children().click(function(){
            updateContentHeight();
        });
        $(window).resize(function() {
            updateContentHeight();
        });
    });
    window.onload = updateContentHeight();
    window.onresize = updateContentHeight();
</script>
<?php
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
// Restore a global setting.
$_setting['show_navigation_menu'] = $save_setting;

//$content = $app['template']->fetch('default/javascript/newscorm/lp.tpl');
$app['template.show_footer'] = false;
$app['template.show_header'] = false;

$app['default_layout'] = 'default/learnpath/lp.tpl';
