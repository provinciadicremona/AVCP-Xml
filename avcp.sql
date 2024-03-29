 -- -------------------------------------------------------

 -- This file is part of project AVCP-Xml that can be found at:
 -- https://github.com/provinciadicremona/AVCP-Xml
 -- 
 -- © 2013 Claudio Roncaglio <claudio.roncaglio@provincia.cremona.it>
 -- © 2013 Gianni Bassini <gianni.bassini@provincia.cremona.it>
 -- © 2013 Provincia di Cremona <sito@provincia.cremona.it>
 -- 
 -- SPDX-License-Identifier: GPL-3.0-only


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Struttura della tabella `avcp_ditta`
--

CREATE TABLE `avcp_ditta` (
    `codiceFiscale` varchar(255) CHARACTER SET utf8 NOT NULL,
    `ragioneSociale` varchar(250) CHARACTER SET utf8 NOT NULL,
    `estero` varchar(1) CHARACTER SET utf8 NOT NULL DEFAULT '0',
    `userins` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
    `flag` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
    UNIQUE KEY `ditta` (`codiceFiscale`),
    UNIQUE KEY `cf-rs` (`codiceFiscale`,`ragioneSociale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='tabella ditte avcp';

-- --------------------------------------------------------

--
-- Struttura della tabella `avcp_ld`
--

CREATE TABLE `avcp_ld` (
    `id` int(11) unsigned DEFAULT NULL,
    `cig` varchar(10) NOT NULL,
    `codiceFiscale` varchar(255) NOT NULL,
    `ruolo` varchar(255) NOT NULL,
    `funzione` set('01-PARTECIPANTE','02-AGGIUDICATARIO') NOT NULL DEFAULT '02-AGGIUDICATARIO' COMMENT 'cosa fa la ditta',
    `raggruppamento` int(11) NOT NULL DEFAULT '0',
    `flag` varchar(8) DEFAULT NULL,
    KEY `gara-ditta` (`cig`,`codiceFiscale`),
    KEY `codiceFiscale` (`codiceFiscale`),
    KEY `id-funz` (`id`,`funzione`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Relazione lotto/ditta';

-- --------------------------------------------------------

--
-- Struttura della tabella `avcp_lotto`
--

CREATE TABLE `avcp_lotto` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `anno` int(4) NOT NULL,
    `cig` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '0000000000',
    `numAtto` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
    `codiceFiscaleProp` varchar(11) CHARACTER SET utf8 NOT NULL DEFAULT 'Inserire CF',
    `denominazione` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT 'Denominazione Ente',
    `oggetto` varchar(250) CHARACTER SET utf8 NOT NULL,
    `sceltaContraente` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '00-DA DEFINIRE',
    `importoAggiudicazione` decimal(15,2) DEFAULT '0.00',
    `dataInizio` date DEFAULT NULL,
    `dataUltimazione` date DEFAULT NULL,
    `importoSommeLiquidate` decimal(15,2) DEFAULT '0.00',
    `userins` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
    `flag` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
    `chiuso` BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (`id`),
    KEY `anno-user` (`anno`,`userins`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='tabella avcp del CIG/Lotto';

-- --------------------------------------------------------

--
-- Struttura della tabella `avcp_ruoloType`
--

CREATE TABLE `avcp_ruoloType` (
    `sceltaContraente` varchar(255) NOT NULL COMMENT 'tipo scelta contraente',
    UNIQUE KEY `sceltaContraente` (`sceltaContraente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tipo scelta contraente';

-- --------------------------------------------------------

--
-- Dump dei dati per la tabella `avcp_ruoloType`
--

INSERT INTO `avcp_ruoloType` (`sceltaContraente`) VALUES
('01-MANDANTE'),
('02-MANDATARIA'),
('03-ASSOCIATA'),
('04-CAPOGRUPPO'),
('05-CONSORZIATA');

-- --------------------------------------------------------

--
-- Struttura della tabella `avcp_sceltaContraenteType`
--

CREATE TABLE `avcp_sceltaContraenteType` (
    `ruolo` varchar(255) NOT NULL COMMENT 'tipo scelta contraente',
    UNIQUE KEY `ruolo` (`ruolo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tipo scelta contraente';

-- --------------------------------------------------------

--
-- Dump dei dati per la tabella `avcp_sceltaContraenteType`
--

INSERT INTO `avcp_sceltaContraenteType` (`ruolo`) VALUES
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
("24-AFFIDAMENTO DIRETTO A SOCIETA' IN HOUSE"),
("25-AFFIDAMENTO DIRETTO A SOCIETA' RAGGRUPPATE/CONSORZIATE O CONTROLLATE NELLE CONCESSIONI E NEI PARTENARIATI"),
("26-AFFIDAMENTO DIRETTO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE"),
("27-CONFRONTO COMPETITIVO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE"),
("28-PROCEDURA AI SENSI DEI REGOLAMENTI DEGLI ORGANI COSTITUZIONALI"),
("29-PROCEDURA RISTRETTA SEMPLIFICATA"),
("30-PROCEDURA DERIVANTE DA LEGGE REGIONALE"),
("31-AFFIDAMENTO DIRETTO PER VARIANTE SUPERIORE AL 20% DELL'IMPORTO CONTRATTUALE"),
("32-AFFIDAMENTO RISERVATO"),
("33-PROCEDURA NEGOZIATA PER AFFIDAMENTI SOTTO SOGLIA"),
("34-PROCEDURA ART.16 COMMA 2-BIS DPR 380/2001 PER OPERE URBANIZZAZIONE A SCOMPUTO PRIMARIE SOTTO SOGLIA COMUNITARIA"),
("35-PARTERNARIATO PER L’INNOVAZIONE"),
("36-AFFIDAMENTO DIRETTO PER LAVORI, SERVIZI O FORNITURE SUPPLEMENTARI"),
("37-PROCEDURA COMPETITIVA CON NEGOZIAZIONE"),
("38-PROCEDURA DISCIPLINATA DA REGOLAMENTO INTERNO PER SETTORI SPECIALI"),
("39-AFFIDAMENTO DIRETTO PER MODIFICHE CONTRATTUALI O VARIANTI PER LE QUALI È NECESSARIA UNA NUOVA PROCEDURA DI AFFIDAMENTO");

-- --------------------------------------------------------
--
-- Struttura della tabella `bloccati`
--
CREATE TABLE `bloccati` (
    `anno` int(11) NOT NULL,
    `avanzato` enum('s','n') NOT NULL DEFAULT 'n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- Struttura della tabella `versioni`
--
CREATE TABLE `avcp_versioni` ( 
            `numero` VARCHAR(10) NOT NULL COMMENT 'Numero della versione' , 
            `data` DATE NOT NULL COMMENT 'Data di aggiornamento', 
            PRIMARY KEY (`numero`)
        ) ENGINE = InnoDB COMMENT = 'Versioni del programma installate';

-- --------------------------------------------------------
--
-- Struttura per la vista `avcp_vista_ditte`
--
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
    `d`.`ragioneSociale`;

-- --------------------------------------------------------

--
-- Struttura per la vista `avcp_vista_gare`
--

CREATE VIEW `avcp_vista_gare` AS
SELECT
    `l`.`id` AS `id`,
    `l`.`cig` AS `cig`,
    `l`.`oggetto` AS `oggetto`,
    `l`.`sceltaContraente` AS `sceltaContraente`,
    `l`.`importoAggiudicazione` AS `importoAggiudicazione`,
    `l`.`importoSommeLiquidate` AS `importoSommeLiquidate`,
    (
    SELECT
        COUNT(0)
    FROM
        `avcp_ld` `ldl`
    WHERE
        (
            (`l`.`id` = `ldl`.`id`) AND(`ldl`.`funzione` = '01-PARTECIPANTE')
        )
) AS `partecipanti`,
(
SELECT
    COUNT(0)
FROM
    `avcp_ld` `ldl`
WHERE
    (`l`.`id` = `ldl`.`id`) AND(
        `ldl`.`funzione` = '02-AGGIUDICATARIO'
    )
) AS `aggiudicatari`,
`l`.`userins` AS `userins`,
GROUP_CONCAT(
    `ditta`.`ragioneSociale` SEPARATOR 'xxxxx'
) AS `nome_aggiudicatari`
FROM
    `avcp_lotto` `l`
LEFT JOIN
    `avcp_ld` `ld`
ON
    (`l`.`id` = `ld`.`id`) AND(
        `ld`.`funzione` = '02-AGGIUDICATARIO'
    )
LEFT JOIN
    `avcp_ditta` `ditta`
ON
    (
        `ld`.`codiceFiscale` = `ditta`.`codiceFiscale`
    )
GROUP BY
    `l`.`id`
ORDER BY
    `l`.`anno`,
    `l`.`id`;

-- --------------------------------------------------------

--
-- Struttura per la vista `avcp_xml_ditte`
--

CREATE VIEW `avcp_xml_ditte` AS
SELECT
    `avcp_ld`.`id` AS `id`,
    `avcp_ld`.`cig` AS `cig`,
    `avcp_ditta`.`codiceFiscale` AS `codiceFiscale`,
    `avcp_ditta`.`estero` AS `estero`,
    `avcp_ld`.`ruolo` AS `ruolo`,
    `avcp_ld`.`funzione` AS `funzione`,
    `avcp_ld`.`raggruppamento` AS `raggruppamento`,
    `avcp_ditta`.`ragioneSociale` AS `ragioneSociale`
FROM
    (`avcp_ld`
JOIN
    `avcp_ditta`)
WHERE
    (
        `avcp_ld`.`codiceFiscale` = `avcp_ditta`.`codiceFiscale`
    )
ORDER BY
    `avcp_ld`.`funzione`,
    `avcp_ld`.`raggruppamento`;

-- --------------------------------------------------------

--
-- Struttura per la vista `avcp_export_ods`
--

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
    `l`.`id`;
