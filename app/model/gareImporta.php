<?php
if (is_null($_POST['action'])) {
    // Se non sto inserendo chiamo la maschera e le istruzioni
    require_once 'app/view/gareImportaIstr.php';
} else {
    // Controllo che ci sia il file e che sia un csv
    if ($_FILES['csv']['error'] != 0) {
        require_once 'app/view/gareImportaError.php';
    } else {
        if (($fh = fopen($_FILES['csv']['tmp_name'], 'r')) !== false) {
            // in $fn metto i nomi delle colonne che sono la prima riga del csv
            $fn = fgetcsv($fh, 600, ',', '"');
            $n = 0;
            $numErrori = 0;
            $query = "INSERT IGNORE INTO avcp_lotto
			(`anno`,
			`cig`,
			`numAtto`,
			`codiceFiscaleProp`,
			`denominazione` ,
			`oggetto`,
			`sceltaContraente`,
			`dataInizio`,
			`dataUltimazione`,
			`importoAggiudicazione`,
			`importoSommeLiquidate`,
			`userins`) VALUES " . PHP_EOL;
            while ($row = fgetcsv($fh, 1024, ',', '"')) {
                foreach ($row as $key => $value) {
                    $rows[$n][$fn[$key]] = $db->real_escape_string(trim($value));
                }
                if (empty($rows[$n]['anno']) || $rows[$n]['anno'] < 2012 || empty($rows[$n]['oggetto'])) {
                    $numErrori++;
                    continue;
                }
                $rows[$n]['importoAggiudicazione'] = str_replace(',', '.', $rows[$n]['importoAggiudicazione']);
                $rows[$n]['importoSommeLiquidate'] = str_replace(',', '.', $rows[$n]['importoSommeLiquidate']);
                if (empty($rows[$n]['sceltaContraente'])) {
                    $rows[$n]['sceltaContraente'] = '00-DA DEFINIRE';
                }
                if (empty($rows[$n]['cig'])) {
                    $rows[$n]['cig'] = '0000000000';
                }
                foreach ($rows[$n] as $key => $value) {
                    if (empty($rows[$n][$key])) {
                        $rowQ[$n][$key] = 'NULL';
                    } else {
                        $rowQ[$n][$key] = "'" . $value . "'";
                    }
                }
                
                if ($n > 0) {
                    $query .= ", " . PHP_EOL;
                }
                $query .= "(
					" . $rowQ[$n]['anno'] . ",
					" . $rowQ[$n]['cig'] . ",
					" . $rowQ[$n]['numAtto'] . ",
					'" . CF_PROPONENTE . "',
					'" . $db->real_escape_string(ENTE_PROPONENTE) . "',
					" . $rowQ[$n]['oggetto'] . ",
					" . $rowQ[$n]['sceltaContraente'] . ",
					" . $rowQ[$n]['dataInizio'] . ",
					" . $rowQ[$n]['dataUltimazione'] . ",
					" . $rowQ[$n]['importoAggiudicazione'] . ",
					" . $rowQ[$n]['importoSommeLiquidate'] . ",
					'" . $_SESSION['user'] . "'
				)";
                $n++;
            }
            fclose($fh);
            $res = $db->query($query);
            $numGareFile = $n + $numErrori;
            $numOk = $db->affected_rows;
            $riepilogo = $db->info;
            $riepilogo = str_replace('Records', '<p><span class="label label-success">Gare esaminate</span>', $riepilogo);
            $riepilogo = str_replace('Duplicates', '</p><p><span class="label label-warning">Tentativi di inserimenti doppi</span>', $riepilogo);
            $riepilogo = str_replace('Warnings:', '</p><p><span class="label label-warning">Errori corretti durante l\'imortazione:</span>', $riepilogo);
            $riepilogo .= '</p>';
            require_once 'app/view/gareImporta.php';
        } else {
            echo "File non leggibile...";
        }
    }
}
