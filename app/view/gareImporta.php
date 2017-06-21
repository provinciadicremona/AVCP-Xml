<div class="row">
    <div class="span12">
        <div class="well well-large">
            <div class="page-header">   
                <h1>Riepilogo importazione</h1>
                <p>Le gare eventualmente non importate perchè non valide possono essere comunque inserite manualmente</p>
            </div>
            <p><span class="label label-info">Numero gare presenti nel file:</span> <?php echo $numGareFile; ?></p>
            <p><span class="label label-success">Gare importate:</span> <?php echo $numOk; ?></p>
            <?php echo $riepilogo; ?>
            <p><span class="label label-important">Errori nei campi obbligatori:</span> <?php echo $numErrori;?></p>
            <textarea style="width: 560px; height: 125px;"><?php echo $logImport;?></textarea>
            <hr />
            <p><a href="./" class="btn btn-large">Torna alla Home</a>&nbsp;&nbsp;&nbsp;<a href="?mask=importa&amp;do=gare" class="btn btn-large btn-primary">Importa un'altro file &raquo;</a></p>
        </div>
    </div>
</div>
