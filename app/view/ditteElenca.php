<?php
$customJsScript .= PHP_EOL . '
<script type="text/javascript">
    $(document).ready(function(){
    $("#filter").keyup(function(){

        // Retrieve the input field text and reset the count to zero
        var filter = $(this).val(), count = 0;

        // Loop through the comment list
        $("#elenco tr.dati").each(function(){

            // If the list item does not contain the text phrase fade it out
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).fadeOut();

            // Show the list item if the phrase matches and increase the count by 1
            } else {
                $(this).show();
                count++;
            }
            if (count < 0 ){
                count = 0;
            }
        });

        // Update the count
        var numberItems = count;
        if (count > 1){
            $("#filter-count").text("Trovate "+count+" ditte");
        } else if (count == 1) {
                $("#filter-count").text("Trovata "+count+" ditta");
        } else {
            $("#filter-count").text("Nessuna ditta trovata");
        }
    });
});
</script>
';
?>

<?php if (isset($conferma)){
    echo $conferma;
} ?>
<div class="row">
    <div class="span12">
        <h1>Elenco Ditte</h1>
        <p>Totale inserite: <?php echo $quante; ?></p>
        <div class="well">
            <!--  Inizio liveSearch -->
            <form id="live-search" action="" class="styled" method="post">
                <fieldset>
                    <input type="text" class="text-input" id="filter" value=""
                        placeholder="Cerca ditta e CF" /> <a href="#cercaModal"
                        title="Aiuto ricerca" data-toggle="modal"><i
                        class="icon-question-sign"></i></a> <span id="filter-count"></span>
                </fieldset>
            </form>
            <!-- fine liveSearch (aggiunta id=elenco a table -->
            <table class="table table-striped table-condensed table-hover"
                id="elenco">
                <tr>
                    <th width="10%">Azioni</th>
                    <th width="18%">Codice Fiscale</th>
                    <th>Ragione Sociale</th>
                    <th>Nazionalità</th>
                    <th>Partecipa</th>
                    <th>Aggiudica</th>
                </tr>
<?php if (isset($ditte)): ?>
<?php foreach ($ditte as $ditta): ?>
                <tr class="dati">
                    <td><a
                        href="?mask=ditta&amp;codiceFiscale=<?php echo $ditta['codiceFiscale']; ?>"
                        title="Modifica ditta"><i class="icon-pencil"></i></a>
                        &nbsp;/&nbsp;
                        <?php echo $ditta['elimina']; ?>
                    </td>
                    <td> &nbsp;<?php echo $ditta['codiceFiscale']; ?> </td>
                    <td> <?php echo $ditta['ragioneSociale']; ?> </td>
                    <td> <?php echo $ditta['estero']; ?>
                    </td>
                    <td>
    <?php if ($ditta['partecipa'] > 0) : ?>
        <a href="?mask=ditta&amp;do=gareDitta&amp;event=partecipa&amp;id=<?php echo $ditta['codiceFiscale']; ?>"><?php echo $ditta['partecipa']; ?>
        <?php if ($ditta['partecipa'] > 1) : ?>
            &nbsp;gare
        <?php else : ?>
            &nbsp;gara
        <?php endif;?>
        </a>
    <?php else : ?>
        nessuna
    <?php endif;?>
                    </td>
                    <td>
    <?php if ($ditta['aggiudica'] > 0) : ?>
        <a href="?mask=ditta&amp;do=gareDitta&amp;event=aggiudica&amp;id=<?php echo $ditta['codiceFiscale']; ?>"><?php echo $ditta['aggiudica']; ?>
        <?php if ($ditta['aggiudica'] > 1) : ?>
            &nbsp;gare
        <?php else : ?>
            &nbsp;gara
        <?php endif;?>
        </a>
    <?php else : ?>
        nessuna
    <?php endif;?>
                    </td>
                </tr>
            <?php endforeach;	?>
        <?php endif;?>
            </table>
        </div>
    </div>
</div>

<!-- Finestra di aiuto -->
<div id="cercaModal" class="modal hide fade" tabindex="-1"
    role="dialog" aria-labelledby="cercaModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
            aria-hidden="true">×</button>
        <h3 id="cercaModalLabel">Ricerca</h3>
    </div>
    <div class="modal-body">
        <p>Scrivendo all'interno di questo campo si restringerà l'elenco
            alle righe che soddisfano i criteri di ricerca.</p>
        <h4>Attenzione!</h4>
        <p>
            La ricerca <strong>non distingue</strong> tra maiuscole e
            minuscole e soprattutto, per inserire i caratteri <strong>punto</strong>
            (.) e <strong>più</strong> (+) ricordarsi di farli precedere dal <strong>carattere
                "\"</strong>.
        </p>
        <p>Ad esempio:</p>
        <ul>
            <li>per trovare la ditta B+B Solutions, cercare b\+b</li>
            <li>per trovare la ditta A.Cispi, cercare a\.cispi</li>
        </ul>

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Chiudi</button>
    </div>
</div>
<!-- / finestra di aiuto -->
