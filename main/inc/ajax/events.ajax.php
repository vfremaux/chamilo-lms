<?php
<<<<<<< HEAD
=======
/* For licensing terms, see /license.txt */
require_once '../global.inc.php';
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
$event_name = isset($_REQUEST['eventName']) ? $_REQUEST['eventName'] : null;

api_protect_admin_script();

switch ($action) {
    case 'getEventTypes':
        $events = get_all_event_types();
        print json_encode($events);
        break;
    case 'getUsers':
        $users = UserManager::get_user_list();
        print json_encode($users);
        break;
    case 'get_event_users':
        $users = get_event_users($event_name);
        print json_encode($users);
        break;
}
exit;
