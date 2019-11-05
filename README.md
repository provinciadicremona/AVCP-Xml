# AVCP-Xml - Repository di sviluppo
Generatore di dataset XML per l'Autorità per la Vigilanza sui Contratti Pubblici - art.32 L. 190/2012

## Upgrade dalla versione 0.7.1
Per aggiornare il programma, [scaricare la release più recente](https://github.com/provinciadicremona/AVCP-Xml/releases/latest) e seguire le istruzioni che trovate in quella pagina.

## API per l'ultima versione disponibile
`https://api.github.com/repos/provinciadicremona/AVCP-Xml/releases/latest`

### Aggiunta campo a tabella lotti

```sql
ALTER TABLE `avcp_lotto` ADD `chiuso` BOOLEAN NOT NULL DEFAULT FALSE AFTER `flag`;
```

Aggiungo il campo `chiuso` per permettere all'utente di segnalare come conclusi i lotti per cui non viene pagata l'intera somma aggiudicata.

### Aggiunta vista per esportazione ods dei lotti utente

```sql
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
    `l`.`id`;

```

## Aggiungere le nuove modalità di scelta del contraente
Il 21 gennaio 2019, l'ANAC ha [modificato le modalità di scelta del contraente]( http://www.anticorruzione.it/portal/public/classic/Comunicazione/News/_news?id=7102507a0a778042193e31c2da0782b7) introducendo 3 nuove tipologie.

Indipendentemente dalla versione del programma utilizzata, è possibile aggiungerle seguendo la procedura descritta di seguito.

Aprite il vostro gestore di database, solitamente phpMyAdmin, selezionare il database avcp e nella tab 'SQL' lanciate le seguenti query:

```sql

DROP TABLE IF EXISTS `avcp_sceltaContraenteType`;

CREATE TABLE `avcp_sceltaContraenteType` (
  `ruolo` varchar(255) NOT NULL COMMENT 'tipo scelta contraente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tipo scelta contraente';


INSERT INTO `avcp_sceltaContraenteType` (`ruolo`) VALUES
('01-PROCEDURA APERTA'),
('02-PROCEDURA RISTRETTA'),
('03-PROCEDURA NEGOZIATA PREVIA PUBBLICAZIONE DEL BANDO'),
('04-PROCEDURA NEGOZIATA SENZA PREVIA PUBBLICAZIONE DEL BANDO'),
('05-DIALOGO COMPETITIVO'),
('06-PROCEDURA NEGOZIATA SENZA PREVIA INDIZIONE DI GARA ART. 221 D.LGS. 163/2006'),
('07-SISTEMA DINAMICO DI ACQUISIZIONE'),
('08-AFFIDAMENTO IN ECONOMIA - COTTIMO FIDUCIARIO'),
('14-PROCEDURA SELETTIVA EX ART 238 C.7, D.LGS. 163/2006'),
('17-AFFIDAMENTO DIRETTO EX ART. 5 DELLA LEGGE N.381/91'),
('21-PROCEDURA RISTRETTA DERIVANTE DA AVVISI CON CUI SI INDICE LA GARA'),
('22-PROCEDURA NEGOZIATA DERIVANTE DA AVVISI CON CUI SI INDICE LA GARA'),
('23-AFFIDAMENTO IN ECONOMIA - AFFIDAMENTO DIRETTO'),
('24-AFFIDAMENTO DIRETTO A SOCIETA\' IN HOUSE'),
('25-AFFIDAMENTO DIRETTO A SOCIETA\' RAGGRUPPATE/CONSORZIATE O CONTROLLATE NELLE CONCESSIONI DI LL.PP'),
('26-AFFIDAMENTO DIRETTO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE'),
('27-CONFRONTO COMPETITIVO IN ADESIONE AD ACCORDO QUADRO/CONVENZIONE'),
('28-PROCEDURA AI SENSI DEI REGOLAMENTI DEGLI ORGANI COSTITUZIONALI'),
('29-PROCEDURA RISTRETTA SEMPLIFICATA'),
('30-PROCEDURA DERIVANTE DA LEGGE REGIONALE'),
('31-AFFIDAMENTO DIRETTO PER VARIANTE SUPERIORE AL 20% DELL''IMPORTO CONTRATTUALE');

ALTER TABLE `avcp_sceltaContraenteType`
  ADD UNIQUE KEY `ruolo` (`ruolo`);

```
Una volta fatto questo, sostituite il file `app/xml/TypesL190.xsd` con [quello nuovo fornito da ANAC](https://raw.githubusercontent.com/provinciadicremona/AVCP-Xml/master/app/xml/TypesL190.xsd)
