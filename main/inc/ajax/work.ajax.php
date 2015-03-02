<?php
/* For licensing terms, see /license.txt */
/**
 * Responses to AJAX calls
 */

require_once '../global.inc.php';
require_once api_get_path(SYS_CODE_PATH).'work/work.lib.php';

$action = isset($_REQUEST['a']) ? $_REQUEST['a'] : null;
$isAllowedToEdit = api_is_allowed_to_edit();

switch ($action) {
<<<<<<< HEAD
	case 'get_work_user_list':
		break;
=======
    case 'delete_work':
        if ($isAllowedToEdit) {
            if (empty($_REQUEST['id'])) {
                return false;
            }
            $workList = explode(',', $_REQUEST['id']);
            foreach ($workList as $workId) {
                deleteDirWork($workId);
            }
        }
        break;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    default:
        echo '';
        break;
}
exit;
