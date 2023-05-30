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

$actHome = null;
$actAzioni = null;
$actContatti = null;
$actLicenza = null;
if (empty($_GET['mask'])) {
    $actHome = 'class="active" ';
} elseif ($_GET['mask'] == 'page') {
    switch ($_GET['do']){
    case 'contatti':
        $actContatti = ' class="active" ';
        break;
    case 'licenza':
        $actLicenza = ' class="active" ';
        break;
    }
} else {
    $actAzioni = ' active';
}

?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="utf-8">
<title>AVCP Xml</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description"
    content="Generatore di XML dati art. 32 L.190/2012 per comunicazione AVCP">
<meta name="author" content="Claudio Roncaglio e Gianni Bassini">

<!-- Le styles -->
<link href="css/bootstrap.css" rel="stylesheet" />
<style class="css">
html, body {
    height: 100%;
    /* The html and body elements cannot have any padding or margin. */
}
</style>
<style>
.ui-autocomplete-loading {
    background: white url('css/images/ui-anim_basic_16x16.gif') right center
        no-repeat;
}
</style>
<link href="css/bootstrap-responsive.css" rel="stylesheet" />
<link rel="stylesheet" href="js/jquery-ui.css">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <![endif]-->

<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144"
    href="ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114"
    href="ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72"
    href="ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed"
    href="ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="ico/favicon.png">
</head>
<body>
    <div id="wrap">
        <div class="navbar navbar-inverse navbar-static-top">
            <div class="navbar-inner">
                <div class="container">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse"
                        data-target=".nav-collapse">
                        <span class="icon-bar"></span> <span class="icon-bar"></span> <span
                            class="icon-bar"></span>
                    </button>
                    <a class="brand" href="./">AVCP Xml</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">

                            <li <?php echo $actHome;?>><a href="./">Home</a></li>
                            <li class="dropdown<?php echo $actAzioni;?>"><a href="#"
                                class="dropdown-toggle" data-toggle="dropdown">Azioni <b
                                    class="caret"></b></a>
                                <ul class="dropdown-menu" id="menu">
                                    <li class="nav-header">Gare</li>
                                    <li><a href="?mask=gara"><i class="icon-plus"></i>&nbsp;Inserisci
                                            nuova gara</a></li>
                                    <li><a href="?mask=gara&amp;do=cercaGara"><i
                                            class="icon-search"></i>&nbsp;Cerca gara</a></li>
                                    <li class="divider"></li>
                                    <li class="nav-header">Ditte</li>
                                    <li><a href="?mask=ditta"><i class="icon-plus"></i>&nbsp;Inserisci
                                            nuova ditta</a></li>
                                    <li><a href="?mask=ditta&amp;do=cercaDitta"><i
                                            class="icon-search"></i>&nbsp;Cerca ditta</a></li>
                                    <li><a href="?mask=ditta&amp;do=elencaDitte"><i
                                            class="icon-list"></i>&nbsp;Elenco ditte</a></li>
                                    <li class="nav-header">Importa csv</li>
                                    <li><a href="./?mask=importa&amp;do=gare"><i
                                            class="icon-upload"></i>&nbsp;Importa gare</a></li>
                                    <li><a href="./?mask=importa&amp;do=ditte"><i
                                            class="icon-upload"></i>&nbsp;Importa ditte</a></li>
                                </ul></li>

                            <li <?php echo $actContatti;?>><a
                                href="?mask=page&amp;do=contatti">Contatti</a></li>
                            <li <?php echo $actLicenza;?>><a href="?mask=page&amp;do=licenza">Licenza</a></li>
                        </ul>
                        <ul class="nav pull-right">
                            <li><a href="#" title="Utente attivo"><?php echo $_SESSION['user']?></a></li>
                            <li class="divider-vertical"></li>
                            <li><a href="?mask=signin&amp;do=logout">Esci</a></li>
                        </ul>
                    </div>
                    <!--/.nav-collapse -->
                </div>
            </div>
        </div>
        <div class="container">
            <article>
