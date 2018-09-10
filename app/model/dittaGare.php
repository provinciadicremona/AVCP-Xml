<h1>
    Dettaglio gare ditta
</h1>
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
echo $quanti;

