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
error_reporting(E_ALL ^ E_NOTICE);
require_once dirname(__FILE__).'/../config.php';
$anno = (int) $_GET['anno'];
$XML_FILE = null;
$XML_TOT = null;
$XML_TOT .= '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>' . PHP_EOL;
$XML_TOT .= '<!DOCTYPE legge190:pubblicazione>' . PHP_EOL;
$date_agg = date("Y-m-d");
$date_agg_full = date("Y-m-d H:i:s");
$dataPubb = $anno + 1 . "-01-31";
$query_lotti = "SELECT * FROM avcp_lotto WHERE anno = '" . $anno . "' AND sceltaContraente != '00-DA DEFINIRE'";
$result_lotti = $db->query($query_lotti);
$number_lotti = $result_lotti->num_rows;

$XML_TOT .= '<legge190:pubblicazione xsi:schemaLocation="legge190_1_0 datasetAppaltiL190.xsd"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:legge190="legge190_1_0">
    <metadata>
    <titolo> Pubblicazione 1 legge 190</titolo>
    <abstract> Pubblicazione 1 legge 190 anno 1 rif. 2010' . ' aggiornamento del ' . $date_agg_full . '</abstract>
    <dataPubblicazioneDataset>' . $dataPubb . '</dataPubblicazioneDataset>
    <entePubblicatore>' . stripslashes(ENTE_PROPONENTE) . '</entePubblicatore>
    <dataUltimoAggiornamentoDataset>' . $date_agg . '</dataUltimoAggiornamentoDataset>
    <annoRiferimento>' . $anno . '</annoRiferimento>' . "\n";
