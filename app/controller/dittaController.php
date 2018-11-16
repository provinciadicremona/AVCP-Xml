<?php
// Controller dei metodi che agiscono sulle ditte
isset($_GET['do']) || $_GET['do'] = null;
switch ($_GET['do']){
case 'cercaDitta':
    require_once __DIR__ . '/../model/dittaCerca.php';
    break;
case 'elencaDitte':
    require_once __DIR__ . '/../model/ditteElenca.php';
    break;
case 'dittaGare':
    require_once __DIR__ . '/../model/dittaGare.php';
    break;
case 'eliminaDitta':
    require_once __DIR__ . '/../model/dittaElimina.php';
    break;
case 'pulisciDitte':
    require_once __DIR__ . '/../model/dittePulisci.php';
    break;
default:
    if (!empty($_POST['codiceFiscale'])) {
        $_GET['codiceFiscale'] = $_POST['codiceFiscale'];
    }
    require_once __DIR__ . '/../model/ditta.php';
    break;
}
