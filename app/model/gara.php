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
}
switch ($_GET['do']){
    case 'inserisciGara':
        // Verifico che l'anno non sia bloccato
        try {
            if (array_key_exists($anno, $bloccati))
                throw new Exception('
                    <div class="row">
                    <div class="span8 offset2">
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Errore nell\'inserimento della gara:</strong><br /> gli inserimenti per l\'anno ' . $anno . ' sono bloccati
                        </div>
                        <p>Torna alla <a href="./">home page</a></p>
                    </div>
                    </div>', 1);
        } catch (Exception $e) {
            echo $e->getMessage();
            break;
        }
        // Verifico che l'anno sia compreso tra 2012 e anno corrente + 1
        try {
            if (empty(filter_input(INPUT_POST, 'anno', FILTER_VALIDATE_INT, $anniValidi)))
                throw new Exception('
                    <div class="row">
                    <div class="span8 offset2">
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Errore nell\'inserimento della gara:</strong><br /> l\'anno ' . $anno . ' non è consentito
                            <br />Sono accettati anni compresi tra ' . $anniValidi['options']['min_range'] . ' e ' . $anniValidi['options']['max_range'] . '
                        </div>
                        <p>Torna alla <a href="./">home page</a></p>
                    </div>
                    </div>', 1);
        } catch (Exception $e) {
            echo $e->getMessage();
            break;
        }

        // Imposto il cig al valore di default se non specificato
        if (empty($cig) || $cig == '')
            $cig = '0000000000';
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
                '" . $dataInizio . "',
                '" . $dataUltimazione . "',
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
            require_once 'app/view/garaInsModOk.php';
        }
        $res->free;
        break;
    case 'modificaGara':
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        // Verifico che l'anno non sia bloccato
        try {
            if (array_key_exists($anno, $bloccati))
                throw new Exception('
                    <div class="row">
                    <div class="span8 offset2">
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Errore nella modifica della gara:</strong><br /> le modifiche per l\'anno ' . $anno . ' sono bloccate
                        </div>
                        <p>Torna alla <a href="./">home page</a></p>
                    </div>
                    </div>', 1);
        } catch (Exception $e) {
            echo $e->getMessage();
            break;
        }
        // Verifico che l'anno sia compreso tra 2012 e anno corrente + 1
        try {
            if (empty(filter_input(INPUT_POST, 'anno', FILTER_VALIDATE_INT, $anniValidi)))
                throw new Exception('
                    <div class="row">
                    <div class="span8 offset2">
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Errore nella modifica della gara:</strong><br /> l\'anno ' . $anno . ' non è consentito
                            <br />Sono accettati anni compresi tra ' . $anniValidi['options']['min_range'] . ' e ' . $anniValidi['options']['max_range'] . '
                        </div>
                        <p>Torna alla <a href="./">home page</a></p>
                    </div>
                    </div>', 1);
        } catch (Exception $e) {
            echo $e->getMessage();
            break;
        }
        // Imposto il cig al valore di default se non specificato
        if (empty($cig) || $cig == '')
            $cig = '0000000000';
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
                `dataInizio` = '" . $dataInizio . "',
                `dataUltimazione` = '" . $dataUltimazione . "',
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
            require_once 'app/view/garaInsModOk.php';
        } else {
            echo '<div class="row">
                    <div class="span8 offset2">
                        <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Nessun dato modificato.</strong>
                        </div>
                    </div>
                </div>' . PHP_EOL;
            require_once 'app/view/garaInsModOk.php';
        }
        break;
    case 'elencaGareAnno':
        require_once 'app/view/garaElenca.php';
        break;
    case 'errore':
        require_once 'footer.php';
        break;
    default:
        if (!empty($_GET['id'])) {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
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
        for($x = 0; $x < $numContr; $x++) {
            $row = $res->fetch_assoc();
            $ruolo = stripslashes($row['ruolo']);
            if ($ruolo == $sceltaContraente) {
                $ruoloOutput .= '<option value="' . $ruolo . '" selected>' . $ruolo . '</option>';
            } else {
                $ruoloOutput .= '<option value="' . $ruolo . '">' . $ruolo . '</option>';
            }
        }
        $res->free();
        require_once 'app/view/garaForm.php';
        break;
}
