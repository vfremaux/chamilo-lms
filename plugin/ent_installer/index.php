<?php
/**
 * @package chamilo.plugin.ent_installer
 */

// See also the share_user_info plugin 

echo '<div class="well">';
if (!empty($plugin_info['settings']['ent_installer_show_type'])) {
    echo "<h2>".$plugin_info['settings']['ent_installer_show_type']."</h2>";
} else {
    echo "<h2>ENT Installer</h2>";
}

//Using get_lang inside a plugin
echo get_lang('ENTInstaller');

echo '</div>';