<?php
class application_postgresql	{
    public $connections = array();
    public function __construct($reg)   {
	if($reg->settings->postgres_db)    {
            $this->initDb($reg->settings->postgres_db);
        }
        $reg->loadState += 32;
    }
    function initDb($name,$host = '',$user = '',$pass = '')	{
	$reg = application_register::getInstance();
        if($reg->settings->postgres_db)    {
            if(!is_a($this->$name,"pdo"))	{
                $reg = application_register::getInstance();
                $u = (empty($user)) ? $reg->settings->postgres_user : $user;
                $p = (empty($pass)) ? $reg->settings->postgres_pass : $pass;
                $h = (empty($host)) ? $reg->settings->postgres_host : $host;

                $reg = application_register::getInstance();
                $dsn = "pgsql:dbname={$name}";
		$dsn .= ($h) ? ";host={$host}" : "";
		
                try {
                    $this->$name = new pdo($dsn,$u,$p);
                    $this->$name->name = $name;
                } catch  (PDOException $e) {
                    $reg->log->entry("Postgresql connect error: {$dsn} " . $e->getMessage());
                    die("Postgres connect error, check settings file");
                } 
                
            }
            
            return $this->$name;
        }  
        return false;
    }
    public function __set($name, $value) {
	$this->connections[$name] = $value;
    }
    public function __get($name)    {
	return $this->connections[$name];
    }
}
?>
