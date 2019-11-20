<?php
/*
 * This file is part of project AVCP-Xml that can be found at:
 * https://github.com/provinciadicremona/AVCP-Xml
 * 
 * © 2013 Claudio Roncaglio <claudio.roncaglio@provincia.cremona.it>
 * © 2013 Gianni Bassini <gianni.bassini@provincia.cremona.it>
 * © 2013 Provincia di Cremona <sito@provincia.cremona.it>
 * 
 * SPDX-License-Identifier: GPL-3.0-only
*/
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
 *      - Creo la tabella
 *      - Inserisco il numero di versione
 *      - Verifico che sia una nuova installazione
 *        Se è nuova installazione.
 *          - Tutto ok, non devo aggiornare
 *        Altrimenti:
 *          - Procedo ad aggiornare come da 0.7.1
 *   Se esiste:
 *      - leggo il numero di versione
 *      - lo confronto con quello del file
 *          Se è minore:
 *             - Verifico gli aggiornamenti mancanti
 *             - Lancio in sequenza gli aggiornamenti
 *          Altrimenti:
 *             - Tutto ok, non devo aggiornare
 * - Esco dalla fase di aggiornamento e torno al login
 *
 */

$fname = AVCP_DIR."version.txt";
$fvh = fopen($fname, "r");
$currentVersion = strtr(fread($fvh, 1024), '_', '.');
fclose($fvh);
$msgUpdate = '';
$toUpdate = false;
if (checkVersionTable($db, 'avcp_versioni') === false) {
    createVersionTable($db);
    updateVersionTable($db, $currentVersion);
    $toUpdate = true;
    $msgUpdate  .= "<h3>Aggiornamento dalla versione 0.7.1 alla versione ".$currentVersion." del programma:</h3>".PHP_EOL;
} else {
    // Leggo l'ultima versione installata e decido cosa fare
    $query = "SELECT `numero` FROM `avcp_versioni` ORDER BY `data` DESC LIMIT 0,1";
    $res = $db->query($query);
    $row = $res->fetch_assoc($res);
    if ($row['numero'] > $currentVersion) {
        updateVersionTable($db, $currentVersion);
        $toUpdate = true;
        $msgUpdate  .= "<h3>Aggiornamento dalla versione ".$row['numero']." alla versione ".$currentVersion." del programma:</h3>".PHP_EOL;
    }
}

// Controllo la presenza del campo 'aggiudica' nella vista
// 'avcp_vista_ditte' per capire se il db ha bisogno di 
// essere aggiornato.
// Questa è l'ultima modifica fatta e mi dice con certezza
// lo stato di aggiornamento del db.
// TODO: verificare che questo mi distingua tra 0.7.1 e 0.7.2
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
function checkVersionTable($db, $tName) {
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
            `numero` VARCHAR(10) NOT NULL COMMENT 'Numero della versione' , 
            `data` DATE NOT NULL COMMENT 'Data di aggiornamento', 
            PRIMARY KEY (`numero`)
        ) ENGINE = InnoDB COMMENT = 'Versioni del programma installate'";
    if (!$db->query($query))
        return false;
    return true;
}
/*
 * Aggiorno la vista `avcp_vista_ditte`
 *
 * @param object $db Database connection handler
 *
 * @return bool, string
 */
function updateViewDitte($db) {
    $queryDeletw = "DROP VIEW IF EXISTS `avcp_vista_ditte";
    if (!$db->query($queryDelDitte)) {
        return "Fallito il DROP sulla vista avcp_vista_ditte. Aggiornamento vista abortito!";
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
        return "Aggiornamento vista avcp_vista_ditte fallito.";
    }
    return true;
}

/*
 * Creo la vista `avcp_export_ods`
 *
 * @param object $db Database connection handler
 *
 * @return bool, string 
 */
