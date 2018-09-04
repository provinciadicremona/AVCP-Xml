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
