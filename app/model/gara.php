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
if (!empty($_GET['do'])) {
    foreach ($_POST as $key => $value) {
        $$key = $db->real_escape_string(trim($value));
    }
    // Controllo che non manchino i campi principali
    try {
        if (empty($denominazione) || empty($codiceFiscaleProp) || empty($anno) || empty($oggetto)) {
            throw new Exception('
                <div class="row">
                <div class="span8 offset2">
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Errore nei dati della gara:</strong><br />
                        Manca uno di questi dati:
                        <ul>
                            <li>Denominazione: ' . $denominazione . '</li>
                            <li>Codice Fiscale: ' . $codiceFiscaleProp . '</li>
                            <li>Anno: ' . $anno . '</li>
                            <li>Oggetto: ' . $oggetto . '</li>
                        </ul>
                    </div>
                    <p>Devi reinserire la gara, <a href="?mask=gara">torna alla maschera d\'inserimento</a></p>
                </div>
                </div>', 1);
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        // Imposto do a errore per essere gestito dallo switch
        $_GET['do'] = 'errore';
    }
    // Imposto i campi vuoti con valori di default
    if (empty($cig) || $cig == '')
        $cig = '0000000000';
    if (empty($importoAggiudicazione)) {
        $importoAggiudicazione = '0.00';
    } else {
        $importoAggiudicazione = strtr($importoAggiudicazione, ',', '.');
    }
    if (empty($importoSommeLiquidate)) {
        $importoSommeLiquidate = '0.00';
    } else {
        $importoSommeLiquidate = strtr($importoSommeLiquidate, ',', '.');
    }
    // Modifiche sul formato date necessarie per mysql8 che non accetta 
    // più come valida la data '0000-00-00'
    if (empty($dataInizio)) {
        $dataInizioIns = 'NULL';
        $dataInizioUpd = "`dataInizio` = NULL, ";
    } else {
        $dataInizioIns = "'".$dataInizio."'";
        $dataInizioUpd = "`dataInizio` = '" . $dataInizio . "',";
    }
    if (empty($dataUltimazione)) {
        $dataUltimazioneIns= 'NULL';
        $dataUltimazioneUpd = "`dataUltimazione` = NULL, ";
    } else {
        $dataUltimazioneIns = "'".$dataUltimazione."'";
        $dataUltimazioneUpd = "`dataUltimazione` = '" . $dataUltimazione . "',";
    }
}
switch ($_GET['do']){
    case 'inserisciGara':
        // Verifico che l'anno sia compreso tra 2012 e anno corrente
        // Il 2012 è stato il primo anno della rilevazione.
        try {
            if (!in_array($anno, $anniValidi))
                throw new Exception('
                    <div class="row">
                    <div class="span8 offset2">
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Errore nell\'inserimento della gara:</strong><br />gli inserimenti per l\'anno ' . $anno . ' non sono consentiti
                            <br />Sono accettati questi anni: ' . implode(", ", $anniValidi) . '. 
                        </div>
                        <p>Torna alla <a href="./">home page</a></p>
                    </div>
                    </div>', 1);
        } catch (Exception $e) {
            echo $e->getMessage();
            break;
        }
        $queryIns = "
            INSERT INTO avcp_lotto
            (
                `denominazione`,
                `codiceFiscaleProp`,
                `anno`,
                `cig`,
                `numAtto`,
                `oggetto`,
                `sceltaContraente`,
                `dataInizio`,
                `dataUltimazione`,
                `importoAggiudicazione`,
                `importoSommeLiquidate`,
                `userins`
            )
            VALUES
            (
                '" . $denominazione . "',
                '" . $codiceFiscaleProp . "',
                '" . $anno . "',
                '" . $cig . "',
                '" . $numAtto . "',
                '" . $oggetto . "',
                '" . $sceltaContraente . "',
                " . $dataInizioIns . ",
                " . $dataUltimazioneIns . ",
                '" . $importoAggiudicazione . "',
                '" . $importoSommeLiquidate . "',
                '" . $_SESSION['user'] . "'
            )
            ";
        try {
            if (!$res = $db->query($queryIns))
                throw new Exception('
                    <div class="row">
                    <div class="span8 offset2">
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Errore nell\'inserimento della gara:</strong><br /> ' . $db->error . '
                        </div>
                        <p>Qualcuno dei dati non era corretto, <a href="?mask=gara">ripeti l\'inserimento</a></p>
                    </div>
                    </div>', 1);
        } catch (Exception $e) {
            echo $e->getMessage();
            break;
        }
        // Se l'inserimento è riuscito
        $id = $db->insert_id;
        if ($db->affected_rows == 1) {
            echo '<div class="row">
                    <div class="span8 offset2">
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Gara inserita correttamente!</strong>
                        </div>
                    </div>
                </div>' . PHP_EOL;
            require_once AVCP_DIR . 'app/view/garaInsModOk.php';
        }
        $res->free;
        break;
    case 'modificaGara':
        $id = (int) $_POST['id'];
        // Verifico che l'anno sia compreso tra 2012 e anno corrente
        // Il 2012 è stato il primo anno della rilevazione.
        try {
            if (!in_array($anno, $anniValidi))
                throw new Exception('
                    <div class="row">
                    <div class="span8 offset2">
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Errore nella modifica della gara:</strong><br />le modifiche per l\'anno ' . $anno . ' non sono consentite
                            <br />Sono accettati questi anni: ' . implode(", ", $anniValidi) . '. 
                        </div>
                        <p>Torna alla <a href="./">home page</a></p>
                    </div>
                    </div>', 1);
        } catch (Exception $e) {
            echo $e->getMessage();
            break;
        }
        // TODO Spostare in alto dopo il controllo del $_GET[do]
        // Imposto il cig al valore di default se non specificato
        $queryMod = "
            UPDATE avcp_lotto
            SET
                `cig` = '" . $cig . "',
                `denominazione` = '" . $denominazione . "',
                `codiceFiscaleProp` = '" . $codiceFiscaleProp . "',
                `anno` = '" . $anno . "',
                `oggetto` = '" . $oggetto . "',
                `numAtto` = '" . $numAtto . "',
                `sceltaContraente` = '" . $sceltaContraente . "',
                ".$dataInizioUpd."
                ".$dataUltimazioneUpd."
                `importoAggiudicazione` = '" . $importoAggiudicazione . "',
                `importoSommeLiquidate` = '" . $importoSommeLiquidate . "',
                `userins` = '" . $_SESSION['user'] . "'
            WHERE
                id = '" . $id . "';
        ";
        try {
            if (!$res = $db->query($queryMod))
                throw new Exception('
                    <div class="row">
                    <div class="span8 offset2">
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Errore nell\'aggiornamento della gara:</strong><br /> ' . $db->error . '
                        </div>
                        <p>Qualcuno dei dati non era corretto, <a href="?mask=gara">torna alla modifica</a></p>
                    </div>
                    </div>', 1);
        } catch (Exception $e) {
            echo $e->getMessage();
            break;
        }

        // Se la modifica è riuscita
        if ($db->affected_rows == 1) {
            echo '<div class="row">
                    <div class="span8 offset2">
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Gara modificata correttamente!</strong>
                        </div>
                    </div>
                </div>' . PHP_EOL;
            require_once AVCP_DIR . 'app/view/garaInsModOk.php';
        } else {
            echo '<div class="row">
                    <div class="span8 offset2">
                        <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Nessun dato modificato.</strong>
                        </div>
                    </div>
                </div>' . PHP_EOL;
            require_once AVCP_DIR . 'app/view/garaInsModOk.php';
        }
        break;
    case 'elencaGareAnno':
        require_once AVCP_DIR . 'app/view/garaElenca.php';
        break;
    case 'duplicaGara':
        require_once AVCP_DIR . 'app/view/garaDuplica.php';
        break;
    case 'errore':
        require_once AVCP_DIR . 'footer.php';
        break;
    default:
        if (!empty($_GET['id'])) {
            $id = (int) $_GET['id'];
            $queryGara = "
                    SELECT * FROM avcp_lotto
                    WHERE
                         id = '" . $db->real_escape_string($id) . "'";
            $resGara = $db->query($queryGara);
            $gara = $resGara->fetch_assoc();
            foreach ($gara as $key => $value) {
                $$key = stripslashes($value);
            }
            $resGara->free();
            $formDo = 'modificaGara';
            $formButton = 'Modifica';
            if (array_key_exists($anno, $bloccati)) {
                $formDo = '';
                $formButton = 'Non modificabile';
            }
        } else {
            $formDo = 'inserisciGara';
            $formButton = 'Inserisci';
        }
        $queryContr = "SELECT * FROM avcp_sceltaContraenteType ORDER BY ruolo ASC";
        $res = $db->query($queryContr);
        $numContr = $res->num_rows;
        $ruoloOutput = '';
        for($x = 0; $x < $numContr; $x++) {
            $row = $res->fetch_assoc();
            $ruolo = stripslashes($row['ruolo']);
            if (isset($sceltaContraente) && $ruolo == $sceltaContraente) {
                $ruoloOutput .= '<option value="' . $ruolo . '" selected>' . $ruolo . '</option>';
            } else {
                $ruoloOutput .= '<option value="'. $ruolo . '">' . $ruolo . '</option>';
            }
        }
        $res->free();
        require_once AVCP_DIR . 'app/view/garaForm.php';
        break;
}
