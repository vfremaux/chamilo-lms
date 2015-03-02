<?php
/* For licensing terms, see /license.txt */
/**
*	This is the tracking library for Chamilo.
*
*	@package chamilo.reporting
*
* Calculates the time spent on the course
* @param integer $user_id the user id
* @param string $course_code the course code
* @author Julio Montoya <gugli100@gmail.com>
* @author Jorge Frisancho Jibaja - select between dates
*
*/
/**
 * Code
 */
// name of the language file that needs to be included
$language_file = array ('registration', 'index', 'tracking');

require_once '../inc/global.inc.php';

// including additional libraries
<<<<<<< HEAD
=======
require_once api_get_path(LIBRARY_PATH).'pchart/pData.class.php';
require_once api_get_path(LIBRARY_PATH).'pchart/pChart.class.php';
require_once api_get_path(LIBRARY_PATH).'pchart/pCache.class.php';
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
require_once 'myspace.lib.php';

api_block_anonymous_users();

// the section (for the tabs)
$this_section = SECTION_TRACKING;

/* MAIN */
$user_id = intval($_REQUEST['student']);
$session_id = intval($_GET['id_session']);
$type = Security::remove_XSS($_REQUEST['type']);
$course_code = Security::remove_XSS($_REQUEST['course']);
<<<<<<< HEAD
$courseInfo = api_get_course_info($course_code);
$courseId = $courseInfo['real_id'];

$connections = MySpace::get_connections_to_course($user_id, $courseId, $session_id);

=======
$connections = MySpace::get_connections_to_course($user_id, $course_code, $session_id);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
$quote_simple = "'";

$form = new FormValidator('myform', 'get', api_get_self(), null, array('id' => 'myform'));
$form->addElement('text', 'from', get_lang('From'), array('id' => 'date_from'));
$form->addElement('text', 'to', get_lang('Until'), array('id' => 'date_to'));
$form->addElement('select', 'type', get_lang('Type'), array('day' => get_lang('Day'), 'month' => get_lang('Month')), array('id' => 'type'));
$form->addElement('hidden', 'student', $user_id);
$form->addElement('hidden', 'course', $course_code);
$form->addRule('from', get_lang('ThisFieldIsRequired'), 'required');
$form->addRule('to', get_lang('ThisFieldIsRequired'), 'required');
$group = array(
    $form->createElement(
        'label',
        null,
        Display::url(get_lang('Send'), 'javascript://', array('onclick'=> 'loadGraph();', 'class' => 'btn'))
    )
    //$form->createElement('label', null, Display::url(get_lang('Reset'), 'javascript:void()', array('id' => "reset_button", 'class' => 'btn')))
);
$form->addGroup($group);
$from = null;
$to = null;
$course = $course_code;
if ($form->validate()) {
    $values = $form->getSubmitValues();
    $from = $values['from'];
    $to = $values['to'];
    $type = $values['type'];
    $course = $values['course'];
}

$url = api_get_path(WEB_AJAX_PATH).'myspace.ajax.php?a=access_detail_by_date&course='.$course.'&student='.$user_id;

$htmlHeadXtra[] = '<script src="slider.js" type="text/javascript"></script>';
$htmlHeadXtra[] = '<link rel="stylesheet" href="slider.css" />';
<<<<<<< HEAD

