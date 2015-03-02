<?php
/* For licensing terms, see /license.txt */
<<<<<<< HEAD

=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
require_once '../global.inc.php';

$timeline = new Timeline();

$action = $_GET['a'];

switch ($action) {
	case 'get_timeline_content':
        $items = $timeline->get_timeline_content($_GET['id']);
        echo json_encode($items);
        /*echo '<pre>';
        echo json_encode($items);
        echo '</pre>';
        var_dump($items);*/
    break;
}
exit;
