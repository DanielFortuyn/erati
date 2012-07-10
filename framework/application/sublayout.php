<?php
class application_sublayout 	{
	
	public $subview;
	private $data;
	public $layout;
	public $position;
	public $reg;
	public $base;
	
	function __construct($file,$position)	{
		$reg = application_register::getInstance();
		if(substr($file,-4,4) == ".php")	{
			$file = "tpl/" . $file;
		}	else	{
			$file = "tpl/" . $file . ".php";
		}
		if(file_exists($file))	{
			$this->position = $position;
			$this->layout	= $file;
			$this->setVar('reg',$reg);
		}	else	{
			throw new application_exception_init("Sublayout not found: $file");	
			die();
		}
	}
	public function setVar($var,$val)	{
		$this->data[$var] = $val;
	}
	public function placeObj($position)	{
		$reg = application_register::getInstance();
		$reg->view->placeObj($position);
	}
	
	public function dispatch()	{
		$reg = $this->reg;
		if(count($this->data))	{
			foreach($this->data as $k => $v)	{
				$$k = $v;
			}
		}
		eval("?>" . file_get_contents($this->layout));	
	}
}
?>