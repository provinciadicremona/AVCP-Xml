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

session_start();

define('AVCP_DIR', dirname(__FILE__).'/');
// Disabilito la visualizzazione dei notice.
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

// Rigenero l'id di sessione per aumentare la sicurezza
session_regenerate_id();

// Imposto il formato locala della valuta
setlocale(LC_MONETARY, 'it_IT.UTF-8');

/**
 * In questa variabile memorizzo i javascript da includere nel footer
 *
 * @var [type]
 */
$customJsScript = null;

$callToJqueryUI = '
    <script src="js/jquery.ui.core.js"></script>
    <script src="js/jquery.ui.widget.js"></script>
    <script src="js/jquery.ui.position.js"></script>
    <script src="js/jquery.ui.menu.js"></script>
    <script src="js/jquery.ui.autocomplete.js"></script>
    <script src="js/jquery.ui.datepicker.js"></script>
    <script src="js/jquery.ui.datepicker-it.js"></script>
    ';

require_once AVCP_DIR.'app/config.php';
require_once AVCP_DIR.'app/functions.php';
require_once AVCP_DIR.'header.php';
require_once AVCP_DIR.'app/controller/indexController.php';
require_once AVCP_DIR.'footer.php';
$db->close();
