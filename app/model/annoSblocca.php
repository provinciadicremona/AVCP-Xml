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
$anno = (int) $_GET['anno'];
$messOk = false;
// var_dump($bloccati);
if ($_SESSION['user'] == 'admin' && array_key_exists($anno, $bloccati)) {
    $querySblocco = "DELETE FROM bloccati WHERE anno = '" . $anno . "'";
    $resSblocco = $db->query($querySblocco);
    if ($db->affected_rows == 1) {
        $messOk = true;
        $message = '<strong>Tutto ok:</strong> Anno ' . $anno . ' sbloccato';
    }
} else {
    $message = '<strong>Errore:</strong> non posso sbloccare l\'anno ' . $anno . '!';
}
