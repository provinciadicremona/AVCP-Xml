<div class="row">
	<div class="span12">
		<div class="well well-large">
			<div class="page-header">	
				<h1>Riepilogo importazione</h1>
				<p>Le ditte eventualmente non importate perchè non valide possono essere comunque inserite manualmente</p>
			</div>
			<p><span class="label label-info">Numero ditte presenti nel file:</span> <?php echo $numGareFile; ?></p>
			<p><span class="label label-success">Ditte importate:</span> <?php echo $numOk; ?></p>
			<?php echo $riepilogo; ?>
			<p><span class="label label-important">Errori nei campi obbligatori:</span> <?php echo $numErrori;?></p>
			<hr />
			<p><a href="./" class="btn btn-large">Torna alla Home</a>&nbsp;&nbsp;&nbsp;<a href="?mask=importa&amp;do=ditte" class="btn btn-large btn-primary">Importa un'altro file &raquo;</a></p>
		</div>
	</div>
</div>