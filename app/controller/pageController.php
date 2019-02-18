<?php
// Controller che gestisce la visualizzazione delle normali pagine
$page = AVCP_DIR . 'app/view/' . $_GET['do'] . '.php';
if (!empty($_GET['do']) && file_exists($page)) {
    require_once $page;
} else {
    require_once AVCP_DIR . 'app/view/index.php';
}
