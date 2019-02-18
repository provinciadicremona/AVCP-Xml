<?php
/**
 * APVC Xml - Generatore dataset per art.
 * 32 L. 190/2012
 * Copyright (C) 2013 Claudio Roncaglio e Gianni Bassini
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * To contact the authors send an email to <sito@provincia.cremona.it>
 */
session_start();

define('AVCP_DIR', dirname(__FILE__).'/');
// Disabilito la visualizzazione dei notice.
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

// Rigenero l'id di sessione per aumentare la sicurezza
session_regenerate_id();
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

require_once AVCP_DIR.'header.php';
require_once AVCP_DIR.'app/controller/indexController.php';
require_once AVCP_DIR.'footer.php';
$db->close();
