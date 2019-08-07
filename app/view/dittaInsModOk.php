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
    <div class="span8 offset2">
        <h1>Prosegui il lavoro sulle ditte</h1>
        <p>
            <strong>Azienda: </strong> <?php echo stripslashes($ragioneSociale);?><br />
            <strong>Codice Fiscale: </strong> <?php echo stripslashes($codiceFiscale);?> <br/>
        </p>
        <p>
            <a class="btn btn-large btn-warning" href="?mask=ditta&amp;codiceFiscale=<?php echo $codiceFiscale;?>"><i class="icon-pencil icon-white"></i>&nbsp;Modifica</a>&nbsp;
            <a class="btn btn-large btn-primary" href="?mask=ditta"><i class="icon-plus icon-white"></i>&nbsp;Nuova</a>
            <a class="btn btn-large btn-primary" href="?mask=ditta&amp;do=cercaDitta"><i class="icon-search icon-white"></i>&nbsp;Cerca</a>
            <a class="btn btn-large btn-primary" href="?mask=ditta&amp;do=elencaDitte"><i class="icon-list icon-white"></i>&nbsp;Vai ad elenco</a>
        </p>
    </div>
</div>
