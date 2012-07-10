<?php
class application_log	{
	
	protected $filepointer;
	public    $path;
        public    $open = false;
        
	function __construct($reg)	{
            $this->path = "_public/_log/" . date('dmY') . ".log";
            $reg->loadState += 128;
	}
        function logOpen()  {
            if(!$this->filepointer = fopen($this->path,"a+"))	{
                throw new application_exception_init("Failed to open the logifle");
                $this->open = false;
            }
            $this->open = true;
        }
	function entry($string,$type = 'generic')	{
                $this->logOpen();
		if(!fwrite($this->filepointer,"[".date('d-M-Y')."][".$type."] " . $string . "\r\n"))	{
			throw new application_exception_init("Failed to write to logfile");
		}
	}
	function __destruct()	{
            if($this->filepointer)  {
		fclose($this->filepointer);
            }
	}
	function __get($var)	{
		return $this->$var;
	}
	function __set($var,$val)	{
		$this->$var = $val;
	}
}
?>