<?php
// Controller principale
// gestisce il login e smista le richieste agli altri controller
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user']) && empty($_SESSION['user'])) {
    $_GET['mask'] = 'signin';
}

if (!empty($_GET['mask'])) {
    $controller = __DIR__ . '/' . $_GET['mask'] . 'Controller.php';
    if (file_exists($controller)) {
        require_once $controller;
    }
} else {
    require_once __DIR__ . '/../model/indexModel.php';
}
