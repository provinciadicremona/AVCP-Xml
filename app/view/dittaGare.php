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
$customJsScript = "
<script>
function GetUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] == sParam) { 
            return sParameterName[1]; 
        } 
    } 
}

function setPartecipa() {
    partecipa.show();
    aggiudica.hide();
    $('.btn-partecipa').addClass('btn-success');
    $('.btn-aggiudica').removeClass('btn-success');
    $('.btn-partecipa').html('Ha partecipato alle gare');
    $('.btn-aggiudica').html('Vedi le gare aggiudicate');
}

function setAggiudica() {
    aggiudica.show();
    partecipa.hide();
    $('.btn-aggiudica').addClass('btn-success');
    $('.btn-partecipa').removeClass('btn-success');
    $('.btn-partecipa').html('Vedi le gare a cui ha partecipato');
    $('.btn-aggiudica').html('Si è aggiudicata le gare');
}

var evento = GetUrlParameter('event');
var rows = $('table.ditte tr');
var partecipa = rows.filter('.01-PARTECIPANTE');
var aggiudica = rows.filter('.02-AGGIUDICATARIO');

if (evento == 'aggiudica') {
    setAggiudica();
} else {
    setPartecipa();
}

$('#showPartecipa').click(function() {
    setPartecipa();
});

$('#showAggiudica').click(function() {
    setAggiudica();
});
</script>
";
?>
<div class="row">
    <div class="span12">
        <p><a class="btn btn-primary" href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><i class="icon-backward icon-white"></i>&nbsp;&nbsp;Indietro</a></p>
        <h1>
            Dettaglio gare ditta
        </h1>
        <p class="lead">
        <strong>Ragione sociale:</strong> <?php echo $rows[0]['ragioneSociale']; ?>
        <br>
        <strong>CF/P.IVA:</strong><?php echo $rows[0]['codiceFiscale']; ?> <strong>Estera:</strong>
        <?php 
        if ($rows[0]['estero']) {
            echo 'sì';
        } else { 
            echo 'no';
        }
        ?>
        </p>
        <button class="btn btn-partecipa" id="showPartecipa">Ha partecipato alle gare</button>
        <button class="btn btn-aggiudica" id="showAggiudica">Si è aggiudicata le gare</button>
        <div class="well">
            <table class="table table-condensed table-hover ditte"
                id="elenco">
                <thead>
                    <tr>
                        <th>Anno</th>
                        <th>Atto</th>
                        <th>CIG</th>
                        <th>Lotto</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($rows as $row): ?>
                    <tr class="<?php echo $row['funzione']; ?>">
                        <td><?php echo $row['anno']; ?></td>
                        <td><?php echo $row['numAtto']; ?></td>
                        <td><?php echo $row['cig']; ?></td>
                        <td><a href="?mask=gara&amp;do=cercaGara&amp;event=garaSelezionata&amp;idDaElenco=<?php echo $row['id']; ?>"><?php echo $row['oggetto']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
