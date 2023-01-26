<?php 
$annoCorrente = date("Y");

if (array_key_exists('idDaElenco', $_GET) === true) {
    $id = (int) $_GET['idDaElenco'];
} 
if (true === empty($id)) { 
    die("ERRORE: identificativo di gara non corretto");
}
$qLotto = "SELECT
    *
    FROM
    avcp_lotto
    WHERE
    id = '{$id}'";
$resLotto = $db->query($qLotto);

if ($resLotto->num_rows !== 1) {
    die ("Fallita la selezione della gara da duplicare");
}
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
            '{$annoCorrente}',
            '{$oldLotto['cig']}',
            '{$oldLotto['numAtto']}',
            '{$oldLotto['codiceFiscaleProp']}',
            '{$oldLotto['denominazione']}',
            '{$oldLotto['oggetto']}',
            '{$oldLotto['sceltaContraente']}',
            '{$oldLotto['importoAggiudicazione']}',
            NULLIF('{$oldLotto['dataInizio']}', ''),
            NULLIF('{$oldLotto['dataUltimazione']}', ''),
            '{$oldLotto['importoSommeLiquidate']}',
            '{$oldLotto['userins']}',
            ''
                )";
$db->query($qdLotto);
// Prendo il nuovo id del lotto da usare sulla tabella avcp_ld
$newId= $db->insert_id;

// Effettuo le associazioni ditte/lotto sulla tabella ld
$qld = "SELECT * from `avcp_ld` WHERE `id` = '{$id}'";
$resLd = $db->query($qld);
$numLd = $resLd->num_rows;

for($i = 0; $i < $numLd; $i++) {
    $ld = $resLd->fetch_assoc();
    while (list ( $lkey, $lvalue ) = each($ld)) {
        $ld[$lkey] = $db->real_escape_string(stripslashes($lvalue));
    }
    $qnld = "INSERT INTO avcp_ld
            (`id`, `cig`, `codiceFiscale`, `ruolo`, `funzione`, `raggruppamento`, `flag`) 
        VALUES
                ( '{$newId}',
                  '{$ld['cig']}',
                  '{$ld['codiceFiscale']}',
                  '{$ld['ruolo']}',
                  '{$ld['funzione']}',
                  '{$ld['raggruppamento']}',
                  ''
                )";
    $resnld = $db->query($qnld);
}
require_once AVCP_DIR . 'app/view/garaDuplica.php';
