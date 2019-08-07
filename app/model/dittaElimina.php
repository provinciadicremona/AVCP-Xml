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
if (!empty($_GET['eleDitta'])) {
    $queryEle = "DELETE FROM avcp_ditta WHERE codiceFiscale = '" . $db->real_escape_string($_GET['eleDitta']) . "'";
    $resEle = $db->query($queryEle);
    $conferma = null;
    if (1 == mysqli_affected_rows($db)) {
        $conferma = '
                    <div class="row">
                        <div class="span12">
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Ditta cancellata!</strong>
                            </div>
                        </div>
                    </div>';
    }
}
require_once AVCP_DIR . 'app/model/ditteElenca.php';
