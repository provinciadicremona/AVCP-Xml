<?php
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
            $outTopAg .= 'C.F.: ' . $rows[$x]['codiceFiscale'] . ' - ';
            $outTopAg .='<a href="?mask=ditta&do=dittaGare&event=aggiudica&id=' . $rows[$x]['codiceFiscale'] . '">';
            $outTopAg .= $rows[$x]['ragioneSociale'] . '</a><br />' . PHP_EOL;
        } else {
            if ($x > 0) {
                $outTopAg .= '<hr />' . PHP_EOL;
            }
            $outTopAg .= 'C.F.: ' . $rows[$x]['codiceFiscale'] . ' - ';
            $outTopAg .='<a href="?mask=ditta&do=dittaGare&event=aggiudica&id=' . $rows[$x]['codiceFiscale'] . '">';
            $outTopAg .= $rows[$x]['ragioneSociale'] . '</a><br />' . PHP_EOL;
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
