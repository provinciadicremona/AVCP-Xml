<?php
switch ($_GET['do']){
    case 'cercaDitta':
        require_once 'app/model/dittaCerca.php';
        break;
    case 'elencaDitte':
        require_once 'app/model/ditteElenca.php';
        break;
    case 'eliminaDitta':
        require_once 'app/model/dittaElimina.php';
        break;
    case 'pulisciDitte':
        require_once 'app/model/dittePulisci.php';
        break;
    default:
        if (!empty($_POST['codiceFiscale'])) {
            $_GET['codiceFiscale'] = $_POST['codiceFiscale'];
        }
        require_once 'app/model/ditta.php';
        break;
}