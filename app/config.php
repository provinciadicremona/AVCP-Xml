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

/**
 * --------------------------------------------------
 * INIZIO CONFIGURAZIONE
 * --------------------------------------------------
 */


/* ---------------------------
 CONFIGURAZIONE MYSQL
 Inserire i valori tra gli apici
--------------------------- */
/**
 * Indirizzo dell'host su sui risiede il db (di solito è 'localhost')
 */
define('DB_HOST', 'localhost');
/**
 * Nome del database usato dal programma
 */
define('DB_NAME', 'avcp');
/**
 *  Utente che si connette al db
 */
define('DB_USER', 'avcp');
/**
 * Password dell'utente che si connette al db
 */
define('DB_PASSWORD', 'password');



/* --------------------------------------------------------------
 UTENTI CHE ACCEDONO AL PROGRAMMA
 Il programma usa questo array per l'autenticazione degli utenti
 Sostituire i valori presenti. Se necessario aggiungerne altri.
 IMPORTANTE: rimuovere le linee non utilizzate e assicurarsi che
 quelle rimaste siano terminate da una virgola tranne l'ultima

 
 ATTENZIONE!!! Per sfruttare tutte le funzioni del programma
 è NECESSARIO CREARE UN UTENTE 'admin'.
-------------------------------------------------------------- */
$user = array(
	'admin' => 'password-admin',
	'utente-1' => 'password-1',
	'utente-2' => 'password-2',
	'utente-3' => 'password-3'
	);

/* --------------------------------------------------------------
 DATI DELL'ENTE CHE UTILIZZA IL PROGRAMMA
-------------------------------------------------------------- */
/**
 * Attenzione ai caratteri di escape per gli apstrofi!!!
 */
define('ENTE_PROPONENTE', 'Nome Ente');
define('CF_PROPONENTE', '99999999999');

/**
 * Licenza di utilizzo dei dati preimpostata a IODL 2.0
 */
define('LICENZA', 'IODL 2.0');

/**
 *
 * LEGGERE ATTENTAMENTE QUESTE ISTRUZIONI:
 *
 * IMPOSTARE L'INDIRIZZO INTERNET COMUNICATO ALL'AVCP PER SCARICARE IL FILE.
 *
 * IMPORTANTE! Il programma è fatto per generare file diversi per ogni anno
 * utilizzando la costante URL_XML_FILE_ANNUALE e aggiungendovi alla fine
 * l'anno di riferimento e l'estensione .xml
 *
 * Ad es. impostando URL_XML_FILE_ANNUALE col valore 'http://ente.it/trasp_'
 * per l'anno 2012, si genererà un file con indirizzo: 'http://ente.it/trasp_2012.xml'
 *
 * Nel caso si preferisse fare diversamente, è possibile specificare l'indirizzo
 * completo del file nella costante URL_XML_FILE.
 * Ad es. http://ente.it/trasparenza.xml
 * 
 * È stato aggiunto il segnaposto {{anno}} per stabilire la posizione dell'anno 
 * all'interno dell'URL da comunicare all'ANAC
 *
 * E' PREFERIBILE L'USO DELL'URL_XML_FILE_ANNUALE
 *
 *----------------
 * ATTENZIONE!!!!
 * ---------------
 * Se si desidera usare l'indirizzo completo è NECESSARIO imostare il valore di
 * URL_XML_FILE_ANNUALE a 'NO' altrimenti URL_XML_FILE non sarà considerato.
 *
 * In ogni caso è opportuno verificare la correttezza del valore di <urlFile> nei metadata
 * del file xml generato .
 *
 */

// Uso il file xml annuale:
define('URL_XML_FILE_ANNUALE', 'http://www.sito.ente.it/trasparenza/avcp_dataset_{{anno}}');
// oppure commentare la linea precedente e decommentare le due linee successive:
// define('URL_XML_FILE_ANNUALE', 'NO');
// define('URL_XML_FILE', 'http://www.sito.ente.it/trasparenza/avcp_dataset.xml');

/**
 * --------------------------------------------------
 * FINE CONFIGURAZIONE
 * --------------------------------------------------
 */

/**
 * --------------------------------------------------
 * NON MODIFICARE NIENTE AL DI SOTTO DI QUESTA LINEA!
 * --------------------------------------------------
 */

require_once 'connection.php';
