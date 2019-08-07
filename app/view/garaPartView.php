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

<?php if(!is_null($outTopAg)): ?>
        <h3>Aggiudicatari</h3>
        <?php echo $outTopAg; ?>
<?php endif; ?>
    <h3>Partecipanti</h3>
        <table class="table table-condensed">
            <tr>
                <th>Raggruppamenti</th>
                <th>Ruolo</th>
                <th class="span8">Ditta</th>
            </tr>
<?php
if (!empty($rows)) :
    $oldRag = 0;
    $ultimo = count($rows) - 1;
    $maxRag = $rows[$ultimo]['raggruppamento'] + 1;
    foreach ($rows as $part) :
?>
    <?php if ($part['raggruppamento'] > 0 && $part['raggruppamento'] > $oldRag) : ?>
    <tr class="success">
                <td><strong>Raggruppamento <?php echo $part['raggruppamento']; ?></strong><br />
    <?php else : ?>
            <tr>
                <td>
<?php
    endif;
?>
                </td>
                <td>
        <?php if ($part['raggruppamento'] > 0 ): ?>
            <?php if ($part['raggruppamento'] > $oldRag): ?>
            <?php $oldRag = $part['raggruppamento']; ?>

            <?php echo $part['ruolo']; ?><br />

            <?php else: ?>
                <?php echo $part['ruolo']; ?>
            <?php endif; ?>
        <?php endif; ?>
        </td>
                <td><a href="?mask=ditta&amp;do=dittaGare&amp;event=partecipa&amp;id=<?php echo $part['codiceFiscale']; ?>"><?php echo $part['ragioneSociale']; ?></a><br /></td>
            </tr>
<?php
endforeach ;
else : ?>
    <tr>
                <td colspan="3">Nessun partecipante inserito</td>
            </tr>
<?php
endif;
$newRag = $part['raggruppamento'] + 1;
?>
        </table>
<hr />