$htmlHeadXtra[] = '<script>
$(function() {
    var dates = $( "#date_from, #date_to" ).datepicker({
        dateFormat: '.$quote_simple.'yy-mm-dd'.$quote_simple.',
        changeMonth: true,
    changeYear: true,
        onSelect: function( selectedDate ) {
            var foo = areBothFilled();
            var option = this.id == "date_from" ? "minDate" : "maxDate",
                instance = $( this ).data( "datepicker" );
                date = $.datepicker.parseDate(
                    instance.settings.dateFormat ||
                    $.datepicker._defaults.dateFormat,
                    selectedDate, instance.settings );
            dates.not( this ).datepicker( "option", option, date );

            if (foo){
                var start_date  = document.getElementById("date_from").value;
                var end_date    = document.getElementById("date_to").value;
                changeHREF(start_date,end_date);
                var foo_student = '.$user_id.';
                var foo_course  = "'.$courseId.'";
                var graph_type  = "'.$type.'";
                var foo_slider_state = getSliderState();
                if (foo_slider_state == "open"){
                    sliderAction();
                }
                $.post("'.api_get_path(WEB_AJAX_PATH).'myspace.ajax.php?a=access_detail_by_date", {startDate: start_date, endDate: end_date, course: foo_course, student: foo_student, type: graph_type}, function(db)
                {
                    if (!db.is_empty){
                        // Display confirmation message to the user
                        $("#messages").html(db.result).stop().css("opacity", 1).fadeIn(30);
                        $("#cev_cont_stats").html(db.stats);
                        $( "#ui-tabs-1" ).empty();
                        $( "#ui-tabs-2" ).empty();
                        $( "#ui-tabs-1" ).html(db.graph_result);
                        $( "#ui-tabs-2" ).html(db.graph_result);
                    }
                    else{
                        $("#messages").text("'.get_lang('NoDataAvailable').'");
                        $("#messages").addClass("warning-message");
                        $("#cev_cont_stats").html(db.stats);
                        $( "#ui-tabs-1" ).empty();
                        $( "#ui-tabs-1" ).html(db.graph_result);
                        controlSliderMenu(foo_height);
                    }
                    var foo_height = sliderGetHeight("#messages");
                    sliderSetHeight(".slider",foo_height);
                    controlSliderMenu(foo_height);
                    // Hide confirmation message and enable stars for "Rate this" control, after 2 sec...
                    /*setTimeout(function(){
                            $("#messages").fadeOut(1000, function(){ui.enable()})
                    }, 2000);*/
                }, "json");

                $( "#cev_slider" ).empty();
                // Create element to use for confirmation messages
                $('.$quote_simple .'<div id="messages"/>'.$quote_simple .').appendTo("#cev_slider");

            }
        }
     });
    if (areBothFilled()){
        runEffect();
    }
});

</script>';


$htmlHeadXtra[] = '<script>
function changeHREF(sd,ed) {
    var i       = 0;
    var href    = "";
    var href1   = "";
    $('.$quote_simple .'#container-9 a'.$quote_simple .').each(function() {
        href = $.data(this, '.$quote_simple .'href.tabs'.$quote_simple .');
        href1= href+"&sd="+sd+"&ed="+ed+"&range=1";
        $("#container-9").tabs("url", i, href1);
        var href1 = $.data(this, '.$quote_simple .'href.tabs'.$quote_simple .');
        i++
    })
=======
$htmlHeadXtra[] = "<script>
function loadGraph() {
    var startDate = $('#date_from').val();
    var endDate = $('#date_to').val();
    var type = $('#type option:selected').val();
    $.ajax({
        url: '".$url."&startDate='+startDate+'&endDate='+endDate+'&type='+type,
        dataType: 'json',
        success: function(db) {
            if (!db.is_empty) {
                // Display confirmation message to the user
                $('#messages').html(db.result).stop().css('opacity', 1).fadeIn(30);
                $('#cev_cont_stats').html(db.stats);
                $('#graph' ).html(db.graph_result);
            } else {
                $('#messages').text('".get_lang('NoDataAvailable')."');
                $('#messages').addClass('warning-message');
                $('#cev_cont_stats').html('');
                $('#graph').empty();
            }
        }
    });
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
}

$(function() {
    var dates = $('#date_from, #date_to').datepicker({
        dateFormat: ".$quote_simple."yy-mm-dd".$quote_simple.",
        changeMonth: true,
        changeYear: true
    });
});

</script>";

$htmlHeadXtra[] = '<script>
$(function() {
    $("#cev_button").hide();
    $("#container-9").tabs({remote: true});
});
</script>';

//Changes END
$interbreadcrumb[] = array('url' => '#', 'name' => get_lang('AccessDetails'));

Display :: display_header('');
$userInfo = api_get_user_info($user_id);
$result_to_print = '';
<<<<<<< HEAD

