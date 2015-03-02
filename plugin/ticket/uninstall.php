<?php
/* For licensing terms, see /license.txt */
<<<<<<< HEAD
=======

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
/**
 * This script is included by main/admin/settings.lib.php when unselecting a plugin
 * and is meant to remove things installed by the install.php script in both
 * the global database and the courses tables
<<<<<<< HEAD
 * @package chamilo.plugin.bigbluebutton
 */
/**
 * Queries
 */
require_once dirname(__FILE__).'/config.php';
TicketPlugin::create()->uninstall();
=======
 * @package chamilo.plugin.ticket
 */
require_once dirname(__FILE__).'/config.php';
TicketPlugin::create()->uninstall();
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
