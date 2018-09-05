<?php
// Funzione che formatta i messaggi di errore nella
// validazione del file XML
function libxml_display_error($error) {
    $return = "<br/>\n";
    switch ($error->level){
    case LIBXML_ERR_WARNING:
        $return .= "<b>Warning $error->code</b>: ";
        break;
    case LIBXML_ERR_ERROR:
        $return .= "<b>Error $error->code</b>: ";
        break;
    case LIBXML_ERR_FATAL:
        $return .= "<b>Fatal Error $error->code</b>: ";
        break;
    }
    $return .= trim($error->message);
    // Formatto l'errore con un link alla linea per prism.js, plugin line-highlight
    // http://prismjs.com/plugins/line-highlight/
    $return .= " <b><a href=\"#xml." . $error->line . "\">Vedi alla linea $error->line</a></b>\n";
    return $return;
}

function libxml_display_errors() {
    $errors = libxml_get_errors();
    foreach ($errors as $error) {
        // Error 1872: The document has no document element.
        if ($error->code != 1872) {
            // Array delle linee in cui si presenta l'errore
            $errArray[] = $error->line;
            print libxml_display_error($error);
        }
    }
    libxml_clear_errors();
    return $errArray;
}

if (isset($_GET['anno'])) {
    $anno_rif = (int) $_GET['anno'];
}
// Enable user error handling
libxml_use_internal_errors(true);

require_once __DIR__ . '/testa_xml_avcp_query.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>AVCP Xml</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description"
    content="Generatore di XML dati art. 32 L.190/2012 per comunicazione AVCP">
<meta name="author" content="Claudio Roncaglio e Gianni Bassini">

<!-- Le styles -->
<link href="../../css/bootstrap.css" rel="stylesheet" />

<link href="../../js/prism/prism.css" rel="stylesheet" />

<script src="../../js/prism/prism.js"></script>


<style class="css">
html, body {
    height: 100%;
    padding-top: 20px;
    /* The html and body elements cannot have any padding or margin. */
}
</style>
<link href="../../css/bootstrap-responsive.css" rel="stylesheet" />



<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <![endif]-->

<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144"
    href="../../ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114"
    href="../../ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72"
    href="../../ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed"
    href="../../ico/apple-touch-icon-57-precomposed.png">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="span12">
                <div class="well">
                    <h1>Esito della validazione</h1>
<?php
$xml = new DOMDocument();
$xml->preserveWhiteSpace = false;
$xml->formatOutput = true;
$xml->loadXML($XML_TOT);
if ($xml->schemaValidate('./datasetAppaltiL190.xsd')) {
    echo '
    <div class="alert alert-success">
        <strong>Il documento è corretto.</strong>
        <p>Scarica il <a href="../../crea_xml_avcp_query.php?anno=' . $anno . '">file XML</a>
        da pubblicare sul sito o torna alla <a href="../../">homepage</a></p>
    </div>';
} else {
    echo '
    <div class="alert alert-error">
        <strong>Il documento contiene degli errori e non può essere validato!</strong>
        <p>Torna alla <a href="../../">homepage</a></p>
    </div>';
    $errorLine = libxml_display_errors();
    // Evidenzio le linee degli errori per prism.js, plugin line-highlight
    // http://prismjs.com/plugins/line-highlight/
    $outErrorLine = null;
    foreach ($errorLine as $el) {
        $outErrorLine .= $el . ',';
    }
}
?>
            </div>
            </div>
        </div>
        <div class="row">
            <div class="span12">
                <div class="well">
<?php
if (isset($_GET['source']) && $_GET['source'] == 'on') {
    echo '<pre id="xml"><code class="language-markup" data-line="' . (isset($outErrorLine)?$outErrorLine:'') . '">';
    echo nl2br(htmlentities($XML_TOT));
    echo '</code></pre>' . PHP_EOL;
} else {
    echo '<p>Per visualizzare anche il file XML esegui una <a href="valida_dataset.php?anno=' . $anno_rif . '&amp;source=on">validazione con sorgente</a>.</p>';
}
?>
            </div>
            </div>
        </div>
    </div>

</body>
</html>
