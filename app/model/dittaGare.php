<?php
if (!empty($_GET['id'])) {
    $cf = $db->real_escape_string($_GET['id']);
}
$query = "
SELECT
    `d`.*,
    `ld`.id,
    `ld`.funzione,
    `l`.oggetto,
    `l`.anno,
    `l`.numAtto,
    `l`.cig
FROM
    `avcp_ditta` AS `d`,
    `avcp_ld` AS `ld`,
    `avcp_lotto` AS `l`
WHERE
    `ld`.codiceFiscale = `d`.codiceFiscale AND `d`.codiceFiscale LIKE '".$cf."' AND `l`.id = `ld`.id
ORDER BY
    `l`.anno DESC ,
    `l`.id ASC,
    `ld`.funzione ASC
";
$res = $db->query($query);
$quanti = $res->num_rows;
for ($i = 0; $i < $quanti; $i++) {
    $rows[$i] = $res->fetch_assoc();
    foreach ($rows[$i] as $key => $value) {
        $rows[$i][$key] = mb_convert_encoding($value, "UTF-8", "ISO-8859-15, ISO-8859-1, CP1251, CP1252"); 
    }
}
require_once AVCP_DIR . 'app/view/dittaGare.php';
