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

// possibili anni compresi tra l'introduzione della rilevazione (2012)
// e quello corrente
$anni = range(2012, (int) date('Y'));

// Estraggo l'elenco degli anni bloccati e relativo stato di avanzamento:
$queryBloccati = "SELECT anno, avanzato FROM bloccati ORDER BY anno ASC";
$resBloccati = $db->query($queryBloccati);
$quantiBloccati = $resBloccati->num_rows;

// NB: $bloccati serve per funzioni admin (avanza, arretra, blocca, sblocca)  
$bloccati = array ('0000' => 'n');
// $anniBloccati viene sottratto a $anni per stabilire in quali annualità
// è ancora possibile effettuare modifiche/inserimenti/cancellazioni
$anniBloc = array ();
if ($quantiBloccati > 0) {
    for($x = 0; $x < $quantiBloccati; $x++) {
        $tmp = $resBloccati->fetch_assoc();
        $anniBloc[] = $tmp['anno'];
        $bloccati[$tmp['anno']] = $tmp['avanzato'];
    }
}
// NB: $anniValidi contiene gli anni su cui è ancora possibile operare
$anniValidi = array_diff($anni, $anniBloc);
unset($anniBloc);
