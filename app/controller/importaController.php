<?php
// Controller che gestisce i metodi di importazione di lotti e ditte
switch ($_GET['do']){
case 'ditte':
    require_once __DIR__ . '/../model/ditteImporta.php';
    break;
case 'gare':
    require_once __DIR__ . '/../model/gareImporta.php';
    break;
default:
    break;
}
