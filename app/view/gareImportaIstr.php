<div class="row">
	<div class="span8">
		<div class="page-header">
			<h1>Importa csv gare</h1>
			<p>Prima di caricare il file, leggere attentamente le istruzioni</p>
		</div>
		<p>Nel caso si disponga di un elenco delle gare effettuate, è
			possibile importarlo utilizzando uno dei file modello scaricabili di
			seguito:</p>
		<ul class="unstyled">
			<li><i class="icon icon-download-alt"></i>&nbsp;<a
				href="modelli/modelloLotto.csv">modelloLotto.csv</a></li>
			<li><i class="icon icon-download-alt"></i>&nbsp;<a
				href="modelli/modelloLotto.ods">modelloLotto.ods</a> - Libre / Open
				Office</li>

		</ul>
		<p></p>
		<h2>Istruzioni di compilazione:</h2>
		<p>
			Gli unici <strong>campi obbligatori</strong> del file sono: anno, cig
			e oggetto.<br /> Il <strong>campo oggetto verrà troncato al 250°
				carattere</strong> così come da specifiche AVCP.<br /> Gli altri
			campi possono essere omessi ma nel caso fossero disponibili,
			assicurarsi che rispettino queste caratteristiche:
		</p>
		<ul>
			<li><strong>sceltaContraente: </strong>utilizzare esattamente le
				diciture specificate nel documento dell'AVCP, se non si è sicuri
				della correttezza è consigliabile lasciarlo in bianco</li>
			<li><strong>dataInizio e dataUltimazione: </strong> in formato
				aaaa-mm-gg</li>
			<li><strong>importoAggiudicazione e importoSommeLiquidate: </strong>il
				programma converte i caratteri ',' in caratteri '.' come separatore
				decimale, non usare nessun carattere per le migliaia</li>
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
			<form action="?mask=importa&amp;do=gare" method="post"
				enctype="multipart/form-data">
				<input type="file" name="csv" id="csv"
					placeholder="Seleziona il file" /><br />&nbsp;<br /> <input
					type="submit" value="Importa"
					class="btn btn-primary btn-block btn-large" name="action"
					id="action" /><br />
			</form>
			<h4>Esiti della verifica</h4>
			<p>Al termine dell'importazione le gare saranno selezionabili e
				modificabili tramite programma.</p>
			<p>I record non importati potranno essere comunque corretti e
				inseriti manualmente tramite le apposite pagine o aggiunti
				importando un nuovo file csv.</p>
		</div>
	</div>
</div>
<hr />