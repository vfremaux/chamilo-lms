<?php
/* For licensing terms, see /license.txt */
<<<<<<< HEAD

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
require_once '../global.inc.php';

$action = $_GET['a'];

switch($action) {
    case 'load_online_user':
        if (isset($_SESSION['who_is_online_counter'])) {
            $_SESSION['who_is_online_counter']++;
        } else {
            $_SESSION['who_is_online_counter'] = 2;
        }
        $images_to_show = 9;

        $page = intval($_REQUEST['online_page_nr']);
<<<<<<< HEAD
        $max_page = round(Online::who_is_online_count()/$images_to_show);
        $page_rows = ($page-1)*9 + 1;

        if (!empty($max_page) && $page <= $max_page) {
            if (isset($_GET['cidReq']) && strlen($_GET['cidReq']) > 0) {
                $user_list = Online::who_is_online_in_this_course($page_rows, $images_to_show, api_get_user_id(), api_get_setting('time_limit_whosonline'), $_GET['cidReq']);
            } else {
                $user_list = Online::who_is_online($page_rows, $images_to_show);
=======
        $max_page = ceil(who_is_online_count()/$images_to_show);
        $page_rows = ($page-1)*9;

        if (!empty($max_page) && $page <= $max_page) {
            if (isset($_GET['cidReq']) && strlen($_GET['cidReq']) > 0) {
                $user_list = who_is_online_in_this_course($page_rows, $images_to_show, api_get_user_id(), api_get_setting('time_limit_whosonline'), $_GET['cidReq']);
            } else {
                $user_list = who_is_online($page_rows, $images_to_show);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
            }
            if (!empty($user_list)) {
                echo SocialManager::display_user_list($user_list, false);
                exit;
            }
        }
        echo 'end';
        break;
    default:
        break;
}
<<<<<<< HEAD
exit;
=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
