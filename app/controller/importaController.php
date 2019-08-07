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
// Controller che gestisce i metodi di importazione di lotti e ditte
switch ($_GET['do']){
case 'ditte':
    require_once AVCP_DIR . 'app/model/ditteImporta.php';
    break;
case 'gare':
    require_once AVCP_DIR . 'app/model/gareImporta.php';
    break;
default:
    break;
}
