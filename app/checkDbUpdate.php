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
 * che l'applicazione debba essere aggiornata.
 *
 * Stabilisco dalla struttura del db quale sia la
 * versione di partenza e faccio partire gli
 * aggiornamenti necessari.
 *
 * CRITERI PER DISTINGUERE LE VECCHIE VERSIONI:
 * ============================================
 *   - Se non ha la vista avcp_export_ods -> 0.7.1
 *   - Se la vista avcp_vista_ditte non ha il campo "aggiudica" -> 0.7.2
 *
 *
 * FLUSSO DELLO SCRIPT DI AGGIORNAMENTO:
 * ======================================
 * - acquisisco il la versione da installare leggendo il file vesrion.txt
 * - controllo che esista la tabella avcp_versioni
 *   Se non esiste:
 *      - Creo la tabella
 *      - Inserisco il numero di versione
 *      - Verifico che sia una nuova installazione
 *        Se è nuova installazione.
 *          - Tutto ok, non devo aggiornare
 *        Altrimenti:
 *          - Valuto da che versione
 *          - Procedo con gli aggiornamenti necessari
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
$msgUpdate  = null;
$toUpdate   = false;
$updateFrom = null;
// Leggo il file version.txt per stabilire a che versione aggiornare
if ($currentVersion = getCurrentVersion() == false) {
    die "Non riesco a leggere il file version.txt. Aggiornamento fallito!";
}
// Controllo che esista la tabella delle versioni e se non c'è la creo
// e poi stabilisco se aggiornare dalla 0.7.1 o dalla 0.7.2
if (checkIfExistsTable($db, 'avcp_versioni') === false) {
    createVersionTable($db);
    updateVersionTable($db, $currentVersion);
    $updateFrom = fromWhichOldVersion($db);
    $toUpdate = true;
} else {
    // Leggo l'ultima versione installata e decido cosa fare
    $query = "SELECT `numero` FROM `avcp_versioni` ORDER BY `data` DESC LIMIT 0,1";
    $res = $db->query($query);
    $row = $res->fetch_assoc($res);
    if ($row['numero'] < $currentVersion) {
        $updateFrom = $row['numero'];
        $toUpdate = true;
        updateVersionTable($db, $currentVersion);
    }
}

