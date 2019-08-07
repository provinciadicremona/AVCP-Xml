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
if (array_key_exists('id', $_GET)) {
    $idDel       = (int) $_GET['id'];
    $queryVer    = "SELECT count(id) as quante, anno 
                    FROM avcp_lotto
                    WHERE id = '" . $db->real_escape_string($idDel) . "'
                    GROUP BY anno";
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
        $queryDelLotto = "DELETE FROM avcp_lotto WHERE id = '" . $db->real_escape_string($idDel) . "'";
        $queryDelLd    = "DELETE FROM avcp_ld WHERE id = '" . $db->real_escape_string($idDel) . "'";
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
    require_once AVCP_DIR . 'app/view/garaElimina.php';
}
