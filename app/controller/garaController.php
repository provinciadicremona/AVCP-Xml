<?php
// Controller che gestisce i metodi che agiscono sui lotti
isset($_GET['do']) || $_GET['do'] = null;
switch ($_GET['do']){
case 'cercaGara':
    require_once __DIR__ . '/../model/garaCerca.php';
    break;
case 'partecipantiGara':
    require_once __DIR__ . '/../model/garaPartecipanti.php';
    break;
case 'aggiungiDitta':
    require_once __DIR__ . '/../model/garaPartecipanti.php';
    break;
case 'eliminaDitta':
    require_once __DIR__ . '/../model/garaPartecipanti.php';
    break;
case 'eliminaAggiudicatari':
    require_once __DIR__ . '/../model/garaPartecipanti.php';
    break;
case 'eliminaGara':
    require_once __DIR__ . '/../model/garaElimina.php';
    break;
case 'elencaGareAnno':
    require_once __DIR__ . '/../model/garaElenca.php';
    break;
default:
    require_once __DIR__ . '/../model/gara.php';
    break;
}
