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
// Controller che gestisce la visualizzazione delle normali pagine
$page = AVCP_DIR . 'app/view/' . $_GET['do'] . '.php';
if (!empty($_GET['do']) && file_exists($page)) {
    require_once $page;
} else {
    require_once AVCP_DIR . 'app/view/index.php';
}
