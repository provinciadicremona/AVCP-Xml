<?php if (true === $okMess):?>
<div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>Ok:</strong> il messaggio per gli utenti è stato modificato.<br />
	Se il tuo lavoro qui è finito, <a href="./">torna all'homepage</a>.
</div>
<?php endif; ?>
<div class="row">
	<div class="span8 offset2">
		<h1>Imposta il messaggio agli utenti</h1>
		<form action="?mask=admin&amp;do=messaggio&amp;action=modifica"
			method="post">
			<div class="well">
				<label for="messaggio">Testo del messaggio</label>
				<textarea name="messaggio" id="messaggio" class="span6"><?php echo $contents; ?></textarea>
			</div>
			<input class="btn" type="reset" value="Annulla modifiche" />&nbsp; <input
				class="btn btn-large btn-primary" type="submit"
				value="Modifica &raquo;" />
		</form>
	</div>
</div>