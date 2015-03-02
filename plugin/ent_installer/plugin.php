<?php
/**
 * This script is a configuration file for the vchamilo plugin. You can use it as a master for other platform plugins (course plugins are slightly different).
 * These settings will be used in the administration interface for plugins (Chamilo configuration settings->Plugins)
 * @package chamilo.plugin
 * @author Julio Montoya <gugli100@gmail.com>
 */

require_once api_get_path(LIBRARY_PATH) . 'plugin.class.php';
require_once dirname(__FILE__).'/lib/ent_installer_plugin.class.php';

/**
 * Plugin details (must be present)
 */

/* Plugin config */

//the plugin title
$plugin_info['title']       = 'ENT Installation';
//the comments that go with the plugin
$plugin_info['comment']     = "Holds chamilo ENT environment installation settings";
//the plugin version
$plugin_info['version']     = '1.0';
//the plugin author
$plugin_info['author']      = 'Valery Fremaux';


/* Plugin optional settings */ 

/* 
 * This form will be showed in the plugin settings once the plugin was installed 
 * in the plugin/hello_world/index.php you can have access to the value: $plugin_info['settings']['hello_world_show_type']
*/

$form = new FormValidator('ent_installer_form');

$plugininstance = ENTInstallerPlugin::create();

$config = api_get_settings_params(array('subkey = ? ' => 'ent_installer', ' AND category = ? ' => 'Plugins'));
foreach($config as $fooid => $configrecord){
    $canonic = preg_replace('/^ent_installer_/', '', $configrecord['variable']);
    if (in_array($canonic, array('enable_sync', 'institution_id','last_sync_date','real_used_auth', 'fake_email_domain'))){
        $form_settings[$canonic] = $configrecord['selected_value'];
    }
}

// A simple select.
$options = array(0 => $plugininstance->get_lang('no'), 1 => $plugininstance->get_lang('yes'));
$form->addElement('select', 'enable_sync', $plugininstance->get_lang('enable_sync'), $options);

$form->addElement('text', 'institution_id', $plugininstance->get_lang('institution_id'));
$form->addElement('text', 'last_sync_date', $plugininstance->get_lang('last_sync_date'));

$options = array('platform' => $plugininstance->get_lang('manual'), 'extldap' => $plugininstance->get_lang('ldap'), 'cas' => $plugininstance->get_lang('cas'));
$form->addElement('select', 'real_used_auth', $plugininstance->get_lang('real_used_auth'), $options);

$form->addElement('text', 'fake_email_domain', $plugininstance->get_lang('fake_email_domain'));

$form->addElement('static', 'synttimereport', '<a href="'.$_configuration['root_web'].'/local/ent_installer/synctimereport.php">'.$plugininstance->get_lang('sync_report').'</a>');

$form->addElement('style_submit_button', 'submit_button', $plugininstance->get_lang('Save'));  

$form->setDefaults($form_settings);

$plugin_info['settings_form'] = $form;