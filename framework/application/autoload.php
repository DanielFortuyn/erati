<?php
class application_autoloader	{
    static function autoload($classname)    {
	$pt         = explode("_",$classname);
	$found      = false;

	$class[0] = str_replace("_",DIRECTORY_SEPARATOR,$classname) . ".php";
	$class[1] = str_replace("_",DIRECTORY_SEPARATOR,$classname) . DIRECTORY_SEPARATOR . $pt[count($pt)-1]. ".php";

	foreach(explode(PATH_SEPARATOR,  get_include_path()) as $path)  {
	    foreach($class as $k => $file)    {
		$f = $path . DIRECTORY_SEPARATOR . $file;
		$files[] = $f;
		if(file_exists($f)) {
		    $found = true;
		    require $f;
		    break 2;
		} 
	    }
	}
	if(!$found) {
	    require "tpl/classError.php";
	    die();
	}
    }
}
spl_autoload_register("application_autoloader::autoload");