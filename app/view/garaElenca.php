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
$customJsScript = '
<script>
    $(function() {
        $( ".poppo" ).popover({
            placement: "top",
            html: true,
            trigger: "hover",
            title: "Aggiudicatari:"
        });
    });
</script>
';
$customJsScript .= PHP_EOL . '
    <script type="text/javascript">
        $(document).ready(function(){
            $("#filter").keyup(function(){

                // Retrieve the input field text and reset the count to zero
                let filter = $(this).val(), count = 0;

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
            if (numberItems > 1){
                $("#filter-count").text("Trovate "+numberItems+" gare");
            } else if (numberItems == 1) {
                $("#filter-count").text("Trovata "+numberItems+" gara");
            } else {
                $("#filter-count").text("Nessuna gara trovata");
                }
            });
        });
    </script>
';
isset($usersel) || $usersel = '';
?>
<div class="row">
    <div class="span12">
        <h1>Elenco Gare anno <?php echo $anno; ?></h1>
        <p>Totale inserite: <?php echo $quante; ?></p>
        <div class="well">

            <form
                action="?mask=gara&amp;do=elencaGareAnno&amp;anno=<?php echo $anno;?>"
                method="post" class="form-inline">
                <label for="usersel">Filtra per utente:</label>
                <select name="usersel">
                    <option value="tutti"
                        <?php if ($usersel == 'tutti') echo "selected"; ?>>-- Tutti --</option>
                    <option value="vuota"
                        <?php if ($usersel == 'vuota') echo "selected"; ?>>-- Senza utente
                        --</option>
<?php
$userList = array_keys($user);
foreach ($userList as $u) :
?>
                    <option <?php if ($u === $usersel) echo "selected";?>><?php echo $u;?></option>
                    <?php endforeach;?>

                </select> <input class="btn btn-primary" type="submit" value="Filtra &raquo;" />
                &nbsp;<a href="app/webServices/odsUserExport.php?anno=<?php echo $anno; ?>&amp;user=<?php echo $usersel; ?>"
                title="Elenco relativo all'utente selezionato e comprensivo di numero atto">Scarica elenco in formato ods</a>
            </form>

            <!--  Inizio liveSearch -->
            <form id="live-search" action="" class="styled" method="post">
                <fieldset>
                    <input type="text" class="span4" id="filter" value=""
                        placeholder="Cerca gare, CIG e utente" /> <a href="#cercaModal"
                        title="Aiuto ricerca" data-toggle="modal"><i
                        class="icon-question-sign"></i></a> <span id="filter-count"></span>
                </fieldset>
            </form>
            <div id="cercaModal" class="modal hide fade" tabindex="-1"
                role="dialog" aria-labelledby="cercaoModalLabel" aria-hidden="true">
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
            <!-- / modal -->
            <!-- fine liveSearch (aggiunta id=elenco a table e class=dati alle tr nel model) -->
            <table class="table table-striped table-condensed table-hover"
                id="elenco">
                <tr>
                    <th>&nbsp;</th>
                    <th>Oggetto e C.I.G.</th>
                    <th width="8%"><abbr title="Importo di aggiudicazione">Imp. Agg.</abbr></th>
                    <th width="8%"><abbr title="Importo somme liquidate">Imp. Liq.</abbr></th>
                    <th><abbr title="Numero partecipanti">Part.</abbr></th>
                    <th width="8%"><abbr title="Aggiudicatari">Agg.</abbr></th>
                    <th><abbr title="Utente che ha inserito o importato la gara">Utente</abbr></th>
                    <th>&nbsp;</th>
                </tr>
            <?php for($x=0; $x < $quante; $x++): ?>
<?php if ($out[$x]['chiuso'] == 1 || $out[$x]['importoSommeLiquidate'] >= ($out[$x]['importoAggiudicazione'] )) : ?>
                <tr class="text-center dati success">
<?php else : ?>
                <tr class="text-center dati">
<?php endif; ?>
                    <td><p>&nbsp;<a
                        href="?mask=gara&amp;do=cercaGara&amp;event=garaSelezionata&amp;idDaElenco=<?php echo $out[$x]['id'];?>"
                        title="Gestisci gara"><i class="icon-search"></i></a>&nbsp;</p>
<?php
    if (isset($sceltaContraente) && $sceltaContraente == '00-DA DEFINIRE')
        echo '<br /><span class="label label-important">Definire Contraente!</span>';
?>
                    </td>
                    <td><?php echo $out[$x]['oggetto'];?><br /> <strong>C.I.G.:</strong> <?php echo $out[$x]['cig'];?></td>
                    <td><?php stampaValuta($out[$x]['importoAggiudicazione']);?></td>
                    <td><?php stampaValuta($out[$x]['importoSommeLiquidate']);?></td>
                    <td>
<?php
if ($out[$x]['partecipanti'] > 0) {
    echo '<span class="badge badge-success">' . $out[$x]['partecipanti'] . '</span>';
} else {
    echo '<span class="badge badge-warning">0</span>';
}
?>
                    </td>
                    <td>
<?php
if ($out[$x]['aggiudicatari'] > 0) {
    echo '<span class="badge badge-success">' . $out[$x]['aggiudicatari'] . '</span>';
    $out[$x]['nome_aggiudicatari'] = '<ul><li>' . str_replace('xxxxx', '</li><li>', $out[$x]['nome_aggiudicatari']) . '</li></ul>' . PHP_EOL;
    echo '&nbsp;<a href="#" class="poppo" data-toggle="popover" data-content="' . $out[$x]['nome_aggiudicatari'] . '"><i class="icon-eye-open"></i></a>';
} else {
    echo '<span class="badge badge-warning">0</span>';
}
?>
                    </td>
                    <td><?php echo $out[$x]['userins'];?></td>
                    <td>
                        <p>&nbsp;<a href="?mask=gara&amp;do=duplicaGara&amp;idDaElenco=<?php echo $out[$x]['id'];?>" title="Duplica Gara"><i class="icon-plus"></i></a>&nbsp;</p>
                    </td>
                </tr>
                <?php endfor;?>

            </table>

        </div>
    </div>
</div>
