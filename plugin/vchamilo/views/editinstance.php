<?php

    define('CHAMILO_INTERNAL', true);
    
    global $plugininstance;

    require_once '../../../main/inc/global.inc.php';
    require_once $_configuration['root_sys'] . '/local/classes/mootochamlib.php';
    require_once $_configuration['root_sys'] . '/local/classes/database.class.php';
    require_once $_configuration['root_sys'] . '/plugin/vchamilo/lib/vchamilo_plugin.class.php';
    require_once $_configuration['root_sys'] . '/plugin/vchamilo/views/editinstance_form.php';
    HTML_QuickForm::registerElementType('cancel', $_configuration['root_sys'].'/plugin/vchamilo/lib/QuickForm/cancel.php', 'HTML_QuickForm_cancel');

    $htmlHeadXtra[] = '<script src="'.$_configuration['root_web'] . '/plugin/vchamilo/js/host_form.js" type="text/javascript" language="javascript"></script>';

    global $DB;
    $DB = new DatabaseManager();

    // get parameters
    $id = (int)($_REQUEST['id']);
    $action = $_REQUEST['what'];
    $registeronly = @$_REQUEST['registeronly'];

    $plugininstance = VChamiloPlugin::create();
    $thisurl = $_configuration['root_web'].'/plugin/vchamilo/views/manage.php';
    
    // security
    api_protect_admin_script();
    
    $mode = ($id) ? 'update' : 'add';

    $form = new InstanceForm($plugininstance, $mode);
    $form->definition();

    $actions = '';
    $message = '';
    

    // call controller
    if ($data = $form->get_data()){
        include 'editinstance.controller.php';
    }
    
    if ($id){
        $vhost = $DB->get_record('vchamilo', array('id' => $id));
        $form->set_data((array)$vhost);
    } else {
        $data = array();
        $data['db_host'] = 'localhost';
        $data['single_database'] = 1;
        $data['registeronly'] = $registeronly;
        $form->set_data($data);
    }
    
    $content = $form->return_form();

    $tpl = new Template($tool_name, true, true, false, true, false);
    $tpl->assign('actions', $actions);
    $tpl->assign('message', $message);
    $tpl->assign('content', $content);
    $tpl->display_one_col_template();