function createViewExportOds($db) {
    $query = "DROP TABLE IF EXISTS `avcp_export_ods`"
    if (!$db->query($query)) {
        return "Fallito il DROP sulla vista avcp_export_ods. Aggiornamento vista abortito!";
    }
    $query = " CREATE VIEW `avcp_export_ods` AS select 
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
    (SELECT COUNT(0) 
        FROM `avcp_ld` `ldl` 
        WHERE ((`l`.`id` = `ldl`.`id`) 
            AND (`ldl`.`funzione` = '01-PARTECIPANTE'))) AS `partecipanti`,
    (SELECT COUNT(0) FROM `avcp_ld` `ldl` 
        WHERE ((`l`.`id` = `ldl`.`id`) 
            AND (`ldl`.`funzione` = '02-AGGIUDICATARIO'))) AS `aggiudicatari`,
    `l`.`userins` AS `userins`,
    group_concat(`ditta`.`ragioneSociale` separator 'xxxxx') AS `nome_aggiudicatari` 
    FROM ((`avcp_lotto` `l` 
            LEFT JOIN `avcp_ld` `ld` 
            ON(((`l`.`id` = `ld`.`id`) 
                    and (`ld`.`funzione` = '02-AGGIUDICATARIO')))) 
        LEFT JOIN `avcp_ditta` `ditta` 
        ON(`ld`.`codiceFiscale` = `ditta`.`codiceFiscale`)) 
    GROUP BY `l`.`id` 
    ORDER BY `l`.`anno`,
    `l`.`id`";
    if (!$db->query($query)) {
        return "Fallita la creazione della vista avcp_export_ods. Aggiornamento vista abortito!";
    }
}

/*
 * Aggiorno la tabella `avcp_lotto`
 * controllo se manca la colonna "chiuso" e nel caso la aggiungo
 *
 * @param object $db Database connection handler
 *
 * @return bool, string 
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
    return "Fallita aggiunta campo 'chiuso' alla tabella avcp_lotto.";
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
    $query = "DROP TABLE IF EXISTS `avcp_sceltaContraenteType`";
    if (!$db->query($query)) {
        return "Fallito il DROP sulla tabella avcp_sceltaContraenteType. Aggiornamento tabella abortito!";
    }
    $query = " CREATE TABLE `avcp_sceltaContraenteType` 
        (`ruolo` varchar(255) NOT NULL COMMENT 'tipo scelta contraente') 
        ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tipo scelta contraente'";
    if (!$db->query($query)) {
        return "Fallita la creazione della tabella avcp_sceltaContraenteType. Aggiornamento tabella abortito!";
    }
    $query = 'INSERT INTO `avcp_sceltaContraenteType` (`ruolo`) VALUES
        ("01-PROCEDURA APERTA"),
        ("02-PROCEDURA RISTRETTA"),
        ("03-PROCEDURA NEGOZIATA PREVIA PUBBLICAZIONE"),
        ("04-PROCEDURA NEGOZIATA SENZA PREVIA PUBBLICAZIONE"),
        ("05-DIALOGO COMPETITIVO"),
        ("06-PROCEDURA NEGOZIATA SENZA PREVIA INDIZIONE DI GARA (SETTORI SPECIALI)"),
        ("07-SISTEMA DINAMICO DI ACQUISIZIONE"),
        ("08-AFFIDAMENTO IN ECONOMIA - COTTIMO FIDUCIARIO"),
        ("14-PROCEDURA SELETTIVA EX ART 238 C.7, D.LGS. 163/2006"),
        ("17-AFFIDAMENTO DIRETTO EX ART. 5 DELLA LEGGE 381/91"),
        ("21-PROCEDURA RISTRETTA DERIVANTE DA AVVISI CON CUI SI INDICE LA GARA"),
        ("22-PROCEDURA NEGOZIATA CON PREVIA INDIZIONE DI GARA (SETTORI SPECIALI)"),
        ("23-AFFIDAMENTO DIRETTO"),
        ("24-AFFIDAMENTO DIRETTO A SOCIETA\' IN HOUSE"),
        ("25-AFFIDAMENTO DIRETTO A SOCIETA\' RAGGRUPPATE/CONSORZIATE O CONTROLLATE NELLE CONCESSIONI E NEI PARTENARIATI"),
        ("26-AFFIDAMENTO DIRETTO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE"),
        ("27-CONFRONTO COMPETITIVO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE"),
        ("28-PROCEDURA AI SENSI DEI REGOLAMENTI DEGLI ORGANI COSTITUZIONALI"),
        ("29-PROCEDURA RISTRETTA SEMPLIFICATA"),
        ("30-PROCEDURA DERIVANTE DA LEGGE REGIONALE"),
        ("31-AFFIDAMENTO DIRETTO PER VARIANTE SUPERIORE AL 20% DELL\'IMPORTO CONTRATTUALE"),
        ("32-AFFIDAMENTO RISERVATO"),
        ("33-PROCEDURA NEGOZIATA PER AFFIDAMENTI SOTTO SOGLIA"),
        ("34-PROCEDURA ART.16 COMMA 2-BIS DPR 380/2001 PER OPERE URBANIZZAZIONE A SCOMPUTO PRIMARIE SOTTO SOGLIA COMUNITARIA"),
        ("35-PARTERNARIATO PER L’INNOVAZIONE"),
        ("36-AFFIDAMENTO DIRETTO PER LAVORI, SERVIZI O FORNITURE SUPPLEMENTARI"),
        ("37-PROCEDURA COMPETITIVA CON NEGOZIAZIONE"),
        ("38-PROCEDURA DISCIPLINATA DA REGOLAMENTO INTERNO PER SETTORI SPECIALI")';
    if (!$db->query($query)) {
        return "Fallito l'inserimento nella tabella avcp_sceltaContraenteType. Aggiornamento tabella abortito!";
    }
}


/*
 * Aggiorno i dati della abella `avcp_lotti con le nuove 
 * tipologie di scelta del contraente aggiornate il 4/11/2019
 * Modificano i codici 3, 4, 6, 17, 22 e 23
 *
 * @param object $db Database connection handler
 *
 * @return bool, string 
 */