if (URL_XML_FILE_ANNUALE == 'NO') {
    $XML_TOT .= '<urlFile>' . URL_XML_FILE . '</urlFile>' . "\n";
} else {
    $url = strpos(URL_XML_FILE_ANNUALE, '{{anno}}') === false
        ? URL_XML_FILE_ANNUALE . $anno // Per la retrocompatibilità.
        : str_replace('{{anno}}', $anno, URL_XML_FILE_ANNUALE);

    $XML_TOT .= "\t".'<urlFile>' . $url . '.xml</urlFile>' . "\n";
}
$XML_TOT .= "\t".'<licenza>' . LICENZA . '</licenza>'."\n".'</metadata>' . PHP_EOL;
$XML_TOT .= '<data>' . PHP_EOL;
while ($lotto = $result_lotti->fetch_assoc()) {
    foreach ($lotto as $key => $value) {
        $$key = stripslashes($value);
    }
    $oggetto = mb_convert_encoding($oggetto, "UTF-8", "ISO-8859-15, ISO-8859-1, CP1251, CP1252");
    $oggetto = htmlspecialchars($oggetto, ENT_NOQUOTES, 'UTF-8');
    $XML_FILE .= "<lotto>\n";
    $XML_FILE .= "<cig>" . $cig . "</cig>\n";
    $XML_FILE .= "<strutturaProponente>\n";
    $XML_FILE .= "<codiceFiscaleProp>" . $codiceFiscaleProp . "</codiceFiscaleProp>\n";
    $XML_FILE .= "<denominazione>" . stripslashes($denominazione) . "</denominazione>\n";
    $XML_FILE .= "</strutturaProponente>\n";
    $XML_FILE .= "<oggetto>" . $oggetto . "</oggetto>\n";
    $XML_FILE .= "<sceltaContraente>" . $sceltaContraente . "</sceltaContraente>\n";

    // ### PARTECIPANTI ######

    $XML_FILE .= "<partecipanti>\n";
    $raggruppamento_old = 1;
    $raggruppamento_start = 0;
    $flag_first_ragg = true;
    $XML_RAGG = "<raggruppamento>\n";
    $XML_PART = null;
    $query_partecipanti = "SELECT * FROM avcp_xml_ditte WHERE id = '" . $id . "' AND funzione = '01-PARTECIPANTE'";
    $result_partecipanti = $db->query($query_partecipanti);
    $number_partecipanti = $result_partecipanti->num_rows;
    while ($partecipante = $result_partecipanti->fetch_assoc()) {
        foreach ($partecipante as $key => $value) {
            $$key = stripslashes($value);
        }
        $ragioneSociale = htmlspecialchars($ragioneSociale, ENT_NOQUOTES, 'UTF-8');
        if ($raggruppamento == 0) {
            $XML_PART .= "<partecipante>\n";
            if ($estero == 0) {
                $XML_PART .= "<codiceFiscale>" . $codiceFiscale . "</codiceFiscale>\n";
            } else {
                $XML_PART .= "<identificativoFiscaleEstero>" . $codiceFiscale . "</identificativoFiscaleEstero>\n";
            }
            $XML_PART .= "<ragioneSociale>" . $ragioneSociale . "</ragioneSociale>\n";
            $XML_PART .= "</partecipante>\n";
        } else {
            if ($flag_first_ragg == true) {
                $raggruppamento_old = $raggruppamento;
                $flag_first_ragg = false;
            }
            if ($raggruppamento != $raggruppamento_old) {
                $XML_RAGG .= "</raggruppamento>\n";
                $XML_RAGG .= "<raggruppamento>\n";
            }
            $XML_RAGG .= "<membro>\n";
            if ($estero == 0) {
                $XML_RAGG .= "<codiceFiscale>" . $codiceFiscale . "</codiceFiscale>\n";
            } else {
                $XML_RAGG .= "<identificativoFiscaleEstero>" . $codiceFiscale . "</identificativoFiscaleEstero>\n";
            }
            $XML_RAGG .= "<ragioneSociale>" . utf8_encode($ragioneSociale) . "</ragioneSociale>\n";
            $XML_RAGG .= "<ruolo>" . $ruolo . "</ruolo>\n";
            $XML_RAGG .= "</membro>\n";
            $raggruppamento_old = $raggruppamento;
            $raggruppamento_start = 1;
        }
    } // fine while partecipanti
    $result_partecipanti->free(); // pulisco partecipanti

    if ($raggruppamento_start == 1) {
        $XML_RAGG .= "</raggruppamento>\n";
        $XML_FILE .= $XML_RAGG;
    }
    $XML_FILE .= $XML_PART;
    $XML_FILE .= "</partecipanti>\n";

    // ### AGGIUDICATARI ######
    $XML_FILE .= "<aggiudicatari>\n";
    $raggruppamento_old = 1;
    $raggruppamento_start = 0;
    $XML_RAGG = null;
    $XML_AGG = null;
    $query_aggiudicatari = 'SELECT * FROM avcp_xml_ditte WHERE id = "' . $id . '" AND funzione = "02-AGGIUDICATARIO" ORDER BY raggruppamento DESC';
    $result_aggiudicatari = $db->query($query_aggiudicatari);
    $number_aggiudicatari = $result_aggiudicatari->num_rows;
    while ($aggiudicatario = $result_aggiudicatari->fetch_assoc()) {
        foreach ($aggiudicatario as $key => $value) {
            $$key = stripslashes($value);
        }
        $ragioneSociale = htmlspecialchars($ragioneSociale, ENT_NOQUOTES, 'UTF-8');
        if ($raggruppamento == 0) {
            $XML_AGG .= "<aggiudicatario>\n";
            if ($estero == 0) {
                $XML_AGG .= "<codiceFiscale>" . $codiceFiscale . "</codiceFiscale>\n";
            } else {
                $XML_AGG .= "<identificativoFiscaleEstero>" . $codiceFiscale . "</identificativoFiscaleEstero>\n";
            }
            $XML_AGG .= "<ragioneSociale>" . $ragioneSociale . "</ragioneSociale>\n";
            $XML_AGG .= "</aggiudicatario>\n";
        } else {
            if ($raggruppamento_start == 0) {
                $XML_RAGG .= "<aggiudicatarioRaggruppamento>\n";
            }
            if ($raggruppamento != $raggruppamento_old && $raggruppamento_start == 1) {
                $XML_RAGG .= "</aggiudicatarioRaggruppamento>\n";
                $XML_RAGG .= "<aggiudicatarioRaggruppamento>\n";
            }
            $XML_RAGG .= "<membro>\n";
            if ($estero == 0) {
                $XML_RAGG .= "<codiceFiscale>" . $codiceFiscale . "</codiceFiscale>\n";
            } else {
                $XML_RAGG .= "<identificativoFiscaleEstero>" . $codiceFiscale . "</identificativoFiscaleEstero>\n";
            }
            $XML_RAGG .= "<ragioneSociale>" . $ragioneSociale . "</ragioneSociale>\n";
            $XML_RAGG .= "<ruolo>" . $ruolo . "</ruolo>\n";
            $XML_RAGG .= "</membro>\n";
            $raggruppamento_old = $raggruppamento;
            $raggruppamento_start = 1;
        }
    } // fine while aggiudicatari
    $result_aggiudicatari->free(); // pulisco aggiudicatari
    if ($raggruppamento_start == 1) {
        $XML_RAGG .= "</aggiudicatarioRaggruppamento>\n";
    }
    $XML_FILE .= $XML_RAGG . $XML_AGG;
    $XML_FILE .= "</aggiudicatari>\n";

    // ####### importi e tempi #####
    $XML_FILE .= "<importoAggiudicazione>" . $importoAggiudicazione . "</importoAggiudicazione>\n";
    $XML_FILE .= "<tempiCompletamento>\n";
    // Se le date sono vuote, non le stampo
    // Check su empty per via di mysql8 NO_ZERO_DATE etc...
    if (!empty($dataInizio) && $dataInizio != '0000-00-00') {
        $XML_FILE .= "<dataInizio>" . $dataInizio . "</dataInizio>\n";
    }
    if (!empty($dataUltimazione) && $dataUltimazione != '0000-00-00') {
        $XML_FILE .= "<dataUltimazione>" . $dataUltimazione . "</dataUltimazione>\n";
    }
    $XML_FILE .= "</tempiCompletamento>\n";
    $XML_FILE .= "<importoSommeLiquidate>" . $importoSommeLiquidate . "</importoSommeLiquidate>\n";
    $XML_FILE .= "</lotto>\n";
} // fine while lotti
$result_lotti->free(); // Pulisco lotti

$XML_TOT .= $XML_FILE;
$XML_TOT .= '</data>' . PHP_EOL;
$XML_TOT .= '</legge190:pubblicazione>';
