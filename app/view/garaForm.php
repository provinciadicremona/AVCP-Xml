<?php
$customJsScript = '
<script>
    $(function() {
        /*
        var ac_config = {
        source: "app/webServices/searchProponente.php",
        select: function(event, ui){
            $("#denominazione").val(ui.item.denominazione);
            $("#codiceFiscaleProp").val(ui.item.codiceFiscaleProp);
        },
        minLength:2
        };
        $("#denominazione").autocomplete(ac_config);
        */
        $( "#dataInizio" ).datepicker({
            dateFormat: "yy-mm-dd"
        });

        $( "#dataUltimazione" ).datepicker({
            dateFormat: "yy-mm-dd"
        });
    });
</script>
';
?>
<div class="row">
    <div class="span8 offset2">
        <h1>Dettagli gara</h1>
        <form action="?mask=gara&amp;do=<?php echo $formDo;?>" method="post">
            <div class="well">
                <fieldset>
                    <legend>Dati dell'ente proponente:</legend>
                    <div class="ui-widget">
                        <label for="denominazione">Denominazione: <?php echo ENTE_PROPONENTE; ?></label>
                        <input type="hidden" name="denominazione" id="denominazione"
                            class="span6" placeholder="Denominazione ente proponente"
                            value="<?php echo ENTE_PROPONENTE; ?>" />
                    </div>
                    <label for="codiceFiscaleProp">Codice Fiscale: <?php echo CF_PROPONENTE; ?> </label>
                    <input type="hidden" name="codiceFiscaleProp"
                        id="codiceFiscaleProp" placeholder="Codice Fiscale Ente"
                        value="<?php echo CF_PROPONENTE; ?>" />
                </fieldset>
                <fieldset>
                    <legend>Dati gara:</legend>
                    <label for="anno">Anno di riferimento</label> <input type="text"
                        name="anno" class="span1" placeholder="Anno"
                        value="<?php echo $anno; ?>" /> <input type="hidden" name="id"
                        id="id" value="<?php echo $id; ?>" /> <label for="cig">C.I.G.</label>
                    <input type="text" name="cig" maxlength="10"
                        placeholder="Codice Identificativo Gara"
                        value="<?php echo $cig; ?>" /> <label for="numAtto">Numero Atto/i
                        (facoltativo)</label> <input type="text" name="numAtto"
                        maxlength="255" placeholder="Numero della determinazione"
                        value="<?php echo $numAtto; ?>" /> <a href="#attoModal"
                        title="Informazioni sul numero atto" data-toggle="modal"><i
                        class="icon-question-sign"></i></a>
                    <!-- Inizio Modal Atto -->
                    <div id="attoModal" class="modal hide fade" tabindex="-1"
                        role="dialog" aria-labelledby="attoModalLabel" aria-hidden="true">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">×</button>
                            <h3 id="attoModalLabel">Numero Atto</h3>
                        </div>
                        <div class="modal-body">
                            <p>
                                Il numero atto <strong>non è un campo richiesto dall'AVCP</strong>
                                e non viene esportato nel file XML. &Egrave; stato aggiunto
                                perché può tornare utile alle amministrazioni per rintracciare i
                                documenti da cui estrapolare le informazioni.
                            </p>
                            <h4>Utilizzo e limiti</h4>
                            <p>
                                Questo è un campo di testo che può contenere fino a <strong>255
                                    caratteri</strong> e può essere utilizzato liberamente
                            </p>
                            <p>Nel caso di più determine per singolo GIG è quindi possibile
                                registrare tutti gli atti interessati.</p>


                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Chiudi</button>
                        </div>
                    </div>
                    <!-- / modal atto -->
                    <label for="oggetto">Oggetto</label>
                    <textarea name="oggetto" class="span6" maxlength="250"
                        placeholder="Oggeto della gara"><?php echo $oggetto; ?></textarea>
                    <label for="sceltaContraente">Scelta contraente</label> <select
                        name="sceltaContraente" class="span6">
                        <?php echo $ruoloOutput;	?>
                    </select> <label for="dataInizio">Data inizio</label> <input
                        type="text" name="dataInizio" id="dataInizio" class="span2"
                        placeholder="aaaa-mm-gg" value="<?php echo $dataInizio; ?>" /> <label
                        for="dataUltimazione">Data fine</label> <input type="text"
                        name="dataUltimazione" id="dataUltimazione" class="span2"
                        placeholder="aaaa-mm-gg" value="<?php echo $dataUltimazione; ?>" />
                    <label for="importoAggiudicazione">Importo aggiudicazione</label> <input
                        type="text" name="importoAggiudicazione"
                        placeholder="Formato: 1234.00"
                        value="<?php echo $importoAggiudicazione; ?>" /> <label
                        for="importoSommeLiquidate">Importo somme liquidate</label> <input
                        type="text" name="importoSommeLiquidate"
                        placeholder="Formato: 1234.00"
                        value="<?php echo $importoSommeLiquidate; ?>" />
                </fieldset>
            </div>
            <input class="btn" type="reset" value="Pulisci" />&nbsp; <input
                class="btn btn-large btn-primary" type="submit"
                value="<?php echo $formButton;?> &raquo;" />
        </form>


    </div>
</div>
