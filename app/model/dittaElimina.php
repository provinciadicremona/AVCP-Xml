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
require_once __DIR__ . '/ditteElenca.php';
