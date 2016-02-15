<?php
/**
 *
 * APVC Xml - Generatore dataset per art. 32 L. 190/2012
 * Copyright (C) 2013  Claudio Roncaglio e Gianni Bassini
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * To contact the authors send an email to <sito@provincia.cremona.it>
 *
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
define('DB_HOST', '');
/**
 * Nome del database usato dal programma
 */
define('DB_NAME', '');
/**
 *  Utente che si connette al db
 */
define('DB_USER', '');
/**
 * Password dell'utente che si connette al db
 */
define('DB_PASSWORD', '');



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
	'utente-1' => 'password-utente-1',
	'utente-2' => 'password-utente-2',
	'utente-3' => 'password-utente-3'
	);

/* --------------------------------------------------------------
 DATI DELL'ENTE CHE UTILIZZA IL PROGRAMMA
-------------------------------------------------------------- */
/**
 * Attenzione ai caratteri di escape per gli apstrofi!!!
 */
define('ENTE_PROPONENTE', 'Inserire usando qui il nome dell\'ente proponente');
define('CF_PROPONENTE', 'Inserire qui il codice fiscale ente');

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
define('URL_XML_FILE_ANNUALE', 'http://www.sito.ente.it/trasparenza/avcp_dataset_');
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
