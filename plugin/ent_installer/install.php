<?php
/* PHP code to install the plugin 
 * For example:
 * 
    // To query something to the database

    $table = Database::get_main_table(TABLE_MAIN_USER); // TABLE_MAIN_USER is a constant check the main/inc/database.constants.inc.php
    $sql = "SELECT firstname, lastname FROM $table_users ";
    $users = Database::query($sql); 

    You can also use the Chamilo classes 
    $users = UserManager::get_user_list();
 */

$table = 'local_ent_installer';
$tablename = Database::get_main_table($table);
$sql = "CREATE TABLE IF NOT EXISTS $tablename (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestart` int(11) NOT NULL,
  `timerun` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  `inserterror` int(11) NOT NULL,
  `updateerror` int(11) NOT NULL,

  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
Database::query($sql);

api_add_setting(0, 'ent_installer_institution_id', 'ent_installer', 'setting', 'Plugins');
api_add_setting(0, 'ent_installer_last_sync_date', 'ent_installer', 'setting', 'Plugins');
api_add_setting(0, 'ent_installer_enable_sync', 'ent_installer', 'setting', 'Plugins');
api_add_setting(0, 'ent_installer_real_used_auth', 'ent_installer', 'setting', 'Plugins');
api_add_setting(0, 'ent_installer_fake_email_domain', 'ent_installer', 'setting', 'Plugins');
