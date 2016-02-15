<?php
switch ($_GET['do']){
    case 'ditte':
        require_once 'app/model/ditteImporta.php';
        break;
    case 'gare':
        require_once 'app/model/gareImporta.php';
        break;
    default:
        break;
}