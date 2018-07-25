<?php
// Controller dei metodi che agiscono sulle ditte
isset($_GET['do']) || $_GET['do'] = null;
switch ($_GET['do']){
case 'cercaDitta':
    require_once '../model/dittaCerca.php';
    break;
case 'elencaDitte':
    require_once '../model/ditteElenca.php';
    break;
case 'eliminaDitta':
    require_once '../model/dittaElimina.php';
    break;
case 'pulisciDitte':
    require_once '../model/dittePulisci.php';
    break;
default:
    if (!empty($_POST['codiceFiscale'])) {
        $_GET['codiceFiscale'] = $_POST['codiceFiscale'];
    }
    require_once '../model/ditta.php';
    break;
}
