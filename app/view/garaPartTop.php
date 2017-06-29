<div class="row">
    <div class="span12">
        <h1>Partecipanti</h1>
    </div>
</div>
<div class="row">
    <div class="span12">
        <p>
            <strong>C.I.G.:</strong> <?php echo stripslashes($cig); ?>&nbsp;&nbsp;&nbsp;<strong>Numero
                atto:</strong> <?php echo stripslashes($numAtto); ?></p>
        <p>
            <strong>Oggetto:</strong> <?php echo stripslashes($oggetto); ?></p>
        <p>
            <a class="btn btn-primary"
                href="?mask=gara&amp;do=cercaGara&amp;event=garaSelezionata&amp;idDaElenco=<?php echo $id;?>"><i
                class="icon-search icon-white"></i>&nbsp;Vai ai dettagli gara</a>&nbsp;&nbsp;
            <a class="btn btn-primary"
                href="?mask=gara&amp;do=elencaGareAnno&amp;anno=<?php echo $anno;?>"><i
                class="icon-backward icon-white"></i>&nbsp;Vai all'elenco gare <?php echo $anno;?></a>

        </p>
    </div>
</div>
<?php if(!is_null($outTopAg)): ?>
<div class="row">
    <div class="span12">
        <h2>Aggiudicatari</h2>
    </div>
</div>
<div class="row">
    <div class="span9">
        <?php echo $outTopAg; ?>
    </div>
    <div class="span3">
        <?php if (!array_key_exists($anno, $bloccati)) echo $outTopButton; ?>
    </div>
</div>
<?php endif; ?>
