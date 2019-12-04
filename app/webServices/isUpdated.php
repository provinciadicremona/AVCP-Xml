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

$outVersion = null;
// determino la versione attuale:
$fh = fopen('version.txt', 'r');
$local= fread($fh, 1024);
fclose($fh);

/* 
 * Converto il numero di versione per essere compatibile con 
 * quello usato su github:
 *  - In locale: 0_0_0
 *  - Su github: v0.0.0
 */
$localVersion = "v".(strtr($local, '_', '.'));

// crea una risorsa cURL
$ch = curl_init();
// Imposto come URL da interrogare quello dell'API di github per l'ultima release
curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/repos/provinciadicremona/AVCP-Xml/releases/latest');
// escludo l'header dall'output
curl_setopt($ch, CURLOPT_HEADER, 0);
// Imposto lo useragent col nome del repository altrimenti github nega la risposta
curl_setopt($ch, CURLOPT_USERAGENT, 'AVCP-Xml');
// Imposto un massimo di 5 secondi prima di interrompere l'operazione
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
// Sopprimo l'output sul browser
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Eseguo la chiamata e memorizzo la risposta (che è in JSON)
$rispostaGithub = curl_exec($ch);
// Converto il JSON in un array
$githubData = json_decode($rispostaGithub, true); // true per avere un array e non un oggetto
$currentVersion =  $githubData['tag_name'];
$releaseTitle = $githubData['name'];
$downloadZip = '<a href="'.$githubData['zipball_url'].'">Scarica in formato zip</a>';
$downloadTarball = '<a href="'.$githubData['tarball_url'].'">Scarica in formato tar.gz</a>';
// chiudo la risorsa cURL
curl_close($ch);
// Se hai una vecchia versione
if ($currentVersion > $localVersion) {
    // Comunico che esiste una nuova versione
    $outVersion .= '
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Una nuova versione di AVCP Xml presente: ' . $currentVersion . '</strong><br />
    </div>' . PHP_EOL;
    $outVersion .= "<h2>".$releaseTitle."</h2>".PHP_EOL;
    // Per convertire il markdown delle note di release in html
    // uso parsedown https://github.com/erusev/parsedown
    require_once 'Parsedown.php';
    $Parsedown = new Parsedown();
    $noteRilascio = $Parsedown->text($githubData['body']);
    $outVersion .= $noteRilascio;
    $outVersion .= '
    <ul>
        <li>'.$downloadZip.'</li>
        <li>'.$downloadTarball.'</li>
    </ul>';
} else {
    // Comunico che va tutto bene così
    $outVersion .= '<p><strong>Questa versione di AVCP Xml è aggiornata</strong><br />' . PHP_EOL;
    $outVersion .= '<a href="./">Torna all\'homepage</a></p>' . PHP_EOL;
}
echo $outVersion;
