<?php
// Effettua una ricerca nella tabella delle ditte
// e restituisce un array di risultati in formato json
require_once '../config.php';
$cerca = $db->real_escape_string($_GET['term']);
$query = "SELECT `codiceFiscale`, `ragioneSociale`, `estero`
    FROM avcp_ditta
    WHERE
    ragioneSociale LIKE '%" . $cerca . "%'
    OR
    codiceFiscale LIKE '%" . $cerca . "%'";

$res = $db->query($query);
$num = $res->num_rows;

$matches = null;
for ($x = 0; $x < $num; $x++){
    $row = $res->fetch_assoc();
    $row_set['codiceFiscale'] = $row['codiceFiscale'];
    $row_set['ragioneSociale'] = $row['ragioneSociale'];
    $row_set['estero'] = $row['estero'];
    $row_set['value'] = $row['ragioneSociale'];
    $row_set['label'] = "{$row['ragioneSociale']}, {$row['codiceFiscale']}";
    $matches[] = $row_set;
}
$res->free();
// Patch json per versioni php < 5.2
if (version_compare(PHP_VERSION, '5.2', '<')) {
    require_once 'ajaxFunctions.php';
        echo array_to_json($matches);
} else {
    echo json_encode($matches);
}
