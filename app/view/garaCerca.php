<?php
$customJsScript = '
<script>
	$(function() {
		var ac_config = {
		source: "app/webServices/searchGara.php",
		select: function(event, ui){
			$("#cercaOggetto").val(ui.item.oggetto);
			$("#cig").val(ui.item.cig);
			$("#id").val(ui.item.id);
		    $("#anno").val(ui.item.anno);
			$("#oggetto").val(ui.item.oggetto);
		},
		minLength:2,
		delay: 500
		};
		$("#cercaOggetto").autocomplete(ac_config);


		$( "#dataInizio" ).datepicker({
			dateFormat: "yy-mm-dd"
		});

		$( "#dataUltimazione" ).datepicker({
			dateFormat: "yy-mm-dd"
		});
	});
</script>
';
?>
<div class="row">
	<div class="span8 offset2">
		<h1>Cerca una gara</h1>


		<form action="?mask=gara&amp;do=cercaGara&amp;event=garaSelezionata"
			method="post">
			<div class="well">
				<fieldset>
					<legend>Cerca oggetto o C.I.G.:</legend>
					<input type="text" name="cercaOggetto" id="cercaOggetto"
						class="span6" placeholder="Ricerca tramite oggetto o C.I.G." />
				</fieldset>
				<fieldset>
					<legend>Gara selezionata:</legend>
					<input type="hidden" name="id" id="id" value="" /> <label for="cig">C.I.G.</label>
					<input type="text" name="cig" id="cig"
						placeholder="Codice Identificativo Gara" value="" /> <label
						for="oggetto">Oggetto</label>
					<textarea name="oggetto" id="oggetto" class="span6"
						placeholder="Oggetto della gara"></textarea>
				</fieldset>
			</div>
			<input class="btn" type="reset" value="Pulisci" />&nbsp; <input
				class="btn btn-large btn-primary" type="submit"
				value="Seleziona gara &raquo;" />
		</form>


	</div>
</div>
