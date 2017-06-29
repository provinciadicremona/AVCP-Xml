<?php
$queryDitta = "DELETE FROM `avcp_ditta` WHERE codiceFiscale IS NULL OR codiceFiscale = ''";
$resDitta = $db->query($queryDitta);
$numDitta = $db->affected_rows;

$queryLd = "DELETE FROM `avcp_ld` WHERE codiceFiscale IS NULL OR codiceFiscale = ''";
$resLd = $db->query($queryLd);
$numLd = $db->affected_rows;

require_once 'app/view/dittePulisci.php';
