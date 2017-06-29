<?php
// Controller che gestisce i metodi che agiscono sui lotti
switch ($_GET['do']){
case 'cercaGara':
    require_once 'app/model/garaCerca.php';
    break;
case 'partecipantiGara':
    require_once 'app/model/garaPartecipanti.php';
    break;
case 'aggiungiDitta':
    require_once 'app/model/garaPartecipanti.php';
    break;
case 'eliminaDitta':
    require_once 'app/model/garaPartecipanti.php';
    break;
case 'eliminaAggiudicatari':
    require_once 'app/model/garaPartecipanti.php';
    break;
case 'eliminaGara':
    require_once 'app/model/garaElimina.php';
    break;
case 'elencaGareAnno':
    require_once 'app/model/garaElenca.php';
    break;
default:
    require_once 'app/model/gara.php';
    break;
}
