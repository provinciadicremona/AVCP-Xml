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
$messageFileName = './message.txt';
$okMess = false;
if (!is_writable($messageFileName)) {
    if (false === chmod($messageFileName, 666)) {
        echo "Il file messagge.txt non è modificabile.<br /> Contattare l'amministratore del sistema per modificare i permessi del file.";
    }
}
if (isset($_GET['action']) && $_GET['action'] == 'modifica') {
    if (is_writable($messageFileName)) {
        if ($fmh = fopen($messageFileName, 'w+')) {
            fwrite($fmh, $_POST['messaggio']);
            $okMess = true;
        }
        fclose($fmh);
    } else {
        echo "Il file messagge.txt non è modificabile.";
    }
}

// Leggo il file dei messaggi dell'amministratore
$messageFileName = './message.txt';
$fmh = fopen($messageFileName, 'rb');
$messageSize = filesize($messageFileName);
if ($messageSize > 0) {
    $contents = fread($fmh, $messageSize);
} else {
    $contents = '';
}
fclose($fmh);
require_once AVCP_DIR . 'app/view/messaggio.php';
