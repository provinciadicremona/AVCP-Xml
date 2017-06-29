<div class="row">
    <div class="span12">
        <table class="table table-condensed">
            <tr>
                <th>Azioni</th>
                <th class="span8">Ditta</th>
                <th>Ruolo</th>
            </tr>
<?php
$oldRag = 0;
$ultimo = count($rows) - 1;
$maxRag = $rows[$ultimo]['raggruppamento'] + 1;
if (!array_key_exists($anno, $bloccati)) : // Verifico che l'anno non sia bloccato
?>
<div class="row">
                <div class="span12">
                    <p>
                        <a class="btn btn-primary"
                            href="?mask=ditta&amp;do=cercaDitta&amp;id=<?php echo stripslashes($id); ?>"><i
                            class="icon-plus icon-white"></i>&nbsp;Ditta</a>&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-primary"
                            href="?mask=ditta&amp;do=cercaDitta&amp;id=<?php echo stripslashes($id); ?>&amp;raggruppamento=<?php echo $maxRag; ?>"><i class="icon-plus icon-white"></i>&nbsp;Raggruppamento <?php echo $newRag; ?></a>
                    </p>
                </div>
            </div>
<?php
endif;
if (!empty($rows)) {
    foreach ($rows as $part) :
?>
    <?php if ($part['raggruppamento'] > 0 && $part['raggruppamento'] > $oldRag) { ?>
    <tr class="success">
                <td><strong>Raggruppamento <?php echo $part['raggruppamento']; ?></strong><br />
    <?php } else { ?>
            <tr>
                <td>
<?php
    }
    if (!array_key_exists($anno, $bloccati)) :
?>
            <a class="btn btn-small btn-danger"
                    href="?mask=gara&amp;do=eliminaDitta&amp;id=<?php echo $id;?>&amp;codiceFiscale=<?php echo $part['codiceFiscale'];?>&amp;ruolo=<?php echo $part['ruolo'];?>"><i
                        class="icon-trash icon-white"></i></a>
            <?php if (($part['raggruppamento'] == 0 || $part['raggruppamento'] > $oldRag) && !in_array($part['codiceFiscale'], $winner) ): ?>
            <a class="btn btn-small btn-success"
                    href="?mask=gara&amp;do=partecipantiGara&amp;id=<?php echo stripslashes($id); ?>&amp;codiceFiscale=<?php echo $part['codiceFiscale'];?>&amp;raggruppamento=<?php echo $part['raggruppamento'];?>&amp;action=aggiudica"><i
                        class="icon-thumbs-up icon-white"></i>&nbsp;Aggiudica</a>
            <?php endif; ?>
            <?php if ($part['raggruppamento'] > 0 && $part['raggruppamento'] > $oldRag): ?>
            <a class="btn btn-small btn-primary"
                    href="?mask=ditta&amp;do=cercaDitta&amp;id=<?php echo stripslashes($id); ?>&amp;raggruppamento=<?php echo $part['raggruppamento']; ?>"><i
                        class="icon-plus icon-white"></i><i class="icon-user icon-white"></i>&nbsp;<?php echo $part['raggruppamento']; ?></a><br />
<?php endif;

endif;
?>
        </td>
                <td><strong><?php echo $part['ragioneSociale']; ?></strong><br /></td>
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
            </tr>
<?php
endforeach
;
} else {
?>
    <tr>
                <td colspan="3">Nessun partecipante inserito</td>
            </tr>
<?php
}
$newRag = $part['raggruppamento'] + 1;
?>
        </table>
    </div>
</div>
<hr />
