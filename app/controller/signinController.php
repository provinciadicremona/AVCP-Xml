<?php
// Controller che gestisce la fase di login
if (empty($_GET['do'])) {
    $_GET['do'] = null;
}
switch ($_GET['do']){
case 'login':
case 'logout':
    require_once AVCP_DIR . 'app/model/signin.php';
    break;
default:
    $failedLogin = false;
//    require_once AVCP_DIR . 'app/checkDbUpdate.php';
    require_once AVCP_DIR . 'app/view/signinForm.php';
    break;
}
