<?php
//////////////////////////////////
//				//
//  � Daniel Fortuyn 2009	//
//  Ver  1.1			//
//  Last Edit: 31/8/09		//
//  Double singleton call       //
//   removed now single         //
//				//
//////////////////////////////////

class application_mysql	{
    
    private $databases;
    
    function __construct($reg)	{
        if($reg->settings->mysql_db)    {
            $this->initDb($reg->settings->mysql_db);
        }
        $reg->loadState += 32;
    }

    function initDb($name,$host = '',$user = '',$pass = '')	{
        $reg = application_register::getInstance();
        if($reg->settings->mysql_db)    {
            if(!is_a($this->$name,"pdo"))	{
                $reg = application_register::getInstance();

                $u = (empty($user)) ? $reg->settings->mysql_user : $user;
                $p = (empty($pass)) ? $reg->settings->mysql_pass : $pass;
                $h = (empty($host)) ? $reg->settings->mysql_host : $host;

                $reg = application_register::getInstance();
                $cstr = "mysql:dbname=" . $name . ";host=" . $h;
                try {
                    $this->$name = new pdo($cstr,$u,$p);
                    $this->$name->name = $name;
                } catch  (PDOException $e) {
                    $reg->log->entry("MySQL connect error: " . $e->getMessage());
                    die("MYSQL connect error");
                } 
                
            }
            
            return $this->$name;
        }  
        return false;
        
    }
    
    /*
     * Pseudo dynamisch database connecties creeren;
     */
    
    public function __get($name) {
        if(isset($this->databases[$name]))  {
            return $this->databases[$name];
        }
    }
    public function __set($name, $value) {
        $this->databases[$name] = $value;
    }
}
?>