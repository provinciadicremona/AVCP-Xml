<?php
if (isset($_GET['idDaElenco']) && !is_null($_GET['idDaElenco'])) {
    $id = filter_input(INPUT_GET, 'idDaElenco', FILTER_VALIDATE_INT);
} else {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
}
isset($_GET['event']) || $_GET['event'] = null;
switch ($_GET['event']){
    case 'pagamenti': // Apre o chiude i pagamenti e passa a garaSelezionata
        $query = "UPDATE `avcp_lotto` SET `chiuso` = NOT (`chiuso`) WHERE `avcp_lotto`.`id` = '" . $id . "'";
        try {
            if (!($res = $db->query($query)) || $db->affected_rows != 1)
                throw new Exception('
                        <div class="row">
                        <div class="span8 offset2">
                            <div class="alert alert-error">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Errore nella modifica dei pagamenti</strong><br /> ' . $db->error . ' - ' . $db->affected_rows .'
                            </div>
                        </div>
                        </div>', 1);
        } catch (Exception $e) {
            echo $e->getMessage();
            break;
        }
    case 'garaSelezionata':
        $query = "SELECT
            id, anno, oggetto, cig, numAtto, sceltaContraente, dataInizio, dataUltimazione,
            importoAggiudicazione, importoSommeLiquidate, chiuso
            FROM avcp_lotto
            WHERE id = '" . $id . "'";
        try {
            if (!($res = $db->query($query)) || $res->num_rows != 1)
                throw new Exception('
                        <div class="row">
                        <div class="span8 offset2">
                            <div class="alert alert-error">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Errore nella selezione della gara:</strong><br /> ' . $db->error . '
                            </div>
                        </div>
                        </div>', 1);
        } catch (Exception $e) {
            echo $e->getMessage();
            break;
        }
        // Se la selezione è riuscita
        if ($res->num_rows == 1) {
            $gara = $res->fetch_assoc();
            foreach ($gara as $key => $value) {
                $$key = stripslashes($value);
            }
            $res->free();
            require_once __DIR__ . '/../view/garaInsModOk.php';
        }
        break;
        default:
            require_once __DIR__ . '/../view/garaCerca.php';
            break;
}
