<?php
/**
 * --------------------------------------------------
 * NUOVA CONNESSIONE CON MYSQLI
 * --------------------------------------------------
 */
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($db->connect_errno) {
    echo "Connessione a MySQL fallita: (Error " . $db->connect_errno . ") " . $db->connect_error;
}

// Opzioni per filtro anni in INPUT da usare con filter_input
$anniValidi = array (
        'options' => array (
                'min_range' => 2012,
                'max_range' => (int) (date('Y') + 1)
        )
);


// Estraggo l'elenco degli anni bloccati e relativo stato di avanzamento:
$queryBloccati = "SELECT anno, avanzato FROM bloccati ORDER BY anno ASC";
$resBloccati = $db->query($queryBloccati);
$quantiBloccati = $resBloccati->num_rows;
if ($quantiBloccati > 0) {
    for($x = 0; $x < $quantiBloccati; $x++) {
        $tmp = $resBloccati->fetch_assoc();
        $bloccati[$tmp['anno']] = $tmp['avanzato'];
    }
} else {
    // Estraggo la lista degli anni bloccati e avanzati al successivo
    $bloccati = array (
            '0000' => 'n'
    );    
}
