<div class="row">
    <div class="span8 offset2">
        <h1>Ditta</h1>
        <form action="?mask=ditta&amp;do=<?php echo $formDo;?>" method="post">
            <div class="well">
                <label for="ragioneSociale">Ragione sociale</label>
                <input type="text" name="ragioneSociale" id="ragioneSociale" class="span6" placeholder="Ragione Sociale" value="<?php echo $ragioneSociale; ?>" />
                <label for="codiceFiscale">Cod. Fiscale</label>
                <input type="text" name="codiceFiscale" id="codiceFiscale" class="span6" placeholder="Cod. Fiscale" value="<?php echo $codiceFiscale; ?>" />
                <label for="estero">Residenza</label>
                <label class="radio">
                    <input type="radio" name="estero" id="estero" value="0" <?php echo $esteroRadio[0]; ?> />
                    La ditta risiede in Italia
                </label>
                <label class="radio">
                    <input type="radio" name="estero" id="estero" value="1" <?php echo $esteroRadio[1]; ?>/>
                    La ditta risiede all'estero
                </label>
            </div>
                <input type="hidden" name="oldCodiceFiscale" value="<?php echo $codiceFiscale; ?>" />
                <input class="btn" type="reset" value="Pulisci" />&nbsp;
                <input class="btn btn-large btn-primary" type="submit" value="<?php echo $formButton;?> &raquo;" />
            </form>
    </div>
</div>
