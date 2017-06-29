<?php

function dataIta($data) {
    if (empty($data) || $data == '0000-00-00') {
        return "n.d.";
    }
    list ( $anno, $mese, $giorno ) = preg_split('/-/', $data);
    $dataNew = $giorno . '-' . $mese . '-' . $anno;
    return $dataNew;
}
?>
<div class="row">
    <div class="span10 offset1">
        <h1>Prosegui il lavoro sulla gara</h1>
        <p>
            <strong>Oggetto: </strong> <?php echo stripslashes($oggetto);?><br />
            <strong>Anno: </strong> <?php echo stripslashes($anno);?>&nbsp;-&nbsp;<strong>C.I.G.:
            </strong> <?php echo stripslashes($cig);?> <br /> <strong>Scelta
                contraente: </strong> <?php echo stripslashes($sceltaContraente);?><br />
            <strong>Numero atto: </strong> <?php echo stripslashes($numAtto);?><br />
            <strong>Inizio: </strong> <?php echo stripslashes(dataIta($dataInizio));?>&nbsp;-&nbsp;
            <strong>Fine: </strong> <?php echo stripslashes(dataIta($dataUltimazione));?><br />
            <strong>Somme aggiudicate: </strong> <?php echo stripslashes($importoAggiudicazione);?>&nbsp;-&nbsp;
            <strong>Somme liquidate: </strong> <?php echo stripslashes($importoSommeLiquidate);?>
        </p>
        <p>
<?php
if (array_key_exists($anno, $bloccati)) :
?>
          <a class="btn btn-large disabled" href="#"><i class="icon-pencil"></i>&nbsp;Modifica</a>&nbsp;












<?php

    else :
?>
            <a class="btn btn-large btn-warning"
                href="?mask=gara&amp;id=<?php echo $id;?>"><i
                class="icon-pencil icon-white"></i>&nbsp;Modifica</a>&nbsp;
                 <?php endif;?>
                <a class="btn btn-large btn-primary"
                href="?mask=gara&amp;do=partecipantiGara&amp;id=<?php echo $id;?>"><i
                class="icon-user icon-white"></i>&nbsp;Partecipanti</a>&nbsp; <a
                class="btn btn-large btn-primary"
                href="?mask=gara&amp;do=elencaGareAnno&amp;anno=<?php echo $anno;?>"><i
                class="icon-backward icon-white"></i>&nbsp;Elenco <?php echo $anno;?></a>

<?php
        if (array_key_exists($anno, $bloccati)) :
?>
    <a class="btn btn-large disabled pull-right" href="#"><i
                class="icon-trash"></i>&nbsp;Elimina</a>





<?php

        else :
?>

            <a class="btn btn-large btn-danger pull-right" href="#eliminaModal"
                data-toggle="modal"><i class="icon-trash icon-white"></i>&nbsp;Elimina</a>





<?php

endif;
?>
        </p>
        <hr />


    </div>
</div>
<div id="eliminaModal" class="modal hide fade" tabindex="-1"
    role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
            aria-hidden="true">×</button>
        <h3 id="myModalLabel">Conferma Cancellazione</h3>
    </div>
    <div class="modal-body">
        <p>Sei sicuro di voler eliminare questa gara?</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">No</button>
        <a class="btn btn-danger"
            href="?mask=gara&amp;do=eliminaGara&amp;id=<?php echo $id;?>">Sì,
            eliminala</a>
    </div>
</div>
