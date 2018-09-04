<?php
// Controller che gestisce la visualizzazione delle normali pagine
$page = __DIR__ . '/../view/' . $_GET['do'] . '.php';
if (!empty($_GET['do']) && file_exists($page)) {
    require_once $page;
} else {
    require_once __DIR__ . '/../view/index.php';
}
