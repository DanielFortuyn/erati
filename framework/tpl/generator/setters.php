<?php
if($fields)	{
	foreach($fields as $k => $f)	{
		$setter = "set" . ucfirst($f['Field']);
		echo "\t\$m->{$setter}(\$v[0]);\r\n";
	}
}
?>