<?php
header('Content-Type: application/json');
require_once '../../videos/configuration.php';
$obj = new stdClass();
$obj->msg = '';
$obj->error = false;
$obj->users_id = User::getId();
$obj->notifications = User_notifications::getAllForUsers_id($obj->users_id);
usort($obj->notifications, 'cmpNotifications');
echo json_encode($obj);

function cmpNotifications($a, $b) {
    return $a['id']-$b['id'];
}