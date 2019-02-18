<?php
// Controller che gestisce i metodi che agiscono sui lotti
isset($_GET['do']) || $_GET['do'] = null;
switch ($_GET['do']){
case 'cercaGara':
    require_once AVCP_DIR . 'app/model/garaCerca.php';
    break;
case 'partecipantiGara':
    require_once AVCP_DIR . 'app/model/garaPartecipanti.php';
    break;
case 'aggiungiDitta':
    require_once AVCP_DIR . 'app/model/garaPartecipanti.php';
    break;
case 'eliminaDitta':
    require_once AVCP_DIR . 'app/model/garaPartecipanti.php';
    break;
case 'eliminaAggiudicatari':
    require_once AVCP_DIR . 'app/model/garaPartecipanti.php';
    break;
case 'eliminaGara':
    require_once AVCP_DIR . 'app/model/garaElimina.php';
    break;
case 'elencaGareAnno':
    require_once AVCP_DIR . 'app/model/garaElenca.php';
    break;
default:
    require_once AVCP_DIR . 'app/model/gara.php';
    break;
}
