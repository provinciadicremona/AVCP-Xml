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
        <div class="alert alert-<?php echo $messOk ? 'success' : 'error';?>">
            <button class="close" data-dismiss="alert" type="button"></button>
            <?php echo $message?>
        </div>
    </div>
</div>
<div class="row">
    <div class="span8 offset2">
        <p>
            <a href="./">&larr; Torna all'homepage</a>
        </p>
    </div>
</div>
