<?php
// Controller che gestisce la fase di login
if (empty($_GET['do'])) {
    $_GET['do'] = null;
}
switch ($_GET['do']){
case 'login':
case 'logout':
    require_once 'app/model/signin.php';
    break;
default:
    $failedLogin = false;
    require_once 'app/checkDbUpdate.php';
    require_once 'app/view/signinForm.php';
    break;
}
