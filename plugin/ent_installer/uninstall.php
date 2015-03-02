<?php
/* PHP code to uninstall the plugin */
 
$table = 'local_ent_installer';
$tablename = Database::get_main_table($table);
$sql = " DROP TABLE IF EXISTS $tablename ";

Database::query($sql);
