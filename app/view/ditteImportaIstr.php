<div class="row">
    <div class="span8">
        <div class="page-header">
            <h1>Importa csv ditte</h1>
            <p>Prima di caricare il file, leggere attentamente le istruzioni</p>
        </div>
        <p>Nel caso si disponga di un elenco delle ditte in anagrafica, è
            possibile importarlo utilizzando uno dei file modello scaricabili di
            seguito:</p>
        <ul class="unstyled">
            <li><i class="icon icon-download-alt"></i>&nbsp;<a
                href="modelli/modelloDitta.csv">modelloDitta.csv</a></li>
            <li><i class="icon icon-download-alt"></i>&nbsp;<a
                href="modelli/modelloDitta.ods">modelloDitta.ods</a> - Libre / Open
                Office</li>
        </ul>
        <p></p>
        <h2>Istruzioni di compilazione:</h2>
        <p>
            I <strong>campi obbligatori</strong> del file sono: codiceFiscale e
            ragioneSociale.<br /> Il campo estero può essere omesso se la ditta
            in questione ha un codice fiscale italiano.<br />
        </p>
        <ul>
            <li><strong>estero: </strong>impostare con il valore <strong>1</strong>
                se la ditta è straniera, <strong>0</strong> o lasciare vuoto se è
                italiana</li>
            <li><strong>codiceFiscale: </strong>se la ditta è italiana verrà
                controllata la lunghezza che deve essere di <strong>11 o 16
                    caratteri</strong>.</li>
        </ul>

        <p>
            <span class="label label-important">Importante!</span> Nel caso si
            usi il file ods per Libre Office / Open Office, sarà necessario
            convertirlo in formato csv prima del caricamento.
        </p>
        <h2>Istruzioni per la conversione in formato csv</h2>
        <p>Dopo aver compilato il file, salvarlo con nome usando l'estensione
            ".csv".</p>



        <figure>
            <img src="img/esportacsv.png" alt="Schermata esportazione csv"
                width="480" height="204">
            <figcaption>Opzioni di esportazione csv di Libre Office / Open
                Office.</figcaption>
        </figure>

        <p>Al momento di scgliere le opzioni di esportazione, assicurarsi che
            corrispondano a quelle della figura, ovvero:</p>
        <ul>
            <li><strong>Tipo di carattere:</strong> Unicode (UTF-8)</li>
            <li><strong>Separatore di campo:</strong> , (virgola)</li>
            <li><strong>Separatore di testo:</strong> " (virgolette)</li>
            <li><strong>Spuntare le opzioni: </strong>
                <ul>
                    <li>Salva contenuto cella come mostrato</li>
                    <li>Virgolette su tutte le celle di testo</li>
                </ul></li>
        </ul>
        <p>Se tutto è andato a buon fine, il file csv è pronto per
            l'importazione.</p>
    </div>
    <div class="span4">
        <div class="well">
            <h3>Seleziona il file:</h3>
            <form action="?mask=importa&amp;do=ditte" method="post"
                enctype="multipart/form-data">
                <input type="file" name="csv" id="csv"
                    placeholder="Seleziona il file" /><br />&nbsp;<br /> <input
                    type="submit" value="Importa"
                    class="btn btn-primary btn-block btn-large" name="action"
                    id="action" /><br />
            </form>
            <h4>Esiti della verifica</h4>
            <p>Al termine dell'importazione le ditte saranno selezionabili e
                modificabili tramite programma.</p>
            <p>I record non importati potranno essere comunque corretti e
                inseriti manualmente tramite le apposite pagine o aggiunti
                importando un nuovo file csv.</p>
        </div>
    </div>
</div>
<hr />
