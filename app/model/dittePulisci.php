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
$queryDitta = "DELETE FROM `avcp_ditta` WHERE codiceFiscale IS NULL OR codiceFiscale = ''";
$resDitta = $db->query($queryDitta);
$numDitta = $db->affected_rows;

$queryLd = "DELETE FROM `avcp_ld` WHERE codiceFiscale IS NULL OR codiceFiscale = ''";
$resLd = $db->query($queryLd);
$numLd = $db->affected_rows;

require_once AVCP_DIR . 'app/view/dittePulisci.php';
