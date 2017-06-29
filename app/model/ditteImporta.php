<?php
if (is_null($_POST['action'])) {
    // Se non sto inserendo chiamo la maschera e le istruzioni
    require_once 'app/view/ditteImportaIstr.php';
} else {
    // Controllo che ci sia il file
    if ($_FILES['csv']['error'] != 0) {
        require_once 'app/view/ditteImportaError.php';
    } else {
        if (($fh = fopen($_FILES['csv']['tmp_name'], 'r')) !== false) {
            require_once 'app/functions.php';
            // in $fn metto i nomi delle colonne che sono la prima riga del csv
            $fn = fgetcsv($fh, 600, ',', '"');
            $n = 0;
            $numErrori = 0;
            $logImport = "LOG errori di importazione...\n";
            $query = "INSERT IGNORE INTO avcp_ditta
                (`codiceFiscale`, `ragioneSociale`, `estero`) VALUES " . PHP_EOL;
            while ($row = fgetcsv($fh, 1024, ',', '"')) {

                foreach ($row as $key => $value) {
                    $rows[$n][$fn[$key]] = $db->real_escape_string(trim($value));
                }
                if (!controllaCF($rows[$n]['codiceFiscale'])) {
                    if ($rows[$n]['estero'] != 1) {
                        $numErrori++;
                        $logImport .= "Codice fiscale errato per: " . implode(",", $rows[$n]) . "\n";
                        continue;
                    }
                }

                if (empty($rows[$n]['ragioneSociale'])) {
                    $numErrori++;
                    $logImport .= "Ragione Sociale mancante per: " . implode(",", $rows[$n]) . "\n";
                    continue;
                }
                if (empty($rows[$n]['estero'])) {
                    $rows[$n]['estero'] = '0';
                }

                foreach ($rows[$n] as $key => $value) {
                    if (empty($rows[$n][$key])) {
                        if ($key == 'estero') {
                            $rowQ[$n][$key] = 0;
                        } else {
                            $rowQ[$n][$key] = 'NULL';
                        }
                    } else {
                        $rowQ[$n][$key] = "'" . $value . "'";
                    }
                }

                if ($n > 0) {
                    $query .= ", " . PHP_EOL;
                }
                $query .= "(" . $rowQ[$n]['codiceFiscale'] . ", " . $rowQ[$n]['ragioneSociale'] . ", " . $rowQ[$n]['estero'] . ")";
                $n++;
            }
            fclose($fh);

            $res = $db->query($query);
            $numGareFile = $n + $numErrori;
            $numOk = $db->affected_rows;
            $riepilogo = $db->info;
            $res->free;
            $riepilogo = str_replace('Records', '<p><span class="label label-success">Ditte esaminate</span>', $riepilogo);
            $riepilogo = str_replace('Duplicates', '</p><p><span class="label label-warning">Tentativi di inserimenti doppi</span>', $riepilogo);
            $riepilogo = str_replace('Warnings:', '</p><p><span class="label label-warning">Errori corretti durante l\'imortazione:</span>', $riepilogo);
            $riepilogo .= '</p>';
            require_once 'app/view/ditteImporta.php';
        } else {
            echo "File non leggibile...";
        }
    }
}
