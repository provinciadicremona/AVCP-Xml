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

function dataIta($data) {
    if (empty($data)) {
        return "n.d.";
    }
    list ($anno, $mese, $giorno) = preg_split('/-/', $data);
    $dataNew = $giorno . '-' . $mese . '-' . $anno;
    return $dataNew;
}

function get_resource_path($resource) {
    $metadata = stream_get_meta_data($resource);
    return $metadata['uri'];
}
// TO FIX???
$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);
$uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);

try {
    if (!($type == 'html') && !($type == 'ods'))
        throw new Exception('<h1>Formato richiesto non suppportato (solo html o ods)</h1><p><a href="./">Torna all\'homepage</a></p>', 1);
} catch (Exception $e) {
    echo $e->getMessage();
    die();
}

require_once './xml/testa_xml_avcp_query.php';

try {
    if (!($document = simplexml_load_string($XML_TOT)))
        throw new Exception('<h1>Errori nel caricamento del file xml!</h1><p><a href="./">Torna all\'homepage</a></p>', 1);
} catch (Exception $e) {
    echo $e->getMessage();
}

if ($type == 'html') {
    header("Content-Disposition: attachment; filename=avcp_dataset_" . $anno . ".html");
    header("Content-Type: text/force-download");
    header("content-type: text/html");
    echo "<p>{$document->metadata->titolo}, anno di riferimento {$document->metadata->annoRiferimento}<br />" . PHP_EOL;
    echo "URL pubblicazione: <a href=\"{$document->metadata->urlFile}\">{$document->metadata->urlFile}</a></p>" . PHP_EOL;
    echo "<table>" . PHP_EOL;
    echo "<tr>" . PHP_EOL;
    echo "<th>Lotto</th>" . PHP_EOL;
    echo "<th>Partecipanti</th>" . PHP_EOL;
    echo "<th>Aggiudicatari</th>" . PHP_EOL;

    foreach ($document->data->lotto as $lotto) {
        echo "<tr>" . PHP_EOL;
        echo "<td><strong>CIG:</strong> {$lotto->cig}<br />" . PHP_EOL;
        echo "<strong>Oggetto:</strong> {$lotto->oggetto}<br />" . PHP_EOL;
        echo "<strong>Importo aggiudicato:</strong> &euro; {$lotto->importoAggiudicazione}<br />" . PHP_EOL;
        echo "<strong>Somme liquidate:</strong> &euro; {$lotto->importoSommeLiquidate}<br />" . PHP_EOL;
        echo "<strong>Tempi di completamento:</strong> dal " . dataIta($lotto->tempiCompletamento->dataInizio) . " al " . dataIta($lotto->tempiCompletamento->dataUltimazione) . "<br />" . PHP_EOL;
        echo "<strong>Cod. Scelta contraente:</strong> {$lotto->sceltaContraente}</td>" . PHP_EOL;
        echo "<td><ul>" . PHP_EOL;
        foreach ($lotto->partecipanti->raggruppamento as $partRagg) {
            echo "<li><strong>Raggruppamento:</strong><br />" . PHP_EOL;
            foreach ($partRagg->membro as $part) {
                echo "{$part->ragioneSociale}<br /><strong>CF: </strong>{$part->codiceFiscale}<br />&nbsp;<br />" . PHP_EOL;
            }
            echo "</li>" . PHP_EOL;
        }
        foreach ($lotto->partecipanti->partecipante as $part) {
            echo "<li>{$part->ragioneSociale}<br /><strong>CF: </strong>{$part->codiceFiscale}</li>" . PHP_EOL;
        }
        echo "</ul></td>" . PHP_EOL;
        echo "<td><ul>" . PHP_EOL;
        foreach ($lotto->aggiudicatari->aggiudicatarioRaggruppamento as $aggRagg) {
            echo "<li><strong>Raggruppamento:</strong><br />" . PHP_EOL;
            foreach ($partRagg->membro as $agg) {
                echo "{$agg->ragioneSociale}<br /><strong>CF: </strong>{$agg->codiceFiscale}<br />&nbsp;<br />" . PHP_EOL;
            }
            echo "</li>" . PHP_EOL;
        }
        foreach ($lotto->aggiudicatari->aggiudicatario as $agg) {
            echo "<li>{$agg->ragioneSociale}<br /><strong>CF: </strong>{$agg->codiceFiscale}</li>" . PHP_EOL;
        }
        echo "</ul></td>" . PHP_EOL;
        echo "</tr>" . PHP_EOL;
    }
    echo "</table>";
} else {
    header("Content-Disposition: attachment; filename=avcp_dataset_" . $anno . ".ods");
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
    <office:body><office:spreadsheet><table:table table:name="' . $anno . '">');

    // content.xml - intestazione
    fwrite($tmpcontentxml, '<table:table-row>
                        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>CIG</text:p></table:table-cell>
                        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Oggetto</text:p></table:table-cell>
                        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Imp. aggiudicato</text:p></table:table-cell>
                        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Imp. liquidato</text:p></table:table-cell>
                        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Inizio</text:p></table:table-cell>
                        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Fine</text:p></table:table-cell>
                        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Cod. scelta contraente</text:p></table:table-cell>
                        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Partecipanti</text:p></table:table-cell>
                        <table:table-cell office:value-type="string" table:style-name="table_header"><text:p>Aggiudicatari</text:p></table:table-cell>
                    </table:table-row>');

    // content.xml - righe
    foreach ($document->data->lotto as $lotto) {
        fwrite($tmpcontentxml, "<table:table-row>\n");

        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA[' . $lotto->cig . ']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA[' . $lotto->oggetto . ']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA[€ ' . $lotto->importoAggiudicazione . ']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA[€ ' . $lotto->importoSommeLiquidate . ']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA[' . dataIta($lotto->tempiCompletamento->dataInizio) . ']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA[' . dataIta($lotto->tempiCompletamento->dataUltimazione) . ']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA[' . $lotto->sceltaContraente . ']]></text:p></table:table-cell>');

        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA[');
        foreach ($lotto->partecipanti->raggruppamento as $partRagg) {
            fwrite($tmpcontentxml, "Raggruppamento: ( ");
            $i = 0;
            foreach ($partRagg->membro as $part) {
                fwrite($tmpcontentxml, ($i++ ? " / " : "") . "{$part->ragioneSociale} - CF: {$part->codiceFiscale}");
            }
            fwrite($tmpcontentxml, " ) ");
        }
        $i = 0;
        foreach ($lotto->partecipanti->partecipante as $part) {
            fwrite($tmpcontentxml, ($i++ ? " / " : "") . "{$part->ragioneSociale} - CF: {$part->codiceFiscale}");
        }
        fwrite($tmpcontentxml, ']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, '<table:table-cell office:value-type="string"><text:p><![CDATA[');
        foreach ($lotto->aggiudicatari->aggiudicatarioRaggruppamento as $aggRagg) {
            fwrite($tmpcontentxml, "Raggruppamento: ( "); // . PHP_EOL;
            $i = 0;
            foreach ($aggRagg->membro as $agg) {
                fwrite($tmpcontentxml, ($i++ ? " / " : "") . "{$agg->ragioneSociale} - CF: {$agg->codiceFiscale}");
            }
            fwrite($tmpcontentxml, " ) "); // . PHP_EOL;
        }
        $i = 0;
        foreach ($lotto->aggiudicatari->aggiudicatario as $agg) {
            fwrite($tmpcontentxml, ($i++ ? " / " : "") . "{$agg->ragioneSociale} - CF: {$agg->codiceFiscale}");
        }
        fwrite($tmpcontentxml, ']]></text:p></table:table-cell>');
        fwrite($tmpcontentxml, "</table:table-row>\n");
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
}

?>