$sql_result      = MySpace::get_connections_to_course($user_id, $courseId);
=======
$sql_result = MySpace::get_connections_to_course($user_id, $course_code);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
$result_to_print = convert_to_string($sql_result);

echo Display::page_header(get_lang('DetailsStudentInCourse'));
echo Display::page_subheader(
    get_lang('User').': '.$userInfo['complete_name'].' - '.get_lang('Course').': '.$course_code
);

<<<<<<< HEAD
$form = new FormValidator('myform', 'post', "javascript:get(document.getElementById('myform'));", null, array('id' => 'myform'));
$form->addElement('text', 'from', get_lang('From'), array('id' => 'date_from'));
$form->addElement('text', 'to', get_lang('Until'), array('id' => 'date_to'));

$form->addElement('style_submit_button', 'reset', get_lang('Reset'), array('onclick' => "javascript:window.location='access_details.php?course=".$courseId."&student=".$user_id."&cidReq=".$course_code."';"));
=======
$form->setDefaults(array('from' => $from, 'to' => $to));
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
$form->display();
?>
<div id="cev_results" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
<<<<<<< HEAD
    <div class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all"><?php echo get_lang('Statistics'); ?></div><br />
    <div id="cev_cont_stats">
        <?php
        if ($result_to_print != "")  {
            $rst                = get_stats($user_id, $courseId);
            $foo_stats           = '<strong>'.get_lang('Total').': </strong>'.$rst['total'].'<br />';
            $foo_stats          .= '<strong>'.get_lang('Average').': </strong>'.$rst['avg'].'<br />';
            $foo_stats          .= '<strong>'.get_lang('Quantity').' : </strong>'.$rst['times'].'<br />';
            echo $foo_stats;
        } else {
            echo Display::display_warning_message(get_lang('NoDataAvailable'));
        }
        ?>
    </div><br />
</div><br />

<div id="container-9">
    <ul>
        <li><a href="<?php echo api_get_path(WEB_AJAX_PATH).'myspace.ajax.php?a=access_detail&type=day&course='.$courseId.'&student='.$user_id?>"><span> <?php echo api_ucfirst(get_lang('Day')); ?></span></a></li>
        <li><a href="<?php echo api_get_path(WEB_AJAX_PATH).'myspace.ajax.php?a=access_detail&type=month&course='.$courseId.'&student='.$user_id?>"><span> <?php echo api_ucfirst(get_lang('MinMonth')); ?></span></a></li>
    </ul>
</div>

<div id="cev_results" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
    <div class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all"><?php echo get_lang('DateAndTimeOfAccess'),' - ', get_lang('Duration') ?></div><br />
    <div id="cev_cont_results" >
    <div id="cev_slider" class="slider">
        <?php
        if ($result_to_print != "")  {
            echo $result_to_print;
        } else {
            Display::display_warning_message(get_lang('NoDataAvailable'));
        }
        ?>
    </div>
    <?php
    if ($result_to_print != "")  {
        echo '<br /><div class="slider_menu">
        <a href="#" onclick="return sliderAction();">
            '.Display::return_icon('zoom_in.png').'
        </a>
        </div>';
    }?>
=======
    <div class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
        <?php echo get_lang('Statistics'); ?>
    </div><br />
    <div id="cev_cont_stats">
    <?php
    if ($result_to_print != "")  {
        $rst                = get_stats($user_id, $course_code);
        $foo_stats           = '<strong>'.get_lang('Total').': </strong>'.$rst['total'].'<br />';
        $foo_stats          .= '<strong>'.get_lang('Average').': </strong>'.$rst['avg'].'<br />';
        $foo_stats          .= '<strong>'.get_lang('Quantity').' : </strong>'.$rst['times'].'<br />';
        echo $foo_stats;
    } else {
        echo Display::display_warning_message(get_lang('NoDataAvailable'));
    }
    ?>
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    </div>
    <br />
</div><br />

<div id="messages"></div>
<div id="graph"></div>

<?php
Display:: display_footer();
