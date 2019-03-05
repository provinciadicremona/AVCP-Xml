<?php
if (!empty($_GET['do'])) {
    foreach ($_POST as $key => $value) {
        $$key = $db->real_escape_string(trim($value));
    }
    // Controllo che non manchino i campi principali
    try {
        if (empty($ragioneSociale)) {
            throw new Exception('
                <div class="row">
                <div class="span8 offset2">
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Errore nei dati sull\'azienda:</strong><br />
                        Manca questo dato:
                        <ul>
                            <li>Ragione sociale: ' . $ragioneSociale . '</li>
                            <li>Codice Fiscale: ' . $codiceFiscale . '</li>
                        </ul>
                    </div>
                    <p>Devi reinserire i dati sull\'azienda, <a href="?mask=ditta">torna alla maschera d\'inserimento</a></p>
                </div>
                </div>', 1);
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        $_GET['do'] = 'errore';
    }
    // Controllo il Codice fiscale
    $codiceFiscale = strtoupper($codiceFiscale);
    try {
        if ($estero != 1 && !controllaCF($codiceFiscale) &&  $_POST['action'] != 'Seleziona') {
            throw new Exception('
                <div class="row">
                <div class="span8 offset2">
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Errore nei dati sull\'azienda:</strong><br />
                        Il Codice Fiscale inserito (' . $codiceFiscale . ') non è corretto.
                    </div>
                    <p>Devi reinserire i dati sull\'azienda, <a href="?mask=ditta">torna alla maschera d\'inserimento</a></p>
                </div>
                </div>', 1);
        } else {
            if (empty($codiceFiscale)) {
                throw new Exception('
                <div class="row">
                <div class="span8 offset2">
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Errore nei dati sull\'azienda:</strong><br />
                        Il Codice Fiscale estero è vuoto.
                    </div>
                    <p>Devi reinserire i dati sull\'azienda, <a href="?mask=ditta">torna alla maschera d\'inserimento</a></p>
                </div>
                </div>', 1);
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        $_GET['do'] = 'errore';
    }
}
switch ($_GET['do']){
    case 'inserisciDitta':
        $queryIns = "
            INSERT INTO avcp_ditta
            (
                `ragioneSociale`,
                `codiceFiscale`,
                `estero`,
                `userins`
            )
            VALUES
            (
                '" . $ragioneSociale . "',
                '" . $codiceFiscale . "',
                '" . $estero . "',
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
                            <strong>Errore nell\'inserimento dell\'azienda:</strong><br /> ' . $db->error . '
                        </div>
                        <p>Qualcuno dei dati non era corretto o l\'azienda è già presente <a href="?mask=ditta">ripeti l\'inserimento</a></p>
                    </div>
                    </div>', 1);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        // Se l'inserimento è riuscito
        if ($db->affected_rows == 1) {
            echo '<div class="row">
                    <div class="span8 offset2">
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Azienda inserita correttamente!</strong>
                        </div>
                    </div>
                </div>' . PHP_EOL;
            require_once AVCP_DIR . 'app/view/dittaInsModOk.php';
        }
        break;
    case 'modificaDitta':
        if (!empty($action) && $action == "Seleziona") {
            require_once AVCP_DIR . 'app/view/dittaInsModOk.php';
            break;
        }
        if (empty($oldCodiceFiscale))
            $oldCodiceFiscale = $codiceFiscale;
            $queryMod = "
            UPDATE avcp_ditta SET
                `ragioneSociale` = '" . $ragioneSociale . "',
                `codiceFiscale` = '" . $codiceFiscale . "',
                `estero` = '" . $estero . "',
                `userins` = '" . $_SESSION['user'] . "'
            WHERE
                `codiceFiscale` = '" . $oldCodiceFiscale . "'
            ";
            $queryModLd = "
            UPDATE avcp_ld SET
                `codiceFiscale` = '" . $codiceFiscale . "'
            WHERE
                `codiceFiscale` = '" . $oldCodiceFiscale . "'
            ";
        try {
            $res = $db->query($queryMod);
                $resLd = $db->query($queryModLd);
            if (!$res || !$resLd) {
                throw new Exception('
                    <div class="row">
                    <div class="span8 offset2">
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Errore nella modifica dell\'azienda:</strong><br /> ' . $db->error . '
                        </div>
                    </div>
                    </div>', 1);
            } else {
                echo '<div class="row">
                        <div class="span8 offset2">
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Azienda modificata correttamente!</strong>
                            </div>
                        </div>
                    </div>' . PHP_EOL;
                require_once AVCP_DIR . 'app/view/dittaInsModOk.php';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        break;
    case 'errore':
        require_once AVCP_DIR . 'footer.php';
        break;
    default:
        if (!empty($_GET['codiceFiscale'])) {
            $formDo = 'modificaDitta';
            $formButton = 'Modifica';
            $queryDitta = "SELECT * FROM avcp_ditta
                WHERE codiceFiscale = '" . $db->real_escape_string(trim($_GET['codiceFiscale'])) . "'";
            $resDitta = $db->query($queryDitta);
            $ditta = $resDitta->fetch_assoc();
            foreach ($ditta as $key => $value) {
                $$key = stripslashes($value);
            }
            if ($estero == 0) {
                $esteroRadio = array (
                        0 => 'checked',
                        1 => null
                );
            } else {
                $esteroRadio = array (
                        0 => null,
                        1 => 'checked'
                );
            }
        } else {
            $esteroRadio = array (
                    0 => 'checked',
                    1 => null
            );
            $formDo = 'inserisciDitta';
            $formButton = 'Inserisci';
        }
        require_once AVCP_DIR . 'app/view/dittaForm.php';
        break;
}
