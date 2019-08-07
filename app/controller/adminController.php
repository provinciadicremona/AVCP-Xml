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
// Controller dei metodi riservati all'utente admin
isset($_GET['do']) || $_GET['do'] = null;
switch ($_GET['do']){
case 'blocca':
    require_once AVCP_DIR . 'app/model/annoBlocca.php';
    break;
case 'sblocca':
    require_once AVCP_DIR . 'app/model/annoSblocca.php';
    break;
case 'avanza':
    require_once AVCP_DIR . 'app/model/annoAvanza.php';
    break;
case 'arretra':
    require_once AVCP_DIR . 'app/model/annoArretra.php';
    break;
case 'messaggio':
    require_once AVCP_DIR . 'app/model/messaggio.php';
    break;
default:
    break;
}
if (isset($_GET['do']) && $_GET['do'] != 'messaggio') {
    require_once AVCP_DIR . 'app/view/annoOp.php';
}
