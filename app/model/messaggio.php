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