if ($toUpdate === true) {
    $msgUpdate  .= "<h3>Aggiornamento dalla versione ".$updateFrom." alla versione ".$currentVersion." del programma:</h3>".PHP_EOL;
    switch $updateFrom {
    case '0.7.1':
        if (false === updateTableAvcpLotto($db)) {
            die "Non posso aggiungere campo chiuso ad avcp_lotti. Aggiornamento fallito!";
        }
        if (false === addExportOds($db)) {
            die "Errore in addExportOds. Aggiornamento fallito!";
        }
        if (false === updateVistaDitte($db)) {
            die "Errore in updateVistaDitte. Aggiornamento fallito!";
        }
        if (false === updateTableSceltaContraente($db)) {
            die "Errore in updateTableSceltaContraente. Aggiornamento fallito!";
        }
        if (false === updateLottiSceltaContraente($db)) {
            die "Errore in updateLottiSceltaContraente. Aggiornamento fallito!";
        }
        break;
    case '0.7.2':
        if (false === updateVistaDitte($db) == false) {
            die "Errore in updateVistaDitte. Aggiornamento fallito!";
        }
        if (false === updateTableSceltaContraente($db)) {
            die "Errore in updateTableSceltaContraente. Aggiornamento fallito!";
        }
        if (false === updateLottiSceltaContraente($db)) {
            die "Errore in updateLottiSceltaContraente. Aggiornamento fallito!";
        }
        break;
    case '0.7.4':
        if (false === updateTableSceltaContraente($db)) {
            die "Errore in updateTableSceltaContraente. Aggiornamento fallito!";
        }
        if (false === updateLottiSceltaContraente($db)) {
            die "Errore in updateLottiSceltaContraente. Aggiornamento fallito!";
        }
        break;
    default:
        die "Non riesco a capire da che versione aggiornare. Aggiornamento fallito!";
        break;
    }
    $msgUpdate .= "<h4>Aggiornamento da versione ".$updateFrom." terminato!</h4>".PHP_EOL;
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
 * Verifico l'esistenza di una tabella
 *
 * @param object $db Database connection handler
 * @param string $tName Name of the table to check
 *
 * @return bool 
 */
function checkIfExistsTable($db, $tName) {
    $db->real_escape_string(trim($tName));
    $query = "SHOW TABLES LIKE '".$tName."'";
    if (false === $res = $db->query($query)) {
        die "Non riesco a verificare l'esistenza della tabella ".$tName.". Aggiornamento fallito!";
    }
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
    if (false === $db->query($query))
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
    if (false === $db->query($queryDelDitte)) {
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
    if (false === $db->query($query)) {
        return false;
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
    if (false === $db->query($query)) {
        return false;
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
    if (false === $db->query($query)) {
        return false;
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
    $queryLotto = "ALTER TABLE `avcp_lotto` ADD `chiuso` BOOLEAN NOT NULL DEFAULT FALSE AFTER `flag`";
    if (false === $db->query($queryLotto)) {
        return false;
    }
    return true;
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
    if (false === $db->query($query)) {
        return false;
    }
    $query = " CREATE TABLE `avcp_sceltaContraenteType` 
        (`ruolo` varchar(255) NOT NULL COMMENT 'tipo scelta contraente') 
        ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tipo scelta contraente'";
    if (false === $db->query($query)) {
        return false;
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
    if (false === $db->query($query)) {
        return false;
    }
    return true;
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
    if (false === $db->query($query)) {
        return false;
    }
    $query = "UPDATE `avcp_lotto` SET `sceltaContraente` = '04-PROCEDURA NEGOZIATA SENZA PREVIA PUBBLICAZIONE' WHERE `sceltaContraente` LIKE '04-%'";
    if (false === $db->query($query)) {
        return false;
    }
    $query = "UPDATE `avcp_lotto` SET `sceltaContraente` = '06-PROCEDURA NEGOZIATA SENZA PREVIA INDIZIONE DI GARA (SETTORI SPECIALI)' WHERE `sceltaContraente` LIKE '06-%'";
    if (false === $db->query($query)) {
        return false;
    }
    $query = "UPDATE `avcp_lotto` SET `sceltaContraente` = '17-AFFIDAMENTO DIRETTO EX ART. 5 DELLA LEGGE 381/91' WHERE `sceltaContraente` LIKE '17-%'";
    if (false === $db->query($query)) {
        return false;
    }
    $query = "UPDATE `avcp_lotto` SET `sceltaContraente` = '22-PROCEDURA NEGOZIATA CON PREVIA INDIZIONE DI GARA (SETTORI SPECIALI)' WHERE `sceltaContraente` LIKE '22-%'";
    if (false === $db->query($query)) {
        return false;
    }
    $query = "UPDATE `avcp_lotto` SET `sceltaContraente` = '23-AFFIDAMENTO DIRETTO' WHERE `sceltaContraente` LIKE '23-%'";
    if (false === $db->query($query)) {
        return false;
    }
    $query = "UPDATE `avcp_lotto` SET `sceltaContraente` = '27-CONFRONTO COMPETITIVO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE' WHERE `sceltaContraente` LIKE '27-%'";
    if (false === $db->query($query)) {
        return false;
    }
    return true;
}

/*
 * Leggo il file version.txt per determinare la 
 * versione corrente del programma.
 *
 * Se non riesco a leggerla, restituisco false
 *
 * @param object $db Database connection handler
 *
 * @return bool, string 
 */
function getCurrentVersion() {
    $fname = AVCP_DIR."version.txt";
    if (false === $fvh = fopen($fname, "r")) {
        return false;
    }
    $currentVersion = strtr(fread($fvh, 1024), '_', '.');
    fclose($fvh);
    return $currentVersion;
}

function updateVersionTable($db, $currentVersion) {
    if (empty($currentVersion)) {
        return false;
    }
    $query = "INSERT INTO `avcp_versioni` (`numero`, `data`) VALUES ('".$currentVersion."', NOW())";
    if (false === $db->query($query) {
        return false;
    }
    return true;
}

// Determino se vengo da 0.7.1/2/4
// La versione 0.7.4 dovrebbe essere in uso solo 
// all'interno della Provincia di Cremona
function fromWhichOldVersion($db) {
    $queryCheckOds = "SHOW TABLES LIKE 'avcp_export_ods";
    if (false === $resCheckOds = $db->query($queryCheckOds)) {
        return false;
    }
    if ($resCheckOds->num_rows !== 1) {
        return '0.7.1';
    }
    $query = "SHOW COLUMNS FROM `avcp_vista_ditte` LIKE 'aggiudica'";
    if (false === $res = $db->query($query)) {
        return false;
    }
    if ($res->num_rows === 0) {
        return '0.7.2';
    }
    return '0.7.4';
}

function updateVistaDitte($db) {
        $queryDelDitte = "DROP VIEW IF EXISTS `avcp_vista_ditte";
        if (false === $db->query($queryDelDitte)) {
            return false;
        }
        $query = "
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
        if (false === $db->query($query)) {
            return false;
        }
        return true;
}
