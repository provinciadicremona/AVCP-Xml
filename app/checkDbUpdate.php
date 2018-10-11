<?php
$queryUp = "SHOW COLUMNS FROM `avcp_lotto` LIKE 'chiuso'";
$res = $db->query($queryUp);
$isUpdated= $res->num_rows;
if ($isUpdated === 0) {
    $msgUpdate  = "<strong>Devo preparare il database per la nuova versione del programma</strong><br />".PHP_EOL;
    $msgUpdate .= "Aggiorno tabella 'avcp_lotto'...   ";
    $msgUpdate .= "<strong>OK</strong><br />".PHP_EOL;
    $msgUpdate .= "Creo vista 'avcp_export_ods'...   ";
    $msgUpdate .= "<strong>OK</strong><br />".PHP_EOL;
    $msgUpdate .= "Aggiorno vista 'avcp_vista_ditte'...   ";
    $msgUpdate .= "<strong>OK</strong><br />".PHP_EOL;
    $msgUpdate .= "pippo".PHP_EOL;
?>

    <div class="row">
        <div class="span5 offset3">
<?php echo $msgUpdate.PHP_EOL; ?>
        </div>
    </div>
<br />
<?php
}