function updateLottiSceltaContraente($db) {
    $query = "UPDATE `avcp_lotto` SET `sceltaContraente` = '03-PROCEDURA NEGOZIATA PREVIA PUBBLICAZIONE' WHERE `sceltaContraente` LIKE '03-%'";
    if (!$db->query($query)) {
        return "Fallito UPDATE scelta contraente codice 03. Aggiornamento lotti esistenti abortito!";
    }
    $query = "UPDATE `avcp_lotto` SET `sceltaContraente` = '04-PROCEDURA NEGOZIATA SENZA PREVIA PUBBLICAZIONE' WHERE `sceltaContraente` LIKE '04-%'";
    if (!$db->query($query)) {
        return "Fallito UPDATE scelta contraente codice 04. Aggiornamento lotti esistenti abortito!";
    }
    $query = "UPDATE `avcp_lotto` SET `sceltaContraente` = '06-PROCEDURA NEGOZIATA SENZA PREVIA INDIZIONE DI GARA (SETTORI SPECIALI)' WHERE `sceltaContraente` LIKE '06-%'";
    if (!$db->query($query)) {
        return "Fallito UPDATE scelta contraente codice 06. Aggiornamento lotti esistenti abortito!";
    }
    $query = "UPDATE `avcp_lotto` SET `sceltaContraente` = '17-AFFIDAMENTO DIRETTO EX ART. 5 DELLA LEGGE 381/91' WHERE `sceltaContraente` LIKE '17-%'";
    if (!$db->query($query)) {
        return "Fallito UPDATE scelta contraente codice 17. Aggiornamento lotti esistenti abortito!";
    }
    $query = "UPDATE `avcp_lotto` SET `sceltaContraente` = '22-PROCEDURA NEGOZIATA CON PREVIA INDIZIONE DI GARA (SETTORI SPECIALI)' WHERE `sceltaContraente` LIKE '22-%'";
    if (!$db->query($query)) {
        return "Fallito UPDATE scelta contraente codice 22. Aggiornamento lotti esistenti abortito!";
    }
    $query = "UPDATE `avcp_lotto` SET `sceltaContraente` = '23-AFFIDAMENTO DIRETTO' WHERE `sceltaContraente` LIKE '23-%'";
    if (!$db->query($query)) {
        return "Fallito UPDATE scelta contraente codice 23. Aggiornamento lotti esistenti abortito!";
    }
    $query = "UPDATE `avcp_lotto` SET `sceltaContraente` = '27-CONFRONTO COMPETITIVO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE' WHERE `sceltaContraente` LIKE '27-%'";
    if (!$db->query($query)) {
        return "Fallito UPDATE scelta contraente codice 27. Aggiornamento lotti esistenti abortito!";
    }
    return true;
}
