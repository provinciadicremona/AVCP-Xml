<?php
$id = (int) filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!empty($id)) {
    $query = "SELECT id, anno, oggetto, cig, numAtto
        FROM avcp_lotto
        WHERE id = '" . $id . "'";
    try {
        if (!$res = $db->query($query))
            throw new Exception('
                <div class="row">
                <div class="span12">
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Errore nella selezione della gara:</strong><br /> ' . $db->error . '
                    </div>
                </div>
                </div>', 1);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    // Se la selezione è riuscita
    if ($res->num_rows == 1) {
        $gara = $res->fetch_assoc();
        foreach ($gara as $key => $value) {
            $$key = stripslashes($value);
        }
    }
}

switch ($_GET['do']){
    case 'aggiungiDitta':
        try {
            if (empty($_POST['codiceFiscale']))
                throw new Exception('
                        <div class="row">
                        <div class="span12">
                            <div class="alert alert-error">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Errore: manca il codice fiscale</strong>
                            </div>
                        </div>
                        </div>', 1);
        } catch (Exception $e) {
            echo $e->getMessage();
            break;
        }

        $ruolo = null;
        $raggruppamento = 0;
        // Verifico se la ditta è presente nell'anagrafica
        $queryCe = "SELECT count(codiceFiscale) AS presente FROM avcp_ditta
        WHERE codiceFiscale = '" . $db->real_escape_string($_POST['codiceFiscale']) . "'";
        $resCe = $db->query($queryCe);
        $ce = $resCe->fetch_assoc();
        foreach ($_POST as $key => $value) {
            $$key = trim($db->real_escape_string($value));
        }
        $resCe->free();
        if ($ce['presente'] == 0) {
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
                            <p>Devi reinserire i dati sull\'azienda, <a href="?mask=gara&amp;do=partecipantiGara&amp;cig=' . $cig . '">torna alla maschera dei partecipanti</a></p>
                        </div>
                        </div>', 1);
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                break;
            }
            // Controllo il Codice fiscale
            $codiceFiscale = strtoupper($codiceFiscale);
            try {
                if (($estero != 1 && !controllaCF($codiceFiscale)) && $_POST['action'] != 'Seleziona') {
                    throw new Exception('
                        <div class="row">
                        <div class="span8 offset2">
                            <div class="alert alert-error">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Errore nei dati sull\'azienda:</strong><br />
                                Il Codice Fiscale inserito (' . $codiceFiscale . ') non è corretto.
                            </div>
                            <p>Devi reinserire i dati sull\'azienda, <a href="?mask=gara&amp;do=partecipantiGara&amp;cig=' . $cig . '">torna alla maschera d\'inserimento</a></p>
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
                            <p>Devi reinserire i dati sull\'azienda, <a href="?mask=gara&amp;do=partecipantiGara&amp;cig=' . $cig . '">torna alla maschera d\'inserimento</a></p>
                        </div>
                        </div>', 1);
                    }
                }
            } catch (Exception $e) {
                die($e->getMessage());
                break;
            }
            $queryInsDitta = "INSERT INTO avcp_ditta
            (codiceFiscale, ragioneSociale, estero, userins)
            VALUES
            (
                '" . $codiceFiscale . "',
                '" . $ragioneSociale . "',
                '" . $estero . "',
                '" . $_SESSION['user'] . "'
            )
            ";
            try {
                if (!$resInsDitta = $db->query($queryInsDitta))
                    throw new Exception('
                        <div class="row">
                        <div class="span12">
                            <div class="alert alert-error">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Errore nella creazione della ditta:</strong><br /> ' . $db->error . '
                            </div>
                        </div>
                        </div>', 1);
            } catch (Exception $e) {
                echo $e->getMessage();
                break;
            }
        }
        if (empty($funzione)) {
            $funzione = '01-PARTECIPANTE';
        }
        // TODO: Controllare che la ditta non sia presente e usare $id al posto del valore in $_GET
        $queryPresente = "SELECT count(id) as inserita FROM `avcp_ld` WHERE id = ".$id." and codiceFiscale LIKE '".$codiceFiscale."'";
        $resPresente = $db->query($queryPresente);
        $presente = $resPresente->fetch_assoc();
        try {
            if ($presente['inserita'] > 0 ) 
                throw new Exception('
                    <div class="row">
                        <div class="span12">
                            <div class="alert alert-error">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Impossibile inserire la ditta:</strong><br /> una ditta con questo codice fiscale
                                &egrave; gi&agrave; assegnata a questa gara.
                            </div>
                        </div>
                        </div>', 1);
        } catch (Exception $e) {
            echo $e->getMessage();
            break;
        }
        $queryAggDitta = "
            INSERT INTO avcp_ld
                (
                    `id`,
                    `cig`,
                    `ruolo`,
                    `codiceFiscale`,
                    `funzione`,
                    `raggruppamento`
                )
            VALUES
                (
                    '" . $_GET['id'] . "',
                    '" . $cig . "',
                    '" . $ruolo . "',
                    '" . $codiceFiscale . "',
                    '" . $funzione . "',
                    '" . $raggruppamento . "'
                )";
        try {
            if (!($resAggDitta = $db->query($queryAggDitta)))
                throw new Exception('
                    <div class="row">
                    <div class="span12">
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Errore nell\'aggiunta dell\'azienda alla gara:</strong><br /> ' . mysql_error() . '
                        </div>
                    </div>
                    </div>', 1);
        } catch (Exception $e) {
            echo $e->getMessage();
            break;
        }
        break;
    case 'eliminaDitta':
        if (!empty($_GET['codiceFiscale']) && !empty($id)) {
            $queryDelDitta = "DELETE FROM avcp_ld
                WHERE
                    codiceFiscale = '" . trim($db->real_escape_string($_GET['codiceFiscale'])) . "'
                AND
                    id = '" . $db->real_escape_string($id) . "'
                AND
                    ruolo = '" . trim($db->real_escape_string($_GET['ruolo'])) . "'
                    ";
            $resDelDitta = $db->query($queryDelDitta);
            echo '
            <div class="row">
                <div class="span12">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Ditta eliminata dalla gara</strong>
                    </div>
                </div>
            </div>
            ';
        }
        break;
    case 'eliminaAggiudicatari':
        if (!is_null($_GET['codiceFiscale']) && !is_null($_GET['id']) && !is_null($_GET['raggruppamento'])) {
            if ($_GET['raggruppamento'] < 1) {
                $queryDelAg = "DELETE FROM avcp_ld
                    WHERE
                    codiceFiscale = '" . $db->real_escape_string(trim($_GET['codiceFiscale'])) . "'
                    AND
                    id = '" . $db->real_escape_string(trim($_GET['id'])) . "'
                    AND
                    funzione = '02-AGGIUDICATARIO'
";
            } else {
                $queryDelAg = "DELETE FROM avcp_ld
                    WHERE
                    raggruppamento = '" . $db->real_escape_string(trim($_GET['raggruppamento'])) . "'
                    AND
                    id = '" . $db->real_escape_string(trim($_GET['id'])) . "'
                    AND
                    funzione = '02-AGGIUDICATARIO'
";
            }
            $resDelAg = $db->query($queryDelAg);
            echo '
            <div class="row">
                <div class="span12">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Aggiudicatario rimosso</strong>
                    </div>
                </div>
            </div>
            ';
        }
        break;
    default:
        break;
}

$aggiudicata = 0;
if (!empty($_GET['action'])) {
    if ($_GET['action'] == 'aggiudica') {
        if ($_GET['raggruppamento'] > 0) {

            $querySel = "SELECT * FROM avcp_ld
                WHERE
                raggruppamento = '" . $db->real_escape_string($_GET['raggruppamento']) . "'
                AND
                id = '" . $db->real_escape_string($_GET['id']) . "'";
            $resSel = $db->query($querySel);
            $numSel = $resSel->num_rows;
            if ($numSel < 1) {
                // XXX: Aggiungere errore?
                exit();
            }
            $queryValues = null;
            $ultima = $numSel - 1;
            for($x = 0; $x < $numSel; $x++) {
                $gara[$x] = $resSel->fetch_assoc();
                foreach ($gara[$x] as $key => $value) {
                    $gara[$x][$key] = $db->real_escape_string($value);
                }
                $queryValues .= "(
                    '" . $gara[$x]['id'] . "',
                    '" . $gara[$x]['cig'] . "',
                    '" . $gara[$x]['codiceFiscale'] . "',
                    '" . $gara[$x]['ruolo'] . "',
                    '02-AGGIUDICATARIO',
                    '" . $gara[$x]['raggruppamento'] . "'
                )";
                if ($x < $ultima) {
                    $queryValues .= ',' . PHP_EOL;
                }
            }
            $resSel->free();
            $queryAg = "INSERT INTO avcp_ld
                (
                    `id`,
                    `cig`,
                    `codiceFiscale`,
                    `ruolo`,
                    `funzione`,
                    `raggruppamento`
                )
                VALUES
                " . $queryValues;
            $res = $db->query($queryAg);
            echo '
                <div class="row">
                <div class="span12">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Aggiudicatario Impostato</strong>
                    </div>
                </div>
                </div>';
        } else {
            $querySel = "SELECT * FROM avcp_ld
                WHERE
                codiceFiscale = '" . $db->real_escape_string($_GET['codiceFiscale']) . "'
                AND
                id = '" . $db->real_escape_string($_GET['id']) . "'";
            $resSel = $db->query($querySel);
            $numSel = $resSel->num_rows;
            if ($numSel != 1) {
                // XXX: Aggiungere errore?
                exit();
            }
            $gara = $resSel->fetch_assoc();
            foreach ($gara as $key => $value) {
                $gara[$key] = $db->real_escape_string($value);
            }
            $resSel->free();
            $queryAg = "INSERT INTO avcp_ld
                (
                    `id`,
                    `cig`,
                    `ruolo`,
                    `codiceFiscale`,
                    `funzione`,
                    `raggruppamento`
                )
                VALUES
                (
                    '" . $gara['id'] . "',
                    '" . $gara['cig'] . "',
                    '" . $gara['ruolo'] . "',
                    '" . $gara['codiceFiscale'] . "',
                    '02-AGGIUDICATARIO',
                    '0'
                )";
            $res = $db->query($queryAg);
            echo '
                <div class="row">
                <div class="span12">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Aggiudicatario Impostato</strong>
                    </div>
                </div>
                </div>';
        }
    }
}
require_once AVCP_DIR . 'app/model/estraiPartecipantiGara.php';
require_once AVCP_DIR . 'app/view/garaPartTop.php';
require_once AVCP_DIR . 'app/view/garaPartElenco.php';
