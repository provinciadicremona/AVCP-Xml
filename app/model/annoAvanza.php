<?php
$annoStart = (int) $_GET['anno'];
$annoEnd = $annoStart + 1;
$messOk = false;
if ($_SESSION['user'] == 'admin' && $bloccati[$annoStart] == 'n') {
    $qLotto = "SELECT
        *
        FROM
        avcp_lotto
        WHERE
        (importoAggiudicazione > importoSommeLiquidate OR importoAggiudicazione = 0)
        AND anno = '${annoStart}'";
    $resLotto = $db->query($qLotto);
    if ($resLotto) {
        $messOk = true;
        $message = '<strong>Tutto ok:</strong> eseguito l\'avanzamento di ' . $resLotto->num_rows . ' lotti non ancora conclusi</h2>';
    } else {
        $message = '<strong>Errore:</strong> la query ' . $qLotto . ' è fallita!';
    }
    $numLotti = $resLotto->num_rows;

    for($x = 0; $x < $numLotti; $x++) {
        $oldLotto = $resLotto->fetch_assoc();
        while (list ( $key, $value ) = each($oldLotto)) {
            $oldLotto[$key] = $db->real_escape_string($value);
        }
        $qdLotto = "INSERT INTO `avcp_lotto`
            (
                `anno`,
                `cig`,
                `numAtto`,
                `codiceFiscaleProp`,
                `denominazione`,
                `oggetto`,
                `sceltaContraente`,
                `importoAggiudicazione`,
                `dataInizio`,
                `dataUltimazione`,
                `importoSommeLiquidate`,
                `userins`,
                `flag`)
                VALUES
                (
                    '${annoEnd}',
                    '${oldLotto[cig]}',
                    '${oldLotto[numAtto]}',
                    '${oldLotto[codiceFiscaleProp]}',
                    '${oldLotto[denominazione]}',
                    '${oldLotto[oggetto]}',
                    '${oldLotto[sceltaContraente]}',
                    '${oldLotto[importoAggiudicazione]}',
                    '${oldLotto[dataInizio]}',
                    '${oldLotto[dataUltimazione]}',
                    '${oldLotto[importoSommeLiquidate]}',
                    '${oldLotto[userins]}',
                    'da${annoStart}'
                )";
        $db->query($qdLotto);

        // Prendo il nuovo id del lotto da usare sulla tabella avcp_ld
        $oldLotto['newId'] = $db->insert_id;

        // Effettuo le associazioni ditte/lotto sulla tabella ld
        $qld = "SELECT * from `avcp_ld` WHERE `id` = '${oldLotto['id']}'";
        $resLd = $db->query($qld);
        $numLd = $resLd->num_rows;

        for($i = 0; $i < $numLd; $i++) {
            $ld = $resLd->fetch_assoc();
            while (list ( $lkey, $lvalue ) = each($ld)) {
                $ld[$lkey] = $db->real_escape_string(stripslashes($lvalue));
            }
            $qnld = "INSERT INTO avcp_ld
                (
                    `id`,
                    `cig`,
                    `codiceFiscale`,
                    `ruolo`,
                    `funzione`,
                    `raggruppamento`,
                    `flag`
                ) VALUES
                (
                    '${oldLotto[newId]}',
                    '${ld[cig]}',
                    '${ld[codiceFiscale]}',
                    '${ld[ruolo]}',
                    '${ld[funzione]}',
                    '${ld[raggruppamento]}',
                    'da${annoStart}'
                )";
            $resnld = $db->query($qnld);
        }
    }

    $queryUpBloc = "UPDATE bloccati SET avanzato = 's' WHERE anno = '${annoStart}'";
    $resUpbloc = $db->query($queryUpBloc);
} else {
    $message = '<strong>Errore:</strong> Avanzamento non possibile!';
}
