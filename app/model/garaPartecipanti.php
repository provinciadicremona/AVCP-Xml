<?php
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

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
            require_once 'app/functions.php';
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
        if (empty($funzione))
            $funzione = '01-PARTECIPANTE';
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
        if (!empty($_GET['codiceFiscale']) && !empty($id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT))) {
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
// Verifico la presenza dell'aggiudicatario
$queryVerAg = "SELECT count(id) as numAg FROM avcp_ld WHERE funzione = '02-AGGIUDICATARIO' AND id = '" . $db->real_escape_string($id) . "'";
$resVerAg = $db->query($queryVerAg);
$ceAg = $resVerAg->fetch_assoc();
$aggiudicata = $ceAg['numAg'];
$resVerAg->free();
$winner = array (
        'xxx' => 'xxx' 
);
// Se c'è lo mostro nel top
$outTopAg = null;
if ($aggiudicata > 0) {
    $queryTopAg = "SELECT ld.*, ditta.`ragioneSociale` FROM avcp_ld AS ld, avcp_ditta AS ditta
		WHERE ld.id = '" . $db->real_escape_string($id) . "'
			AND ld.`codiceFiscale` = ditta.`codiceFiscale`
			AND ld.`funzione` = '02-AGGIUDICATARIO'
		ORDER BY raggruppamento ASC, ruolo ASC, ragioneSociale ASC";
    try {
        if (!$resTopAg = $db->query($queryTopAg))
            throw new Exception('
					<div class="row">
					<div class="span12">
						<div class="alert alert-error">
	    					<button type="button" class="close" data-dismiss="alert">&times;</button>
	    					<strong>Errore nella selezione aggiudicatari</strong><br /> ' . mysql_error() . '
	    				</div>
					</div>
					</div>', 1);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    $numTopAg = $resTopAg->num_rows;
    $outTopAg = '<div class="well">' . PHP_EOL;
    $oldAg = 0;
    $raggOld = 0;
    for($x = 0; $x < $numTopAg; $x++) {
        $row = $resTopAg->fetch_assoc();
        foreach ($row as $key => $value) {
            $rows[$x][$key] = stripslashes($value);
        }
        
        $winner[] = $rows[$x]['codiceFiscale'];
        
        if ($x == 0) {
            $outTopButton = '<a class="btn btn-large btn-warning" href="?mask=gara&amp;do=eliminaAggiudicatari&amp;id=' . $id . '&amp;codiceFiscale=' . $rows[$x]['codiceFiscale'] . '&amp;raggruppamento=' . $rows[$x]['raggruppamento'] . '">Rimuovi aggiudicatari <i class="icon-thumbs-down icon-white"></i></a>';
        }
        if (!empty($rows[$x]['ruolo'])) {
            if ($rows[$x]['raggruppamento'] != $raggOld) {
                if ($x > 0) {
                    $outTopAg .= '<hr />' . PHP_EOL;
                }
                $raggOld = $rows[$x]['raggruppamento'];
            }
            $outTopAg .= 'Ruolo: ' . $rows[$x]['ruolo'] . ' - ';
            $outTopAg .= 'C.F.: ' . $rows[$x]['codiceFiscale'] . ' - ' . $rows[$x]['ragioneSociale'] . '<br />' . PHP_EOL;
        } else {
            if ($x > 0) {
                $outTopAg .= '<hr />' . PHP_EOL;
            }
            $outTopAg .= 'C.F.: ' . $rows[$x]['codiceFiscale'] . ' - ' . $rows[$x]['ragioneSociale'] . PHP_EOL;
        }
    }
    $outTopAg .= '</div>' . PHP_EOL;
    $resTopAg->free();
}

$queryPart = "SELECT ld.*, ditta.`ragioneSociale` FROM avcp_ld AS ld, avcp_ditta AS ditta
		WHERE ld.id = '" . $db->real_escape_string($id) . "'
			AND ld.`codiceFiscale` = ditta.`codiceFiscale`
			AND ld.`funzione` = '01-PARTECIPANTE'
		ORDER BY raggruppamento ASC, ruolo ASC, ragioneSociale ASC";
try {
    if (!$resPart = $db->query($queryPart))
        throw new Exception('
					<div class="row">
					<div class="span12">
						<div class="alert alert-error">
	    					<button type="button" class="close" data-dismiss="alert">&times;</button>
	    					<strong>Errore nella selezione dei partecipanti alla gara:</strong><br /> ' . mysqli_error($db) . '
	    				</div>
					</div>
					</div>', 1);
} catch (Exception $e) {
    echo $e->getMessage();
}
$numPart = $resPart->num_rows;
for($x = 0; $x < $numPart; $x++) {
    $row = $resPart->fetch_assoc();
    foreach ($row as $key => $value) {
        $rows[$x][$key] = stripslashes($value);
    }
}
$resPart->free();
require_once 'app/view/garaPartTop.php';
require_once 'app/view/garaPartElenco.php';