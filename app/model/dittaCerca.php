<?php
$formTitle = 'Cerca una ditta';
$formAction = '?mask=ditta&amp;do=modificaDitta';
$formCig = null;
$formRag = null;
$formAlertCF = null;
$formRuolo = null;
if (!empty($_GET['codiceFiscale'])) {
    $formButton = '<input class="btn btn-large btn-primary" type="submit" name="action" value="Modifica &raquo;" />';
} else {
    $formButton = '<input class="btn btn-large btn-primary" type="submit" name="action" value="Seleziona" />';
}
// Se sto aggiungendo un'azienda ad una gara
if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $formAlertCF = 'NON MODIFICARE SE ESTRATTO DALLA RICERCA';
    $formTitle = 'Cerca o inserisci <small>una ditta da aggiungere alla gara</small>';
    $formAction = '?mask=gara&amp;do=aggiungiDitta&amp;id=' . $id;
    $formCig = '<input type="hidden" name="id" id="id" value="' . $id . '" />';
    $formButton = '<input class="btn btn-large btn-primary" type="submit" value="Aggiungi alla gara &raquo;" />';
    if (isset($_GET['raggruppamento'])) {
        $raggruppamento = filter_input(INPUT_GET, 'raggruppamento', FILTER_VALIDATE_INT);
        $formAction .= '&amp;raggruppamento=' . $raggruppamento;
        $formRag = '<input type="hidden" name="raggruppamento" id="raggruppamento" value="' . $raggruppamento . '" />';
        $queryRuolo = "SELECT * FROM avcp_ruoloType ORDER BY sceltaContraente ASC";
        $resRuolo = $db->query($queryRuolo);
        $formRuolo = '
            <fieldset>
                <legend>Ruolo nel raggruppamento ' . $raggruppamento . ':</legend>' . PHP_EOL;
        $formRuolo .= '<select name="ruolo" id="ruolo">' . PHP_EOL;
        $num = $resRuolo->num_rows;
        for($x = 0; $x < $num; $x++) {
            $ruolo = $resRuolo->fetch_assoc();
            $formRuolo .= '<option value="' . $ruolo['sceltaContraente'] . '">' . $ruolo['sceltaContraente'] . '</option>' . PHP_EOL;
        }
        $formRuolo .= '</select>' . PHP_EOL . '</fieldset>' . PHP_EOL;
    }
}
require_once __DIR__ . '/../view/dittaCerca.php';
