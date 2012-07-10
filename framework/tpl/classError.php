<html>
<head>
	<title>Error: Class niet gevonden.</title>
</head>
<style>
<?php
require "_public/_css/error.css";
?>
</style>
<body>
	<h1>Fatal error: Class niet gevonden code: 404</h1>
	<h3>Class <?=$classname?> </h3>
	<pre><?php
	print_r($class);
	debug_print_backtrace();
	?>
	</pre>
	<hr size='1' />
	<p>Wanneer u dit scherm onverwacht te zien krijgt, kunt u het best contact opnemen met de beheerder van de website. U kunt contact met hem opnemen via fwerror@fortuyn.nl Het is het best om zoveel mogelijk informatie uit dit scherm door te geven. De melding zelf is erg belangrijk, maar ook de controller en het event, wanneer deze op de pagina vermeld zijn. U kunt ook een schermafdruk maken en deze opsturen.</p>
	<hr size='1' />
	<h5>(c) Daniel Fortuyn <?=date('Y')?> framework 2.6</h5>
</body>
</html>
<?php
/*
<table>
		<tr><th style='width:200px;'>Indicator</th><th style='width:600px;'>Value</th></tr>
		<tr><td>&nbsp;</td></tr>
		<?php if($this->sql != "")	{ ?><tr><td>SQL: </td><td><?=$this->sql?></td></tr><?php } ?>
		<?php if($this->event != "")	{ ?><tr><td>Event: </td><td><?=$this->event?></td></tr><?php } ?>
		<?php if($this->controller != "")	{ ?><tr><td>Controller: </td><td><?=$this->controller?></td></tr><?php } ?>
		<?php if(isset($this->eventNo))	{ ?><tr><td>Eventnr: </td><td><?=$this->eventNo?></td></tr><?php } ?>
		<tr>
			<td>LoadState: </td>
                        <td><?php if($this->loadstate != 10) {echo "<span class='loadStateR'>Not Complete: ".decbin($this->loadstate) . " " . $this->loadstate."</span>";} else { echo "<span class='loadStateG'>Complete: ".$this->loadstate."</span>";}?></td>
		</tr>
		<tr>
			<td valign='top' ><a onClick="document.getElementById('stack').style.display = 'inline'">Stacktrace:</a></td>
			<td style='display:none;' id='stack'><?=$this->stacktrace()?></td>
		</tr>
	</table>
 * 
 */
?>