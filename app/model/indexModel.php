<?php
if (empty($_GET['do']) || $_GET['do'] == 'login') {
    // Determino la versione attuale del programma:
    $fh = fopen('version.txt', 'r');
    $localVersion = fread($fh, 1024);
    fclose($fh);
    $localVersion = str_replace('_', '.', $localVersion);
    
    // Seleziono gli anni presenti nel database dei lotti
    $queryAnno = "SELECT DISTINCT anno FROM avcp_lotto GROUP BY anno ORDER BY anno DESC";
    $resAnno = $db->query($queryAnno);
    $quanti = $resAnno->num_rows;
    $xmlLink = null;
    $xmlVal = null;
    $elencoGareLink = null;
    
    if ($quanti > 0) {
        for($x = 0; $x < $quanti; $x++) {
            $anno[$x] = $resAnno->fetch_assoc();
            // Preparo le icone di blocco e avanzamento da aggiungere a $elencoGareLink:
            $xmlBlock = null;
            if (array_key_exists($anno[$x]['anno'], $bloccati)) {
                if ($_SESSION['user'] == 'admin') {
                    $xmlBlock = ' - <a href="?mask=admin&amp;do=sblocca&amp;anno=' . $anno[$x]['anno'] . '" title="Sblocca anno">Sblocca</a>';
                } else {
                    $xmlBlock = ' - <i class="icon-lock"></i>';
                }
                if ($_SESSION['user'] == 'admin') {
                    if ($bloccati[$anno[$x]['anno']] == 'n') {
                        $xmlBlock .= ' - <a href="?mask=admin&amp;do=avanza&amp;anno=' . $anno[$x]['anno'] . '" title="Esegui avanzamento"><i class="icon-forward"></i></a>';
                    } else {
                        if (!array_key_exists(($anno[$x]['anno'] + 1), $bloccati))
                            $xmlBlock .= ' - <a href="?mask=admin&amp;do=arretra&amp;anno=' . $anno[$x]['anno'] . '" title="Esegui arretramento"><i class="icon-backward"></i></a>';
                    }
                }
            } elseif ($_SESSION['user'] == 'admin') {
                $xmlBlock = ' - <a href="?mask=admin&amp;do=blocca&amp;anno=' . $anno[$x]['anno'] . '" title="Blocca anno">Blocca</a>';
            }
            $xmlLink .= '<li><i class="icon-download-alt"></i>&nbsp;<a href="./crea_xml_avcp_query.php?anno=' . $anno[$x]['anno'] . '">Scarica file XML anno ' . $anno[$x]['anno'] . '</a></li>' . PHP_EOL;
            $xmlVal .= '<li><i class="icon-ok"></i>&nbsp;Valida ' . $anno[$x]['anno'] . ': <a href="./app/xml/valida_dataset.php?anno=' . $anno[$x]['anno'] . '">Semplice</a> - <a href="./app/xml/valida_dataset.php?anno=' . $anno[$x]['anno'] . '&amp;source=on">Con sorgente</a></li>' . PHP_EOL;
            $xmlEsp .= '<li><i class="icon-ok"></i>&nbsp;Anno ' . $anno[$x]['anno'] . ': <a href="./app/resume.php?anno=' . $anno[$x]['anno'] . '&amp;type=html">HTML</a> - <a href="./app/resume.php?anno=' . $anno[$x]['anno'] . '&amp;type=ods">ODS</a></li>' . PHP_EOL;
            $elencoGareLink .= '<li><i class="icon-filter"></i>&nbsp;<a href="?mask=gara&amp;do=elencaGareAnno&amp;anno=' . $anno[$x]['anno'] . '">Elenco gare anno ' . $anno[$x]['anno'] . '</a>' . $xmlBlock . '</li>' . PHP_EOL;
        }
        $resAnno->free();
    } else {
        $xmlLink = '<li>Inseresci almeno una gara</li>' . PHP_EOL;
    }
    require_once 'app/view/index.php';
} else {
    switch ($_GET['do']){
        case 'aggiorna':
            require_once 'app/webServices/isUpdated.php';
            require_once 'app/view/aggiornamenti.php';
            break;
        default:
            break;
    }
}