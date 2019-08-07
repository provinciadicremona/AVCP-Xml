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
?>

<?php
$outVersion = null;
// determino la versione attuale:
$fh = fopen('version.txt', 'r');
$localVersion = fread($fh, 1024);
fclose($fh);

// crea una risorsa cURL
$ch = curl_init();

// imposto l'URL da interrogare
curl_setopt($ch, CURLOPT_URL, 'http://www.provincia.cremona.it/avcp-repo/getVersion.php');

// escludo l'header dall'output
curl_setopt($ch, CURLOPT_HEADER, 0);

// Imposto un massimo di 5 secondi prima di interrompere l'operazione
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

// Sopprimo l'output sul browser
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// recupero l'URL e lo assegno in variabile
$currentVersion = curl_exec($ch);

// chiudo la risorsa cURL
curl_close($ch);
// Se hai una vecchia versione
if ($currentVersion > $localVersion) {
    // Comunico che esiste una nuova versione
    $currentVersion = str_replace('_', '.', $currentVersion);
    $outVersion .= '
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Una nuova versione di AVCP Xml presente: ' . $currentVersion . '</strong><br />
        <a href="http://www.provincia.cremona.it/urp/?view=Pagina&amp;id=5354"><i class="icon-download-alt"></i>&nbsp;Scaricala dal sito della Provincia di Cremona</a>
        </div>' . PHP_EOL;

    // Recupero le note di rilascio

    // crea una risorsa cURL
    $ch = curl_init();

    // imposto l'URL da interrogare
    curl_setopt($ch, CURLOPT_URL, 'http://www.provincia.cremona.it/avcp-repo/note');

    // escludo l'header dall'output
    curl_setopt($ch, CURLOPT_HEADER, 0);

    // Imposto un massimo di 5 secondi prima di interrompere l'operazione
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    // Sopprimo l'output sul browser
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // recupero l'URL e lo assegno in variabile
    $noteRilascio = curl_exec($ch);
    // Comunico le note di rilascio
    $outVersion .= $noteRilascio;
} else {
    // Comunico che va tutto bene così
    $outVersion .= '<p><strong>Questa versione di AVCP Xml è aggiornata</strong><br />' . PHP_EOL;
    $outVersion .= '<a href="./">Torna all\'homepage</a></p>' . PHP_EOL;
}

?>
