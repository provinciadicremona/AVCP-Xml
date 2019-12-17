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
// Cerca una gara all'interno della tabella avcp_lotti
// e restituisce un array coi risultati in formato json
require_once '../config.php';
$cerca = $db->real_escape_string($_GET['term']);

$query = "SELECT id, oggetto, cig, anno FROM avcp_lotto
    WHERE (
        oggetto LIKE '%" . $cerca . "%'
        OR cig LIKE '%" . $cerca . "%'
    )
    ORDER BY anno DESC";

$res = $db->query($query);
$num = $res->num_rows;

for($x = 0, $matches = null; $x < $num; $x++, $row = null) {
    $row = $res->fetch_assoc();
    $row['value'] = $row['oggetto'];
    $row['label'] = "[{$row['anno']}] - CIG: {$row['cig']} - {$row['oggetto']}";
    $matches[] = $row;
}
$res->free();
// Patch json per versioni php < 5.2
if (version_compare(PHP_VERSION, '5.2', '<')) {
    require_once 'ajaxFunctions.php';
    echo array_to_json($matches);
} else {
    echo json_encode($matches);
}
