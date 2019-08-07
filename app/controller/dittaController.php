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
// Controller dei metodi che agiscono sulle ditte
isset($_GET['do']) || $_GET['do'] = null;
switch ($_GET['do']){
case 'cercaDitta':
    require_once AVCP_DIR . 'app/model/dittaCerca.php';
    break;
case 'elencaDitte':
    require_once AVCP_DIR . 'app/model/ditteElenca.php';
    break;
case 'dittaGare':
    require_once AVCP_DIR . 'app/model/dittaGare.php';
    break;
case 'eliminaDitta':
    require_once AVCP_DIR . 'app/model/dittaElimina.php';
    break;
case 'pulisciDitte':
    require_once AVCP_DIR . 'app/model/dittePulisci.php';
    break;
default:
    if (!empty($_POST['codiceFiscale'])) {
        $_GET['codiceFiscale'] = $_POST['codiceFiscale'];
    }
    require_once AVCP_DIR . 'app/model/ditta.php';
    break;
}
