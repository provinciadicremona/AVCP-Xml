<?php
/*
 * This file is part of project AVCP-Xml that can be found at:
 * https://github.com/provinciadicremona/AVCP-Xml
 * 
 * © 2013 Claudio Roncaglio <claudio.roncaglio@provincia.cremona.it>
 * © 2013 Gianni Bassini <gianni.bassini@provincia.cremona.it>
 * © 2013 Provincia di Cremona <sito@provincia.cremona.it>
 * 
 * SPDX-License-Identifier: GPL-3.0-only
*/
?>

<?php
require_once __DIR__ . '/../config.php';

// Converte una data dal formato Y-m-d a quello d-m-Y
function dataIta($data) {
    if (empty($data)) {
        return "n.d.";
    }
    list ( $anno, $mese, $giorno ) = preg_split('/-/', $data);
    $dataNew = $giorno . '-' . $mese . '-' . $anno;
    return $dataNew;
}
// ottiene l'URI dei file (temporanei) partendo dall'handler
function get_resource_path($resource) {
    $metadata = stream_get_meta_data($resource);
    return $metadata['uri'];
}
// Prendo in GET anno e user
$anno = (int) $_GET['anno'];
if (array_key_exists('user', $_GET) && !empty($_GET['user'])) {
    $user = $db->real_escape_string($_GET['user']);
    if ($user != 'tutti') {
        $where = "AND `userins` = '{$user}'";
    }
}
if ($anno > 2011 && $anno <= date('Y')) {
    $query = "SELECT * FROM avcp_export_ods WHERE `anno` = {$anno} {$where} ORDER BY `numAtto`";
    $res = $db->query($query);
    $num = $db->affected_rows;
    //echo "Trovati $num lotti!";
    for ($x = 0; $x < $num; $x++) {
        $rows[$x] = $res->fetch_assoc();
    }
    /*
    echo '<pre>'.PHP_EOL;
    var_dump($rows);
    echo '</pre>'.PHP_EOL;
     */
    // Preparo il documento ods
    header("Content-Disposition: attachment; filename=lotti-" . $anno . "-". $user .".ods");
    header("Content-Type: application/force-download");
    header("Content-Type: application/vnd.oasis.opendocument.spreadsheet");

    $tmpcontentxml = tmpfile();
    $tmpmanifestxml = tmpfile();
    $tmpstylesxml = tmpfile();
    $tmpmimetype = tmpfile();

    // Mimetype
    fwrite($tmpmimetype, 'application/vnd.oasis.opendocument.spreadsheet');
    // styles.xml
    fwrite($tmpstylesxml, '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><office:document-styles xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0"/>');
    // manifest.xml
    fwrite($tmpmanifestxml, '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
        <manifest:manifest xmlns:manifest="urn:oasis:names:tc:opendocument:xmlns:manifest:1.0">
        <manifest:file-entry manifest:full-path="/" manifest:media-type="application/vnd.oasis.opendocument.spreadsheet"/>
        <manifest:file-entry manifest:full-path="META-INF/manifest.xml" manifest:media-type="text/xml"/>
        <manifest:file-entry manifest:full-path="content.xml" manifest:media-type="text/xml"/>
        <manifest:file-entry manifest:full-path="mimetype" manifest:media-type="text/plain"/>
        <manifest:file-entry manifest:full-path="styles.xml" manifest:media-type="text/xml"/>
        </manifest:manifest>');

    // content.xml - inizio
    fwrite($tmpcontentxml, '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
        <office:document-content
        xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0"
        xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0"
        xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0"
        xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0"
        xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0">
        <office:automatic-styles>
        <style:style style:name="table_header" style:family="table-cell" style:parent-style-name="Default" style:data-style-name="N0">
        <style:text-properties fo:font-weight="bold" style:font-weight-asian="bold" style:font-weight-complex="bold"/>
        </style:style>
        </office:automatic-styles>
        <office:body><office:spreadsheet><table:table table:name="lotti-' . $anno . '-' . $user .'">');

    // content.xml - intestazione
    fwrite($tmpcontentxml, '<table:table-row>
        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Stato</text:p></table:table-cell>
        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>ID</text:p></table:table-cell>
        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Anno</text:p></table:table-cell>
        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Utente</text:p></table:table-cell>
        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Atto</text:p></table:table-cell>
        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>CIG</text:p></table:table-cell>
        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Oggetto</text:p></table:table-cell>
        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Scelta Contraente</text:p></table:table-cell>
        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Inizio</text:p></table:table-cell>
        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Fine</text:p></table:table-cell>
        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Importo Aggiudicazione</text:p></table:table-cell>
        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Somme Liquidate</text:p></table:table-cell>
        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>N. partecipanti</text:p></table:table-cell>
        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>N. aggiudicatari</text:p></table:table-cell>
        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Aggiudicatari</text:p></table:table-cell>
        </table:table-row>');

    // content.xml - righe
    foreach ($rows as $row) {
        foreach ($row as $key => $value) {
            $row[$key] = mb_convert_encoding($value, "UTF-8", "ISO-8859-15, ISO-8859-1, CP1251, CP1252");
        }
        if (($row['importoAggiudicazione'] > 0 && $row['importoSommeLiquidate'] >= $row['importoAggiudicazione']) || $row['chiuso'] == 1) {
            $row['stato'] = 'conclusa';
        } else {
            $row['stato'] = 'in corso';
        } 
        fwrite($tmpcontentxml, "<table:table-row>\n"); // inizio riga

        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA['.$row['stato'].']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA['.$row['id'].']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA['.$row['anno'].']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA['.$row['userins'].']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA['.$row['numAtto'].']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA['.$row['cig'].']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA['.$row['oggetto'].']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA['.$row['sceltaContraente'].']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA['.$row['dataInizio'].']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA['.$row['dataUltimazione'].']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA['.$row['importoAggiudicazione'].']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA['.$row['importoSommeLiquidate'].']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA['.$row['partecipanti'].']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA['.$row['aggiudicatari'].']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA['.$row['nome_aggiudicatari'].']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, "</table:table-row>\n"); // fine riga
    }
    // content.xml - Chiusura
    fwrite($tmpcontentxml, '</table:table></office:spreadsheet></office:body>
        </office:document-content>');

    // Creo l'archivio zip del file ods
    $tmpzipfilename = tempnam(sys_get_temp_dir(), 'FOO');

    $zip = new ZipArchive();
    $zip->open($tmpzipfilename, ZIPARCHIVE::CREATE);
    $zip->addFile(get_resource_path($tmpmimetype), "mimetype");
    $zip->addFile(get_resource_path($tmpcontentxml), "content.xml");
    $zip->addFile(get_resource_path($tmpstylesxml), "styles.xml");
    $zip->addFile(get_resource_path($tmpmanifestxml), "META-INF/manifest.xml");
    $zip->close();

    // Rimuovo i file temporanei
    fclose($tmpcontentxml);
    fclose($tmpmanifestxml);
    fclose($tmpstylesxml);
    fclose($tmpmimetype);

    // Invio il file zip
    readfile($tmpzipfilename);

    // Rimuovo il file zip temporaneo
    unlink($tmpzipfilename);
} else {
    echo 'Messaggio di errore';
}
