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
$oldRag = 0;
$ultimo = count($rows) - 1;
$maxRag = $rows[$ultimo]['raggruppamento'] + 1;
if (!empty($rows)) :
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
                <td><?php echo $part['ragioneSociale']; ?><br /></td>
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
