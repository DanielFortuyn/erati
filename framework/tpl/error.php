<html>
<head>
	<title>Error op de pagina code: <?=$this->getCode()?></title>
</head>
<style>
body	{
	font-family:verdana;
}
h1	{
	background-color:red;
	color:black;
	font-weight:normal;
	border:3px solid #999;
	font-size:22px;
	padding:5px;
}
h3	{
	font-size:18px;
	padding-left:25px;
}
table	{
	font-family:verdana;
	font-size:14px;
}
.trace td {
	font-family: arial; font-size: 11px;
}
span.loadstateR	{
	text-size:24px;
	color:red;
	font-weight:bold;
}
span.loadStateG	{
	text-size:18px;
	color:green;
	font-weight:bold;
}
p	{
	font-family:verdana;
	font-size:11px;
	line-height:1.5;
}
</style>
<body>
	<h1>Fatal error: <?=$this->getMessage()?> code: <?=$this->getCode()?></h1>
	<h3> Line: <?=$this->getLine()?> in file: <?=$this->getFile()?></h3>
	<hr size='1' />
	<p>Wanneer u dit scherm onverwacht te zien krijgt, kunt u het best contact opnemen met de beheerder van de website. U kunt contact met hem opnemen via <?=$this->reg->settings->infoemail?> Het is het best om zoveel mogelijk informatie uit dit scherm door te geven. De melding zelf is erg belangrijk, maar ook de controller en het event, wanneer deze op de pagina vermeld zijn. U kunt ook een schermafdruk maken en deze opsturen.</p>
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
	<hr size='1' />
	<h5>(c) Daniel Fortuyn <?=date('Y')?> framework 2.6</h5>
	<pre>
	    <?php
	    var_dump($_SERVER);
	    ?>
	</pre>
</body>

</html>