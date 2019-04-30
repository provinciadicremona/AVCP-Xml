# AVCP-Xml - Repository di sviluppo
Generatore di dataset XML per l'Autorità per la Vigilanza sui Contratti Pubblici - art. 1 comma 32 L. 190/2012

**Attenzione**, non scaricate il programma da questa pagina, è una versione di sviluppo e **potrebbe non funzionare correttamente**: fate affidamento solo sulla release più recente del programma che trovate linkata di seguito.

## Upgrade dalla versione 0.7.1
Per aggiornare il programma, [scaricare la release più recente](https://github.com/provinciadicremona/AVCP-Xml/releases/latest) e seguire le istruzioni riportate di seguito.

## API per l'ultima versione disponibile
`https://api.github.com/repos/provinciadicremona/AVCP-Xml/releases/latest`

## Aggiungere le nuove modalità di scelta del contraente
Indipendentemente dalla versione del programma che state utilizzando, è possibile aggiungere le nuove modalità di scelta del contraente individuate da ANAC per l'anno 2019.

È sufficiente aprire il vostro gestore di database, solitamente phpMyAdmin, selezionare il database avcp e nella tab 'SQL' lanciare le seguenti query:

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
