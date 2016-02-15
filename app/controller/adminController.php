<?php
switch ($_GET['do']){
    case 'blocca':
        require_once 'app/model/annoBlocca.php';
        break;
    case 'sblocca':
        require_once 'app/model/annoSblocca.php';
        break;
    case 'avanza':
        require_once 'app/model/annoAvanza.php';
        break;
    case 'arretra':
        require_once 'app/model/annoArretra.php';
        break;
    case 'messaggio':
        require_once 'app/model/messaggio.php';
    default:
        break;
}
if ($_GET['do'] != 'messaggio') {
    require_once 'app/view/annoOp.php';
}