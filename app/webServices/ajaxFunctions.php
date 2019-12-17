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
// Funzione che converte un array in formato json
// Utilizzata solo per vecchie versioni di PHP
function array_to_json($array) {
    if (!is_array($array)) {
        return false;
    }
    // Testo se la'array è associativo. Se 0 no, altrimenti sì
    $associative = count(array_diff(array_keys($array), array_keys(array_keys($array))));
    if ($associative) {
        $construct = array ();
        foreach ($array as $key => $value) {
            // We first copy each key/value pair into a staging array,
            // formatting each key and value properly as we go.
            // Format the key:
            if (is_numeric($key)) {
                $key = "key_$key";
            }
            $key = '"' . addslashes($key) . '"';
            // Format the value:
            if (is_array($value)) {
                $value = array_to_json($value);
            } else if (!is_numeric($value) || is_string($value)) {
                $value = '"' . addslashes($value) . '"';
            }
            // Add to staging array:
            $construct[] = "$key:$value";
        }
        // Then we collapse the staging array into the JSON form:
        $result = "{" . implode(",", $construct) . "}";
    } else { // If the array is a vector (not associative):
        $construct = array ();
        foreach ($array as $value) {
            // Format the value:
            if (is_array($value)) {
                $value = array_to_json($value);
            } else if (!is_numeric($value) || is_string($value)) {
                $value = '"' . addslashes($value) . '"';
            }
            // Add to staging array:
            $construct[] = $value;
        }
        // Then we collapse the staging array into the JSON form:
        $result = "[" . implode(",", $construct) . "]";
    }
    return $result;
}
