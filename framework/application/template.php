<?php
class application_template	{
	
	public $position;
	public $file;
	public $data;
	public $cache = false;
	public $cachefile;
	public $reg;
	public $loc;
	public $hash;
	public $event;
	
	function __construct($file,$event = false)	{
		    if(!$event) {
				$reg = application_register::getInstance();
				$event = $reg->controller->activeEvent;
		    }
		    $this->event	= $event;
		    $this->position = $event['position'];
		    $this->hash		= $event['tplHash'];
		    $this->file		= $file;
	}
	public function dispatch()	{
		if(substr($this->file,-5,5) != "cache")	{
			if(count($this->data) > 0)	{
				foreach($this->data as $k => $v)	{
					$$k = $v;
				}
			}	
			if($this->cache)	{	ob_start();		}	
			
			require $this->file;
			
			if($this->cache)	{
				$content = ob_get_contents();
				file_put_contents($this->cachefile,$content);
				ob_end_flush();
			}
		}	else	{
		    if(stream_resolve_include_path($this->file)){
			require $this->file;
		    }	else	{
			throw new application_exception_init("Template not found: $file",404);
		    }
		}
	}
	/*
	* Automatisch een javascript filetje toevoegen op basis van de event naam.
	*
	*
	*/

	public function setVar($var,$val = '')	{
            if(is_array($var) && !$val)  {
                foreach($var as $k => $v)   {
                    $this->data[$k] = $v;
                }
            }   else    {
                if($val)    {
                    $this->data[$var] = $val;
                }
            }
	}
	public function cache()	{	
		$this->cachefile = "cache/" . md5($this->file . "/" . $this->position) . ".cache";
		if(file_exists($this->cachefile))	{
			if(filemtime($this->cachefile) > (time() - $this->reg->settings->cachetime))	{	
				$this->file = $this->cachefile;
				return true;
			}	
		}
		return false;
	}
}
?>