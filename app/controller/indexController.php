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
// Controller principale
// gestisce il login e smista le richieste agli altri controller
if (!array_key_exists('user', $_SESSION)) {
    $_GET['mask'] = 'signin';
}

if (!empty($_GET['mask'])) {
    $controller = AVCP_DIR.'app/controller/' . $_GET['mask'] . 'Controller.php';
    if (file_exists($controller)) {
        require_once $controller;
    }
} else {
    require_once AVCP_DIR.'app/model/indexModel.php';
}
