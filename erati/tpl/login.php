<?php
$this->addRemoteScript("https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js");
$this->addCss('style');
$this->addJs('default');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
$this->placeHead();
?>
<body style="height:100%; width:100%;">
<div id='center'>
	<div id="top"></div>
	<div id="mid">
		<div id="content">
                    <?PHP
			//<p style="color:red; margin-top:0px;">Tijdelijk onderhoud niet mogelijk om in te loggen</p>
                        $this->placeObj('main');
                    ?>
		</div>
	</div>
	<div id='bottom'></div>
</div>
<?php
if(isset($this->page['script_footer']))	{
	echo $this->page['script_footer'];
}
?>
</body>
</html>