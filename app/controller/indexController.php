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
