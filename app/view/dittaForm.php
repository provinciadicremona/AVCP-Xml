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
// init undefined vars
isset($ragioneSociale) || $ragioneSociale = '';
isset($codiceFiscale) || $codiceFiscale = '';
?>
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
