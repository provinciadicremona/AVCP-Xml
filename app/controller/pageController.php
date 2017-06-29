<?php
// Controller che gestisce la visualizzazione delle normali pagine
$page = 'app/view/' . $_GET['do'] . '.php';
if (!empty($_GET['do']) && file_exists($page)) {
    require_once $page;
} else {
    require_once 'app/view/index.php';
}
