<?php
/*
 * This file is part of project AVCP-Xml that can be found at:
 * https://github.com/provinciadicremona/AVCP-Xml
 * 
 * © 2013 Claudio Roncaglio <claudio.roncaglio@provincia.cremona.it>
 * © 2013 Gianni Bassini <gianni.bassini@provincia.cremona.it>
 * © 2013 Provincia di Cremona <sito@provincia.cremona.it>
 * 
 * SPDX-License-Identifier: GPL-3.0-only
*/
?>

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
