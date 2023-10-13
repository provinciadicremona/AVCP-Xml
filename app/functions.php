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

/**
 *
 * @param string $importo        
 * @return string
 */
function stampaValuta($val) {
    if (php_uname('s') === 'Linux' && PHP_MAJOR_VERSION < 8 ) {
            echo strtr(money_format('%.2n', $val), 'EUR', '€');
    } else {
        echo '€ '.number_format($val, 2, ',', '.');
    }
}

/**
 *
 * @param string $c_f        
 * @return boolean
 */
function controllaCF($c_f) {
    if (strlen($c_f) == 0) {
        // Errore, il campo è vuoto -> sbagliato
        return false;
    }
    if (preg_match("/^[0-9]{11}$/", $c_f)) {
        // Il codice fiscale inserito è relativo ad una persona giuridica -> corretto;
        return true;
    }
    if (!preg_match("/^[a-zA-Z]{6}[0-9]{2}[a-zA-Z]{1}[0-9]{2}[a-zA-Z]{1}[0-9]{3}[a-zA-Z]{1}$/", $c_f) || 
        !preg_match("/^[A-Za-z]{6}[0-9LMNPQRSTUV]{2}[A-Za-z]{1}[0-9LMNPQRSTUV]{2}[A-Za-z]{1}[0-9LMNPQRSTUV]{3}[A-Za-z]{1}$/", $c_f)) {
        // Errore, il codice fiscale contiene caratteri non validi
        // o non è della lunghezza esatta -> sbagliato
        return false;
    }

    //----------------------------------------------------------------------
    // Il CF è di una persona fisica e procedo a controllarne la correttezza
    //----------------------------------------------------------------------
    
    // $sumToCF è una tabella di conversione per determinare il valore
    // da aggiungere a $s per il codice di controllo
    $sumToCF = array (
            '0' => 1, '1' => 0, '2' => 5, '3' => 7, '4' => 9, '5' => 13,
            '6' => 15, '7' => 17, '8' => 19, '9' => 21, 'A' => 1, 'B' => 0,
            'C' => 5, 'D' => 7, 'E' => 9, 'F' => 13, 'G' => 15, 'H' => 17,
            'I' => 19, 'J' => 21, 'K' => 2, 'L' => 4, 'M' => 18, 'N' => 20,
            'O' => 11, 'P' => 3, 'Q' => 6, 'R' => 8, 'S' => 12, 'T' => 14,
            'U' => 16, 'V' => 10, 'W' => 22, 'X' => 25, 'Y' => 24, 'Z' => 23 
    );
    // Il CF deve essere in lettere maiuscole o il controllo fallisce
    $c_f = strtoupper($c_f);
    
    $s = 0;
    for($i = 1; $i <= 13; $i += 2) {
        $c = $c_f[$i];
        if ('0' <= $c && $c <= '9') {
            $s += ord($c) - ord('0');
        } else {
            $s += ord($c) - ord('A');
        }
    }
    for($i = 0; $i <= 14; $i += 2) {
        $c = $c_f[$i];
        $s += $sumToCF[$c];
    }
    if (chr($s % 26 + ord('A')) != $c_f[15]) {
        // Errore, il codice fiscale inserito non è formalmente corretto in quanto
        // il codice di controllo non corrisponde -> sbagliato
        return false;
    }
    // Altrimenti OK -> corretto
    return true;
}
