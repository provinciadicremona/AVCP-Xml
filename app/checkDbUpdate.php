<?php
/* ----------------------------------------------
 * VERSIONE 0.8
 * ----------------------------------------------
 * Se non esiste la tabella avcp_versioni presumo
 * che l'aggiornamento venga fatto dalla versione 7.1
 * e faccio partire tutti gli aggiornamenti.
 *
 * Controllo comunque la presenza di ogni variazione per
 * non fare danni a chi viene dalla 0.7.2
*/

 /* 
 * FLUSSO:
 * - acquisisco il la versione da installare leggendo il file vesrion.txt
 * - controllo che esista la tabella avcp_versioni
 *   Se non esiste:
 *    - Creo la tabella
 *    - Inserisco il numero di versione
 *    - Procedo ad aggiornare come da 0.7.1
 *
 *   Se esiste:
 *    - leggo il numero di versione
 *    - lo confronto con quello del file
 *    
 *      Se è minore:
 *       - Verifico gli aggiornamenti mancanti
 *       - Lancio in sequenza gli aggiornamenti
 *
 *      Altrimenti:
 *       - Tutto ok, non devo aggiornare
 *
 * - Esco dalla fase di aggiornamento e torno al login
 *
 */

$fname = AVCP_DIR."version.txt";
$fvh = fopen($fname, "r");
$currentVersion = strtr(fread($fvh, 1024), '_', '.');
fclose($fvh);
$msgUpdate = '';
$toUpdate = false;
if (checkTable($db, 'avp_versioni') === false) {
    createVersionTable($db);
    updateVersionTable($db, $currentVersion);
    $toUpdate = true;
    $msgUpdate  .= "<h3>Aggiornamento alla versione ".$currentVersion." del programma:</h3>".PHP_EOL;
}

