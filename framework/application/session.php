<?php
class application_session	{
    public $history;
    public $num;
    public $data;
    
    public function __construct($reg)   {
        
        $this->history = (isset($_SESSION['history'])) ? unserialize($_SESSION['history']) : array();
        $this->num = (count($this->history) != 0) ? count($this->history) : 0; 
        if($_SESSION)   {
            foreach($_SESSION as $k => $v)  {
                $this->{$k} = unserialize($v);
            }  
        }
        $reg->loadState += 4;
       
    }
    public function __destruct()    {
        foreach($this as $k => $v)  {
            $_SESSION[$k] = serialize($v);
        }
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