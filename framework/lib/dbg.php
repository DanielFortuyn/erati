<?php
class lib_dbg	{
	public function __toString()	{
		echo "<pre>";
		var_dump($this);
		echo "</pre>";
		
	}
}
?>