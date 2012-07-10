<?php
abstract class application_view_parent	{
	public function __construct($reg)	{
		$this->reg = $reg;
	}
	public function setLayout($name)	{
		$layout = "tpl/" . $name . ".php";
		if(file_exists($layout))	{
			$this->addCss($name);
			$this->layout = $layout;
		} else	{
			$this->reg->error->throwError("Layout niet gevonden","layout");
		}
	}
	public function addTplObj(application_template $tpl)	{
		$this->tpl[] = $tpl;
	}
	public function addSubview(application_view_subview $subview)	{
		$this->subview[] = $subview;
	}
	public function addTpl($file,$position)	{
		$this->tpl[] = new application_template($file,$position); 
	}
	public function niceAddTpl($file,$position)	{
		if(file_exists($file))	{
			$this->addTpl($file,$position);
		}
	}
	
	public function placeObj($position)	{
		if(count($this->subview))	{
			foreach($this->subview as $k => $v)	{
				if($v->position == $position)	{
					$v->dispatch();
				}
			}
		}
		if(count($this->tpl) > 0)	{	
			foreach($this->tpl as $k => $v)	{			
				if($v->position == $position)	{
					
					$v->dispatch();
				}
			}
		}
	}
	
	public function placeHead()	{
		echo "<head>";
		$this->placeBase();
		$this->placeCss();
		$this->placeScript();
		$this->placeTitle();
		$this->placeMeta();
		echo "</head>";
	}

	private function placeBase()	{
		echo "<base href=\"" . $this->reg->settings->address ."\"  />";
	}
	private function placeCss()	{
		if(count($this->css) > 0)		{	
			foreach($this->css as $k => $v)	{
				echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $v . "\" />";
			}
		}
		
	}
	private function placeScript()	{
		if(count($this->script) > 0)	{
			foreach($this->script as $k => $v)	{
				?><script language="javascript" src="<?=$v?>"></script><?php
			}
		}
	}
	private function placeMeta()	{
		if(count($this->meta) > 0)	{
			foreach($this->meta as $k => $v)	{
				?><meta name="<?=$k?>" content="<?=$v?>" /><?php
			}
		}
	}
	private function placeTitle()	{
		?><title><?=$this->getTitle()?></title><?php
	}
	private function placeDoctype()	{
		?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><?php
	}
	
	public function getTitle()	{
		return $this->title;
	}
	public function setTitle($val)	{
		$this->title = $val;
	}
		
	public function addCss($filename)	{
		if(substr($filename,-4,4) == ".css")	{
			$this->css[] = "_public/_css/" . $filename;
		} else	{
			$this->css[] = "_public/_css/" . $filename . ".css";
		}
		
	}
	public function addScript($src)	{
		$this->script[] = $src;
	}
	public function addMeta($type,$val)	{
		$this->meta[$type] = $val;
	}
	
	public function dispatch()	{
		$reg = $this->reg;
		eval("?>" . file_get_contents($this->layout));	
	}
}
?>