// Controllo la presenza del campo 'aggiudica' nella vista
// 'avcp_vista_ditte' per capire se il db ha bisogno di 
// essere aggiornato.
// Questa è l'ultima modifica fatta e mi dice con certezza
// lo stato di aggiornamento del db.
$queryUp = "SHOW COLUMNS FROM `avcp_vista_ditte` LIKE 'aggiudica'";
$res = $db->query($queryUp);
$isUpdated= $res->num_rows;
if ($isUpdated === 0) {
    $msgUpdate  .= "<strong>Devo preparare il database per la nuova versione del programma</strong><br />".PHP_EOL;
    try {
        // Se manca il campo 'chiuso', aggiorno  'avcp_lotto'
        $queryCheckLotto = "SHOW COLUMNS FROM `avcp_lotto` LIKE 'chiuso'";
        $resCheckLotto = $db->query($queryCheckLotto);
        $isUpLotto= $resCheckLotto->num_rows;
        if ($isUpLotto === 0) {
            $msgUpdate .= "Aggiorno tabella 'avcp_lotto'...   ";
            $queryLotto = "ALTER TABLE `avcp_lotto` ADD `chiuso` BOOLEAN NOT NULL DEFAULT FALSE AFTER `flag`";
            if ($resLotto = $db->query($queryLotto)) {
                $msgUpdate .= "<strong>OK</strong><br />".PHP_EOL;
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    } 
    // Verifico la presenza, e nel caso creo, della vista 'avcp_export_ods'
    try {
        $queryCheckOds = "SHOW TABLES LIKE 'avcp_export_ods";
        $resCheckOds = $db->query($queryCheckOds);
        $isUpOds= $resCheckOds->num_rows;
        if ($isUpOds === 0) {
            $msgUpdate .= "Creo vista 'avcp_export_ods'...   ";
            $queryOds = "
            CREATE VIEW `avcp_export_ods` AS select 
                `l`.`id` AS `id`,
                `l`.`anno` AS `anno`,
                `l`.`numAtto` AS `numAtto`,
                `l`.`cig` AS `cig`,
                `l`.`oggetto` AS `oggetto`,
                `l`.`sceltaContraente` AS `sceltaContraente`,
                `l`.`dataInizio` AS `dataInizio`,
                `l`.`dataUltimazione` AS `dataUltimazione`,
                `l`.`importoAggiudicazione` AS `importoAggiudicazione`,
                `l`.`importoSommeLiquidate` AS `importoSommeLiquidate`,
                `l`.`chiuso` AS `chiuso`,
                (select count(0) 
                    from `avcp_ld` `ldl` 
                    where ((`l`.`id` = `ldl`.`id`) 
                        and (`ldl`.`funzione` = '01-PARTECIPANTE'))) AS `partecipanti`,
                (select count(0) from `avcp_ld` `ldl` 
                    where ((`l`.`id` = `ldl`.`id`) 
                        and (`ldl`.`funzione` = '02-AGGIUDICATARIO'))) AS `aggiudicatari`,
                `l`.`userins` AS `userins`,
                group_concat(`ditta`.`ragioneSociale` separator 'xxxxx') AS `nome_aggiudicatari` 
                from ((`avcp_lotto` `l` 
                        left join `avcp_ld` `ld` 
                        on(((`l`.`id` = `ld`.`id`) 
                                and (`ld`.`funzione` = '02-AGGIUDICATARIO')))) 
                    left join `avcp_ditta` `ditta` 
                    on((`ld`.`codiceFiscale` = `ditta`.`codiceFiscale`))) 
                group by `l`.`id` 
                order by `l`.`anno`,
                `l`.`id`";
            if ($resOds = $db->query($queryOds)) {
                $msgUpdate .= "<strong>OK</strong><br />".PHP_EOL;
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    } 
    // Aggiorno 'avcp_vista_ditte' per ultima
    try {
        $queryDelDitte = "DROP VIEW IF EXISTS `avcp_vista_ditte";
        $resDelDitte= $db->query($queryDelDitte);
        $msgUpdate .= "Aggiorno vista 'avcp_vista_ditte'...   ";
        $queryDitte = "
        CREATE VIEW `avcp_vista_ditte` AS
        SELECT
            `d`.`codiceFiscale` AS `codiceFiscale`,
            `d`.`ragioneSociale` AS `ragioneSociale`,
            `d`.`estero` AS `estero`,
            `d`.`flag` AS `flag`,
            `d`.`userins` AS `userins`,
            (
            SELECT
                COUNT(0)
            FROM
                `avcp_ld` `ldl`
            WHERE
                (
                    `d`.`codiceFiscale` = `ldl`.`codiceFiscale`
                ) AND(
                    `ldl`.`funzione` LIKE '01-PARTECIPANTE'
                )
        ) AS `partecipa`,
        (
        SELECT
            COUNT(0)
        FROM
            `avcp_ld` `ldl`
        WHERE
            (
                `d`.`codiceFiscale` = `ldl`.`codiceFiscale`
            ) AND(
                `ldl`.`funzione` LIKE '02-AGGIUDICATARIO'
            )
        ) AS `aggiudica`
        FROM
            (
                `avcp_ditta` `d`
            LEFT JOIN
                `avcp_ld` `ld`
            ON
                (
                    `d`.`codiceFiscale` = `ld`.`codiceFiscale`
                ) AND(
                    `ld`.`funzione` LIKE '01-PARTECIPANTE'
                )
            )
        GROUP BY
            `d`.`codiceFiscale`
        ORDER BY
            `d`.`ragioneSociale`";
        if ($resDitte = $db->query($queryDitte)) {
            $msgUpdate .= "<strong>OK</strong><br />".PHP_EOL;
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    } 
    $msgUpdate .= "<h4>Aggiornamento db terminato</h4>".PHP_EOL;
}

/* 
 * Se è stato effettuato un aggiornamento,
 * visualizzo i messaggi relativi alle singole 
 * fasi del processo.
 */
if ($toUpdate === true) {
    echo '
    <div class="row">
        <div class="span8 offset2">
        '.$msgUpdate.'
        </div>
    </div>
    <br />';
}

/*
 * Controllo che esista la tabella `avcp_versioni`
 *
 * @param object $db Database connection handler
 * @param string $tName Name of the table to check
 *
 * @return bool 
 */
function checkTable($db, $tName) {
    $db->real_escape_string(trim($tName));
    $query = "SHOW TABLES LIKE '".$tName."'";
    $res = $db->query($query);
    if ($res->num_rows === 0) 
        return false;
    return true;
}

/*
 * Creo la tabella `avcp_versioni`
 *
 * @param object $db Database connection handler
 *
 * @return bool 
 */
function createVersionTable($db) {
    $query = "CREATE TABLE `avcp_versioni` (
        `major` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Major version''s number',
        `minor` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Minor version''s number',
        `release` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Release version''s number'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Versioni del programma installate'";
    if (!$db->query($query))
        return false;
    return true;
}
/*
 * Aggiorno la vista `avcp_vista_ditte`
 *
 * @param object $db Database connection handler
 *
 * @return bool 
 */
function updateViewDitte($db) {
    $queryDeletw = "DROP VIEW IF EXISTS `avcp_vista_ditte";
    if (!$db->query($queryDelDitte)) {
        return false;
    }
    $query= "
        CREATE VIEW `avcp_vista_ditte` AS
        SELECT
            `d`.`codiceFiscale` AS `codiceFiscale`,
            `d`.`ragioneSociale` AS `ragioneSociale`,
            `d`.`estero` AS `estero`,
            `d`.`flag` AS `flag`,
            `d`.`userins` AS `userins`,
            (
            SELECT
                COUNT(0)
            FROM
                `avcp_ld` `ldl`
            WHERE
                (
                    `d`.`codiceFiscale` = `ldl`.`codiceFiscale`
                ) AND(
                    `ldl`.`funzione` LIKE '01-PARTECIPANTE'
                )
        ) AS `partecipa`,
        (
        SELECT
            COUNT(0)
        FROM
            `avcp_ld` `ldl`
        WHERE
            (
                `d`.`codiceFiscale` = `ldl`.`codiceFiscale`
            ) AND(
                `ldl`.`funzione` LIKE '02-AGGIUDICATARIO'
            )
        ) AS `aggiudica`
        FROM
            (
                `avcp_ditta` `d`
            LEFT JOIN
                `avcp_ld` `ld`
            ON
                (
                    `d`.`codiceFiscale` = `ld`.`codiceFiscale`
                ) AND(
                    `ld`.`funzione` LIKE '01-PARTECIPANTE'
                )
            )
        GROUP BY
            `d`.`codiceFiscale`
        ORDER BY
            `d`.`ragioneSociale`";
    if (!$db->query($query)) {
        return false;
    }
    return true;
}

/*
 * Creo la vista `avcp_export_ods`
 *
 * @param object $db Database connection handler
 *
 * @return bool 
 */
function createViewExportOds($db) {
    ;
}

/*
 * Aggiorno la tabella `avcp_lotto`
 * controllo se manca la colonna "chiuso" e nel caso la aggiungo
 *
 * @param object $db Database connection handler
 *
 * @return bool 
 */
function updateTableAvcpLotto($db) {
    $query = "SHOW COLUMNS FROM `avcp_lotto` LIKE 'chiuso'";
    $res = $db->query($query);
    $isUpdated= $res->num_rows;
    if ($isUpdated === 1) {
        return true;
    }
    $queryLotto = "ALTER TABLE `avcp_lotto` ADD `chiuso` BOOLEAN NOT NULL DEFAULT FALSE AFTER `flag`";
    if ($resLotto = $db->query($queryLotto)) {
        return true;
    }
    return false;
}

/*
 * Aggiorno la tabella `avcp_sceltaContraenteType`
 * aggiungendo i codici 29,30,31
 *
 * @param object $db Database connection handler
 *
 * @return bool 
 */
function updateTableSceltaContraente($db) {
    ;
}
