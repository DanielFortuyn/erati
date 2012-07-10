<?php
class application_session {
    public $history = array();
    public $num;
    public $data;
    
    public function __construct($r)   { 
	$reg->loadState += 4; 
	
        if($_SESSION['MD5'] != "")	{
            if($_SESSION['MD5'] ==  md5($_SESSION[$r->settings->default_user_object] . $r->settings->salt ))    {
		if($_SESSION[$r->settings->default_user_object] != '')	{
                	$this->{$r->settings->default_user_object} = unserialize($_SESSION[$r->settings->default_user_object]);
                }
            }	else	{
                session_destroy();
                throw new application_exception_init("Sessie bewerkt of verlopen, zowel klant als bestelling gereset.");
            }
        }	else	{
            $m = "model_" . $r->settings->mysql_db . "_" . $r->settings->default_user_object;
            //$this->{$r->settings->default_user_object} = new $m();
        }
	 
        // Clear de session voor de overwrite //
	unset($_SESSION[$r->settings->default_user_object]);
	unset($_SESSION['MD5']);
	
	foreach($_SESSION as $k => $v)  {
	    $this->{$k} = unserialize($v);
        }
	$this->num = (count($this->history) != 0) ? count($this->history) : 0; 
    }
    public function  __destruct() {
        $r = application_register::getInstance();
        foreach($this as $k => $v)  {
            $_SESSION[$k] = serialize($v);
        }
        $_SESSION['MD5'] = md5($_SESSION[$r->settings->default_user_object] . $r->settings->salt);
    }
    public function destroy()	{
        foreach($_SESSION as $k => $v)	{
            $_SESSION[$k] = null;
	}
	session_destroy();
    }
    public function __set($name,$val) {
        $this->data[$name] = $val;
    }
    public function __get($name) {
        return $this->data[$name];
    }
}

?>
