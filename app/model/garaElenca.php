<?php
if (empty($_GET['anno'])) {
    echo '
        <div class="row">
            <div class="span12">
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Manca l\'anno da visualizzare:</strong>
                </div>
                <p>Qualcuno dei dati non era corretto, <a href="./">torna alla Homepage</a></p>
            </div>
        </div>';
    return;
} else {
    $whereUser = null;
    if (key_exists('usersel', $_POST) && !empty($_POST['usersel'])) {
        $usersel = $db->real_escape_string($_POST['usersel']);
        switch ($usersel){
        case 'vuota':
            $whereUser = " AND l.`userins` = ''";
            break;
        case 'tutti':
            $whereUser = null;
            break;
        default:
            $whereUser = " AND l.`userins` = '" . $usersel . "'";
            break;
        } // end switch
    } else {
        $usersel = $_SESSION['user'];
        $whereUser = " AND l.`userins` = '" . $usersel . "'";
    }

    $anno = $db->real_escape_string(trim($_GET['anno']));
    $query = "
    SELECT
        l.`id`,
        l.`cig`,
        l.`oggetto`,
        l.`sceltaContraente`,
        l.`importoAggiudicazione`,
        l.`importoSommeLiquidate`,
        (SELECT count(*) FROM `avcp_ld` as ldl WHERE l.`id` = ldl.`id`  AND ldl.funzione = '01-PARTECIPANTE') as partecipanti,
        (SELECT count(*) FROM `avcp_ld` as ldl WHERE l.`id` = ldl.`id`  AND ldl.funzione = '02-AGGIUDICATARIO') as aggiudicatari,
        l.`userins`,
       GROUP_CONCAT(ditta.`ragioneSociale` SEPARATOR 'xxxxx') as nome_aggiudicatari
    FROM
       `avcp_lotto`as l
    LEFT JOIN
       `avcp_ld` as ld
    ON
       l.`id` = ld.`id` AND ld.funzione = '02-AGGIUDICATARIO'
    LEFT JOIN
       `avcp_ditta` as ditta
    ON
       ld.`codiceFiscale` = ditta.`codiceFiscale`
    WHERE
       l.`anno` = '" . $anno . "'" . $whereUser . "
    GROUP BY l.`id`
    ";

    $res = $db->query($query);
    $quante = $res->num_rows;
    $outElenco = null;
    for($x = 0; $x < $quante; $x++) {
        $contraente = null;
        $gara = $res->fetch_assoc();
        foreach ($gara as $key => $value) {
            $out[$x][$key] = htmlspecialchars(stripslashes(trim($value)));
        }
    } // end for
    $res->free();
}
require_once 'app/view/garaElenca.php';
