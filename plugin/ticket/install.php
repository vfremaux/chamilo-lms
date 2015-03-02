<?php
/* For licensing terms, see /license.txt */
<<<<<<< HEAD
/**
 * This script is included by main/admin/settings.lib.php and generally
 * includes things to execute in the main database (settings_current table)
 * @package chamilo.plugin.bigbluebutton
 */
/**
 * Initialization
 */

require_once dirname(__FILE__).'/config.php';
TicketPlugin::create()->install();
=======

/**
 * This script is included by main/admin/settings.lib.php and generally
 * includes things to execute in the main database (settings_current table)
 * @package chamilo.plugin.ticket
 */

require_once dirname(__FILE__).'/config.php';
TicketPlugin::create()->install();
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
