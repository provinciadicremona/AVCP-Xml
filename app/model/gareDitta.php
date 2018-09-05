<h1>
    Dettaglio gare ditta
</h1>
<?php

$query = "
SELECT
    `d`.*,
    `ld`.id,
    `l`.oggetto,
    `l`.cig
FROM
    `avcp_ditta` AS d,
    `avcp_ld` AS `ld`,
    `avcp_lotto` AS l
WHERE
    ld.codiceFiscale = d.codiceFiscale AND d.codiceFiscale LIKE 'RNCCLD75H23I849F' AND ld.funzione LIKE '02-AGGIUDICATARIO' AND l.id = ld.id
GROUP BY
    `l`.`id`";

