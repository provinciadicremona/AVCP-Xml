<?php
if (!empty(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT))) {
    $queryVer = "SELECT count(id) as quante, anno FROM avcp_lotto
        WHERE id = '" . $db->real_escape_string($_GET['id']) . "' GROUP BY anno";
    $resVer = $db->query($queryVer);
    $row = $resVer->fetch_assoc();
    $numVer = $row['quante'];
    $anno = $row['anno'];

    if ($numVer != 1) {
        $messElimina = "La gara che desideri eliminare non esiste!";
    } elseif (array_key_exists($anno, $bloccati)) {
        $messElimina = "La gara che desideri eliminare è bloccata.";
    } else {
        $queryDelLotto = "DELETE FROM avcp_lotto WHERE id = '" . $db->real_escape_string($_GET['id']) . "'";
        $queryDelLd = "DELETE FROM avcp_ld WHERE id = '" . $db->real_escape_string($_GET['id']) . "'";
        $resDelLotto = $db->query($queryDelLotto);
        $resDelLd = $db->query($queryDelLd);
        $messElimina = "Gara eliminata";
    }
    require_once __DIR__ . '/../view/garaElimina.php';
}
