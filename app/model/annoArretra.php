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
$annoDa = (int) $_GET['anno'];
$annoA = $annoDa + 1;
$messOk = false;

if ($_SESSION['user'] == 'admin' && $bloccati[$annoDa] == 's') {
    $queryDel = "DELETE `avcp_lotto`, `avcp_ld`
        FROM `avcp_lotto`
        LEFT JOIN `avcp_ld`
        ON avcp_lotto.id = avcp_ld.id
        WHERE (avcp_lotto.flag = 'da${annoDa}') AND (avcp_lotto.anno = '${annoA}')";
    $resDel = $db->query($queryDel);
    if ($resDel != false) {
        $queryUp = "UPDATE `bloccati` SET avanzato = 'n' WHERE anno = '${annoDa}'";
        $resUp = $db->query($queryUp);
        $messOk = true;
        $message = "<strong>Tutto ok:</strong> Arretramento per l'anno ${annoA} eseguito";
    } else {
        $message = "<strong>Errore:</strong> query fallita: <br /> ${$queryDel}";
    }
} else {
    $message = "<strong>Errore:</strong> impossibile effettuare l'arretramento per i lotti dell'anno ${annoA}";
}
