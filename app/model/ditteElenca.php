<?php
$query = "SELECT * FROM avcp_vista_ditte ORDER BY ragioneSociale";
$res = $db->query($query);
$quante = $db->affected_rows;
$outElenco = null;
$contraente = null;

for($x = 0; $x < $quante; $x++) {
    $ditta = $res->fetch_assoc();
    $lotti = null;
    foreach ($ditta as $key => $value) {
        $ditte[$x][$key] = htmlspecialchars(stripslashes(trim($value)));
    }
    if ($ditte[$x]['estero'] == 1) {
        $ditte[$x]['estero'] = 'Estero';
    } else {
        $ditte[$x]['estero'] = 'Italia';
    }
    if ($ditte[$x]['partecipa'] > 0) {
        $ditte[$x]['elimina'] = '<i class="icon-ban-circle" title="Partecipa a gare"></i>';
        
        $lotti = explode('xxxxx', $ditte[$x]['id_aggiudicatari']);
        $sLotti = array_unique($lotti);
        $ditte[$x]['lotti'] = null;
        $conta = 1;
        foreach ($sLotti as $l) {
            $ditte[$x]['lotti'] .= '<a href="?mask=gara&amp;do=partecipantiGara&amp;id=' . $l . '" title="Vedi gara"> ' . $conta++ . '</a>' . '&nbsp;';
        }
    } else {
        $ditte[$x]['elimina'] = '<a href="?mask=ditta&amp;do=eliminaDitta&amp;eleDitta=' . $ditte[$x]['codiceFiscale'] . '" title="Elimina ditta"><i class="icon-trash"></a></i>';
    }
}
$res->free();
require_once 'app/view/ditteElenca.php';