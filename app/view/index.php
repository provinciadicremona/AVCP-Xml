<?php //TODO: Aggiungere modal di conferma per avanzamento/arretramento?>
<div class="row">
    <div class="span8">
        <div class="page-header">
            <h1>
                AVCP Xml <small> - versione <?php echo $localVersion; ?></small>
            </h1>
            <p>Generatore di dataset XML per l'Autorità per la Vigilanza sui
                Contratti Pubblici - art.32 L. 190/2012</p>
            <?php if ($_SESSION['user'] == 'admin'): ?>
            <i class="icon-pencil"></i>&nbsp;<a
                href="?mask=admin&amp;do=messaggio">Imposta/Modifica il messaggio
                agli utenti</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="span4">
        <div class="well">
            <h2>Aggiornamenti</h2>
            <ul class="unstyled">
                <li><i class="icon-refresh"></i>&nbsp;<a href="?do=aggiorna">Controlla
                        se il programma è aggiornato</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="span4">
        <h2>
            Gare <a href="#gareModal"
                title="Informazioni sulla gestione delle gare" data-toggle="modal"><i
                class="icon-question-sign"></i></a>
        </h2>
        <!-- Inizio modal gare -->
        <div id="gareModal" class="modal hide fade" tabindex="-1"
            role="dialog" aria-labelledby="gareModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">×</button>
                <h3 id="gareModalLabel">Gestione gare</h3>
            </div>
            <div class="modal-body">
                <?php if ($_SESSION['user'] == 'admin'): ?>
                <h4>Funzione blocco</h4>
                <p>
                    La funzione di blocco relativa ai lotti di un anno, <strong>impedisce
                        ogni modifica</strong> a quei dati. <br /> Un anno bloccato <strong>può
                        essere sbloccato</strong> per consentire la correzione di
                    eventuali errori.
                </p>
                <p>Su di un anno bloccato può essere lanciato l'avanzamento all'anno
                    successivo.</p>
                <h4>Funzione avanzamento</h4>
                <p>La funzione di avanzamento copia nell'anno successivo (se non c'è
                    ancora lo crea) tutte le gare (coi relativi partecipanti) che
                    abbiano un importo somme liquidate inferiore all'importo somme
                    aggiudicate o che abbiano un importo di aggiudicazione pari a 0.</p>
                <p>Eventuali gare da considerarsi concluse ma che sono state
                    "avanzate" all'anno successivo, andranno eliminate manualmente da
                    quest'ultimo.</p>
                <h4>Funzione di arretramento</h4>
                <p>
                    La funzione di arretramento elimina dall'anno corrente tutti i dati
                    relativi alle gare importate con la funzione di avanzamento.<br />
                    <strong>Attenzione!</strong>&nbsp;L'arretramento cancellerà anche
                    tutte le modifiche fatte nel frattempo a queste gare, per cui, a
                    meno che non siate sicuri che gli utenti non le abbiano ancora
                    modificate, <strong>andrebbe evitato</strong>.
                </p>

                <?php else: ?>
                <p>
                    Se di fianco all'elenco gare appare il simbolo di un lucchetto (<i
                        class="icon-lock"></i>), vuol dire che l'amministratore ha
                    bloccato ogni modifica ai dati di quell'anno.
                </p>
                <?php endif;?>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Chiudi</button>
            </div>
        </div>
        <!-- / modal gare -->
        <div class="well">
            <ul class="unstyled">
                <li><i class="icon-plus"></i>&nbsp;<a href="./?mask=gara">Inserisci
                        nuova gara</a></li>
                <li><i class="icon-search"></i>&nbsp;<a
                    href="./?mask=gara&amp;do=cercaGara">Cerca gara</a></li>
                <?php echo $elencoGareLink; ?>
            </ul>
        </div>
    </div>
    <div class="span4">
        <h2>Anagrafica ditte</h2>
        <div class="well">
            <ul class="unstyled">
                <li><i class="icon-plus"></i>&nbsp;<a href="./?mask=ditta">Inserisci
                        nuova ditta</a></li>
                <li><i class="icon-search"></i>&nbsp;<a
                    href="./?mask=ditta&amp;do=cercaDitta">Cerca ditta</a></li>
                <li><i class="icon-list"></i>&nbsp;<a
                    href="?mask=ditta&do=elencaDitte">Elenco ditte</a></li>
                <li><i class="icon-wrench"></i>&nbsp;<a
                    href="?mask=ditta&do=pulisciDitte">Rimuovi ditte senza CF</a></li>
            </ul>
        </div>
    </div>
    <div class="span4">
        <h2>Importa dati</h2>
        <div class="well">
            <ul class="unstyled">
                <li><i class="icon-upload"></i>&nbsp;<a
                    href="./?mask=importa&amp;do=gare">Importa csv gare</a></li>
                <li><i class="icon-upload"></i>&nbsp;<a
                    href="./?mask=importa&amp;do=ditte">Importa csv ditte</a></li>
            </ul>
        </div>
    </div>
</div>
<br />
<div class="row">
    <div class="span4">
        <div class="well">
            <h2>
                Valida XML <a href="#validaModal" title="Aiuto validazione"
                    data-toggle="modal"><i class="icon-question-sign"></i></a>
            </h2>
            <div id="validaModal" class="modal hide fade" tabindex="-1"
                role="dialog" aria-labelledby="validaModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">×</button>
                    <h3 id="validaModalLabel">Validazione</h3>
                </div>
                <div class="modal-body">
                    <p>Sono disponibili 2 tipi di validazioni:</p>
                    <ul>
                        <li><strong>Semplice</strong>: è molto veloce e segnala gli errori</li>
                        <li><strong>Con sorgente</strong>: è più lenta ma oltre a
                            segnalare gli errori visualizza il codice XML generato</li>
                    </ul>
                    <p>
                        Per non perdere temo è meglio testare il file con la validazione
                        semplice e, <strong>in caso di errori</strong>, usare quella con
                        sorgente per localizzarli.
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Chiudi</button>
                </div>
            </div>
            <!-- / modal -->
            <ul class="unstyled">
                <?php echo $xmlVal; ?>
            </ul>
        </div>
    </div>
    <div class="span4">
        <div class="well">
            <h2>File XML</h2>
            <ul class="unstyled">
                <?php echo $xmlLink; ?>
            </ul>
        </div>
    </div>
    <div class="span4">
        <div class="well">
            <h2>
                Esporta <a href="#esportaModal"
                    title="Informazioni sull'esportazione" data-toggle="modal"><i
                    class="icon-question-sign"></i></a>
            </h2>
            <div id="esportaModal" class="modal hide fade" tabindex="-1"
                role="dialog" aria-labelledby="esportaModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">×</button>
                    <h3 id="esportaModalLabel">Esportazioni</h3>
                </div>
                <div class="modal-body">
                    <p>&Egrave; possibile esportare i dati inseriti nei file XML
                        annuali in due diversi formati.</p>
                    <ul>
                        <li><strong>HTML</strong>: genera un file html che contiene una
                            tabella che è possibile pubblicare in una pagina del proprio sito</li>
                        <li><strong>ODS</strong>: genera un foglio di calcolo in formato
                            aperto utilizzabile con Libre Office o Open Office</li>
                    </ul>
                    <p>Questi file sono pensati per un uso interno o come forma
                        complementare di pubblicità sul sito. Non adempiono all'obbligo di
                        pubblicazione dell'AVCP.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Chiudi</button>
                </div>
            </div>
            <!-- / modal -->
            <ul class="unstyled">
                <?php echo $xmlEsp; ?>
            </ul>
        </div>
    </div>
</div>
