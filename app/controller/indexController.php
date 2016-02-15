<?php
require_once 'app/config.php';

if (!isset($_SESSION['user']) && empty($_SESSION['user'])) {
    $_GET['mask'] = 'signin';
}

if (!empty($_GET['mask'])) {
    $controller = 'app/controller/' . $_GET['mask'] . 'Controller.php';
    if (file_exists($controller)) {
        require_once $controller;
    }
} else {
    require_once 'app/model/indexModel.php';
}
