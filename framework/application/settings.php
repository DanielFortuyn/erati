<?php
class application_settings	{
        public $set;
        public $makeDirs;
    
        public $default_event       = 'def';
        public $default_controller  = 'home';
        public $public              = 'true';
	
	public $address		    = false;
	public $keysalt;

        public $default_login_controller;
        public $default_login_event;
        public $default_user_object;
        public $default_user_level_method;
        public $default_login_test_type;
        public $default_login_level;
        public $default_template_position;
        public $appdir;
        public $default_viewType;
	
	public $globals = array();
        
        public $tplloc              = 'main';
        public $description         = 'Welkom bij fortuyn framework 3.0';
        public $title               = 'Welkom bij fortuyn framework 3.0';
        public $infoemail           = 'framework@fortuyn.nl';
        
        public $mysql_db;
        
        
	function __construct($reg)	{
		$this->address = "http://{$_SERVER['SERVER_NAME']}/";
		
		$this->parse();
		if(!empty($this->locale))	{
			setlocale(LC_ALL,$this->locale);
		}
                if($this->makeDirs)    {
		    $this->dirStructure();
                }
		if($this->default_viewType) {
		    $reg->viewType = $this->default_viewType;
		    $reg->log->entry("[settings] viewtype: {$reg->viewType}");
		}
                
   
               // if($this->maintanance)    {
                    //Override request settings
                //}
                $reg->loadState += 2;
	}
	function parse($name = '[production]')	{
	    $this->address = "http://{$_SERVER['SERVER_NAME']}/";
	    if(file_exists('settings.ini.php')){
		    $file = file('settings.ini.php');
		    $load = 0;
		    foreach($file as $line)	{
			    if(substr($line,0,1) == "[")	{
				    $load = 0;
				    $length = count($name);
				    $line = trim($line);
				    if($line == $name)	{
					    $load = 1;
				    }
				    if($line == "[globals]")	{
					    $load = 2;	
				    }
			    }


			    if($load == 1)	{
				    $pt = explode('=',$line);
				    if(count($pt) > 1)	{
					    $pt[0] = trim($pt[0]);
					    $this->$pt[0] = trim(str_replace(array_keys($this->globals),$this->globals,$pt[1]));
				    }
			    }
			    if($load == 2)	{
				    // SET GLOBALS
				    $pt = explode('=',$line);
				    if(count($pt) > 1)	{
					    $this->globals[trim($pt[0])] = trim($pt[1]);
				    }
			    }
		    }
		     return $this->set;
	    }	else	{
		throw new application_exception_init('De settingsfile is niet gevonden..',404);
		   
	    }
	}
        function dirStructure()    {
            $dirs = array(
                "_public/_log/",
                "_public/_img",
                "_public/_js",
                "_public/_css",
                "_public/_img/ico/",
                "tpl",
                "controller",
                "application",
                "model");
            foreach($dirs as $d)    {
                if(!mkdir($d,0777,true))    {
                    throw new application_exception_init("Error met maken van dirs");
                }
            }
        }
}
?>