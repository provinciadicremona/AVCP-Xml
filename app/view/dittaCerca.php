<?php
// init undefined vars
isset($ragioneSociale) || $ragioneSociale = '';
isset($codiceFiscale) || $codiceFiscale = '';
isset($esteroRadio) || $esteroRadio = array(' checked','');

$customJsScript = '
<script>
    $(function() {
        let ac_config = {
        source: "app/webServices/searchDitta.php",
        select: function(event, ui){
            $("#cercaRagSoc").val(ui.item.ragioneSociale);
            $("#codiceFiscale").val(ui.item.codiceFiscale);
            $("#ragioneSociale").val(ui.item.ragioneSociale);
            $("#estero").val(ui.item.estero);
            if (ui.item.estero == 0)
            {
                $("input:radio[name=estero]")[0].checked = true;
            } else {
                $("input:radio[name=estero]")[1].checked = true;
            }
            radio.button("refresh");
        },
        minLength:2,
        delay: 500
        };
        $("#cercaRagSoc").autocomplete(ac_config);
    });
</script>
';
?>
<div class="row">
    <div class="span8 offset2">
        <h1><?php echo $formTitle; ?></h1>
        <form action="<?php echo $formAction; ?>" method="post">
            <div class="well">
                <fieldset>
                    <legend>Cerca ragione sociale o C.F.:</legend>
                    <input type="text" name="cercaRagSoc" id="cercaRagSoc" class="span6" placeholder="Cerca ragione sociale o Codice Fiscale" autofocus />
                </fieldset>
                <fieldset>
                    <legend>
                        <?php if (isset($_GET['id']) && !is_null($_GET['id'])): ?>
                        Inserisci ditta /
                    <?php endif; ?>
                        Ditta selezionata:</legend>
                    <label for="ragioneSociale">Ragione sociale<?php echo ' - ' . $formAlertCF; ?></label>
                    <input type="text" name="ragioneSociale" id="ragioneSociale" class="span6" placeholder="Ragione Sociale" value="<?php echo $ragioneSociale; ?>" />
                    <label for="codiceFiscale">Cod. Fiscale<?php echo ' - ' . $formAlertCF; ?></label>
                    <input type="text" name="codiceFiscale" id="codiceFiscale" class="span6" placeholder="Cod. Fiscale" value="<?php echo $codiceFiscale; ?>" />
                    <label for="estero">Residenza</label>
                    <label class="radio">
                        <input type="radio" name="estero" id="estero" value="0" <?php echo $esteroRadio[0]; ?> />
                        La ditta risiede in Italia
                    </label>
                    <label class="radio">
                        <input type="radio" name="estero" id="estero" value="1" <?php echo $esteroRadio[1]; ?>/>
                        La ditta risiede all'estero
                    </label><br />
                    <?php echo $formCig . PHP_EOL . $formRag; ?>
                </fieldset>
            </div>
            <?php echo $formRuolo . PHP_EOL; ?>
            <input class="btn" type="reset" value="Pulisci" />&nbsp;
            <?php echo $formButton . PHP_EOL; ?>

        </form>
    </div>
</div>
