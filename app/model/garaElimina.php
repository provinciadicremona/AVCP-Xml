<?php
if (!empty(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT))) {
    $queryVer = "SELECT count(id) as quante, anno FROM avcp_lotto
        WHERE id = '" . $db->real_escape_string($_GET['id']) . "' GROUP BY anno";

    $resVer       = $db->query($queryVer);
    $row          = $resVer->fetch_assoc();
    $numVer       = $row['quante'];
    $anno         = $row['anno'];
    $messElimina  = null;
    $errorElimina = null;

    if ($numVer != 1) {
        $messElimina  = "Cancellazione non riuscita!";
        $errorElimina = "La gara che desideri eliminare non esiste!";
    } elseif (array_key_exists($anno, $bloccati)) {
        $messElimina  = "Cancellazione non permessa!";
        $errorElimina = "La gara che desideri eliminare si riferisce ad un anno bloccato.";
    } else {
        $queryDelLotto = "DELETE FROM avcp_lotto WHERE id = '" . $db->real_escape_string($_GET['id']) . "'";
        $queryDelLd    = "DELETE FROM avcp_ld WHERE id = '" . $db->real_escape_string($_GET['id']) . "'";
        $resDelLotto = $db->query($queryDelLotto);
        if ($resDelLotto !== true) {
            $messElimina  = "Cancellazione non riuscita!";
            $errorElimina = "Problemi nella cancellazione dalla tabella avcp_lotti";
        } else {
            $resDelLd = $db->query($queryDelLd);
            if ($resDelLd !== true) {
                $messElimina  = "Cancellazione non riuscita!";
                $errorElimina = "Problemi nella cancellazione dalla tabella avcp_ld";
            } else {
                $messElimina = "Gara eliminata";
            }
        }
    }
    require_once __DIR__ . '/../view/garaElimina.php';
}
