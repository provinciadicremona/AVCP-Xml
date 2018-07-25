<?php
// Controller dei metodi riservati all'utente admin
switch ($_GET['do']){
case 'blocca':
    require_once __DIR__ . '/../model/annoBlocca.php';
    break;
case 'sblocca':
    require_once __DIR__ . '/../model/annoSblocca.php';
    break;
case 'avanza':
    require_once __DIR__ . '/../model/annoAvanza.php';
    break;
case 'arretra':
    require_once __DIR__ . '/../model/annoArretra.php';
    break;
case 'messaggio':
    require_once __DIR__ . '/../model/messaggio.php';
default:
    break;
}
if (isset($_GET['do']) && $_GET['do'] != 'messaggio') {
    require_once __DIR__ . '../view/annoOp.php';
}
