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

<div class="row">
    <div class="span12">
        <h1><?php echo $messElimina; ?></h1>
        <?php echo $errorElimina; ?>
    </div>
</div>

<div class="row">
    <div class="span6">
        <a class="btn btn-large btn-primary"
            href="./?mask=gara&amp;do=elencaGareAnno&amp;anno=<?php echo $anno;?>"><i
            class="icon-arrow-left icon-white"></i>&nbsp;Torna all'elenco gare <?php echo $anno;?></a>
    </div>
    <div class="span6">
        <a class="btn btn-large" href="./"><i class="icon-home"></i>&nbsp;Torna
            alla home page</a>
    </div>

</div>
