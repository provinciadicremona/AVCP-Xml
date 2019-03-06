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

/* Creo la tabella delle versioni, se non esiste, e vado a leggere il valore
 * massimo per confrontarlo con quello del file version.txt Se è minore, allora
 * procedo a lanciare le query di aggiornamento.
 *
 * Query di creazione: 
 * CREATE TABLE IF NOT EXISTS `versioni` (`ver`CHAR(10) CHARACTER SET utf8 NOT NULL) ENGINE InnoDB DEFAULT CHARSET=utf8 COMMENT='Versioni del programma installate';
 */
$fname = AVCP_DIR."version.txt";
$fvh = fopen($fname, "r");
$currentVersion = strtr(fread($fvh, 1024), '_', '.');
fclose($fvh);
$msgUpdate = '';
$toUpdate = false;
if (checkTable($db, 'avp_versioni') === false) {
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
 * @param string $tableName Il nome della tabella da cercare
 *
 * @return bool 
 */
function checkTable($db, $tableName) {
    $db->real_escape_string(trim($tableName));
    $query = "SHOW TABLES LIKE '".$tableName."'";
    $res = $db->query($query);
    if ($res->num_rows === 0) 
        return false;
    return true;
}

