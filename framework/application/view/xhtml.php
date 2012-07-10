<?php
class application_view_xhtml extends application_view	{
    
        private $css;
	private $script;
        private $endscript;
	private $title;
	private $meta;
	private $stdtplpos;
	private $description;
	private $keywords;
	private $HtmlComment;
	private $body;
	private $bodyOnLoad;
	private $bodyClass;
    
	function __construct($reg)	{
		parent::__construct($reg);
		$this->setLayout("layout");
                $this->setStdTplPos($reg->settings->tplloc);
		$this->setDescription($reg->settings->description);
		$this->setTitle($reg->settings->title);
	}
        /*
	* setKeywords: zet uit een array de meta keywords voor een pagina.
	* @param array (keywords)
	*/
	
	public function setKeywords($array)	{
		unset($this->keywords);
		if(count($array) > 0)	{
			foreach($array  as $k)	{
				$this->keywords[] = $k;
			}
		}
	}
	
	/*
	* setStdTplLoc
	* geeft voor deze view de standaard positie voor de templates aan. 
	* @param string position
	*/
	
	public function setStdTplPos($position)	{
		$this->stdtplpos = $position;
	}
	public function setTitle($val)	{
 		$this->title = $val;
	}
	
	/*
	* setLayout hiermee kan de layout van de view worden bepaalt
	* 1. param
	* 2. default template in de default/tpl dir
	*
	* @param string $name (filename) zonder .php dat wordt automatisch toegevoegd. 
	*/
	public function getLayout() {
            return $this->layout;
        }
	public function setLayout($name)	{
		$layout = "./tpl/" . $name . ".php";
		if(file_exists($layout))	{
			$this->layout = array('file' => $layout,'name' => $name);
		} else	{
                    $fp = $this->reg->framework_file_exists("tpl/layout.php");
                    if($fp)	{
                            $this->layout = array('file' => $fp,'name' => 'default');
                    }	else	{
                            throw new application_exception_init("Layout niet gevonden!");
                    }
		}
	}
	public function setDescription($string)	{
		$this->description = $string;
	}
	
	/*
	* Retourneerd de standaard template positie voor deze view
	*/
	
	public function getStdTplPos()	{
		return $this->stdtplpos;
	}
	public function getTitle()	{
		return $this->title;
	}
	public function getKeywords()	{
		return $this->keywords;
	}
	public function getDescription()	{
		return $this->description;
	}
        public function getDbg()    {
            return $this->dbg;
        }
	
	/*
	* Zoekt door de array met templates naar degene met de juiste positie en laadt die. Deze functie wordt gecalled in de layout met de positie als parameter
	*
	* @param string position (positie vd template)
	*/
		
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
					$this->attachShortcuts($v);
					$v->dispatch();
					$this->detachShortcuts($v);
				}
			}
		}
	}
        /*
         * Public function place remote script
         * function provides remote javascript calls
         */
        public function placeRemoteScript() {
            if($this->rscript)  {
                foreach($this->rscript as $v) {
                    ?><script type="text/javascript" src="<?=$v?>"></script><?php
                }
            }
        }
	public function placeHead()	{
		echo "<head>";
                $this->reg = application_register::getInstance();
		$this->placeRemoteScript();
                $this->placeBase();
                $this->placeScript();
                $this->placeMeta();
		$this->placeCss();
		$this->placeIcon();
		$this->placeTitle();
		unset ($this->reg);
		echo "</head>";
	}
        private function placeIcon()    {
            if(file_exists($this->reg->settings->basedir . "/favicon.ico"))   {
                echo "<link rel='shortcut icon' type='image/x-icon' href='./favicon.ico' />";
            }
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
				if(file_exists($this->reg->settings->basedir . $v))	{
					?><script type="text/javascript" src="<?=$v?>"></script><?php
				} else	{
					throw new application_exception_init("Request naar niet bestaand script. " . $this->reg->settings->basedir . ${v},1001);
				}
			}
		}
	}
	private function placeMeta()	{
		$kstr = "";
		if(count($this->meta) > 0)	{
			foreach($this->meta as $k => $v)	{
				?><meta name="<?=$k?>" content="<?=$v?>" /><?php
			}
		}
		if($this->getDescription() != "")	{
			?><meta name="description" content="<?=$this->getDescription()?>" /><?php
		}
		if(count($this->getKeywords()) > 0)	{
			foreach($this->getKeywords() as $k)	{
				$kstr .= $k . ",";
			}
			$kstr = substr($kstr,0,-1);
		
			?><meta name="keywords" content="<?=$kstr?>" /><?php
		}
	}
	private function placeTitle()	{
		?><title><?=$this->getTitle()?></title><?php
	}
	protected function placeDoctype()	{
		?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><?php
	}


	private function placeEndScript()	{
		if(count($this->endscript) > 0)	{
			foreach($this->endscript as $k => $v)	{
				?><script type="text/javascript" src="<?=$v?>"></script><?php
			}
		}
	}
	
	public function placeHtmlComment($loc = 'bottom')	{
		if(count($this->HtmlComment) > 0)	{
			foreach($this->HtmlComment as $h)	{
				if($h['loc'] == $loc)	{
					echo "<!-- " .$h['comment']. " -->\r\n";
				}
			}
		}
	}
        public function placeError()    {
            echo "<div id='error'>";
                  if($this->errors)	{
			foreach($this->errors as $e)		{
				echo $e . "<br />";
                                
			}		
		  }
            echo "</div>";
        }
       
        public function placeComment	()	{
		echo "<div id='comment'>";
                if($this->comments)	{
			foreach($this->comments as $c)		{
				echo $c . "<br />\r\n";
			}        
		}
                echo "</div>";
	}
        
        public function placeSuccess ()	{
                echo "<div id='success'>";
		if($this->success)	{
                    foreach($this->success as $c)		{
                        echo $c . "<br />\r\n";
                    }
		}
                echo "</div>";
	}
	
        public function placeNotifications()    {
            
            $this->placeError();
            $this->placeSuccess();
            $this->placeComment();
        }
        
	public function niceAddTpl($file,$event)	{
		if(file_exists($file))	{
			$this->addTpl($file,$event);
		}
	}
	public function addCss($src = '',$dir = false,$silent = false)	{
		$dir = ($dir) ? $dir : '/_public/_css/';
                if($src)    {
                    if(substr($src,-4,4) == ".css")	{
                            $this->addFile('css',$dir,$src,$silent);
                    } else	{
                            $this->addFile('css',$dir,$src . ".css",$silent);
                    }
                }   else    {
		    $reg = application_register::getInstance();
                    $this->addFile('css',$dir . $reg->controller->activeController->whoAmI() . '/',$reg->controller->activeEvent['name'] . ".css");
                }
		
	}
        public function addCssSilent($src = '',$dir = '/_public/_css/')	{
            if(file_exists(substr($dir,1) . $src . ".css"))    {
                $this->addCss($src,$dir);
            } 
        }
	public function addJs($src = '',$dir = false, $silent = false)	{
	    $this->addScript($src, $dir, $silent);
	}
	public function addScript($src = '',$dir = false , $silent = false)	{
		$dir = ($dir) ? $dir : "/_public/_js/";
		if($src)    {
                    if(substr($src,-3,3) == ".js")	{
                    	$this->addFile('script',$dir,$src,$silent);
                    } else	{
                    	$this->addFile('script',$dir,$src . ".js",$silent);
                    }
                }   else    {
		    $reg = application_register::getInstance();
                    $this->addFile('script',$dir . $reg->controller->activeController->WhoAmI() . '/',$reg->controller->activeEvent['name'] . ".js",$silent);
                }
	}
        public function addRemoteScript($src)	{
		if($src)    {
                    $this->rscript[] = $src;
                }
	}
	public function addEndScript($src,$dir = "/_public/_js/")	{
		if(substr($src,-3,3) == ".js")	{
			$this->addFile('endscript',$dir,$src);
		} else	{
			$this->addFile('endscript',"_public/_js/",$src . ".js");
		}
	}
	public function addHtmlComment($comment,$loc = 'bottom')	{
		$this->HtmlComment[] = array("comment" => $comment,"loc" => $loc);
	}
	private function addFile($container,$dir,$name,$silent = false)	{
                if(substr($dir,0,1) == '/')  {
                    $tdir = substr($dir,1);
                }
                if(file_exists($tdir . $name))	{
                    $array = $this->$container;
                    $array[] = $dir . $name;
                    $this->$container = $array;
                }   else    {
		    if(!$silent)    {
			throw new application_exception_init("Request naar niet bestaand bestand: " . $this->reg->settings->basedir . ${dir} . $name,1001);
		    }
                }
	}
	
	public function addMeta($type,$val)	{
		$this->meta[$type] = $val;
	}
        public function addTplObj(application_template $tpl)	{
		$this->tpl[$tpl->hash] = $tpl;
	}
	public function addSublayout(application_sublayout $subview)	{
		$this->subview[] = $subview;
	}
	public function addTpl($file,$position)	{
		$this->tpl[] = new application_template($file,$position); 
	}
	public function addKeywords($array)	{
		if(count($array) > 0)	{
			foreach($array  as $k)	{
				$this->keywords[] = $k;
			}
		}
	}
        public function addOnLoad($str)	{
		$this->bodyOnLoad = $str;
	}
	public function placeBody()	{
		$b = "<body ";
		$b .= ($this->bodyOnLoad != "") ? "onload ='".$this->bodyOnLoad."'" : "";
		$b .= ($this->bodyClass != "") ? "class ='".$this->bodyClass."'" : "";
		$b .= ">";
		echo $b;
	}
	public function placeEndBody()	{
			echo "</body>";
	}
        
        public function attachTemplate($controller,$event)    {
		$file = array();
		$file[0] = "tpl/" . substr(str_replace("_","/",get_class($controller)),11) . "/" . $event['name'] . ".php";
		$file[1] = "tpl/" . substr(str_replace("_","/",get_class($controller)),11) . "/{$event['name']}/{$event['position']}/.php";
		
		// Template aanmaken //
                // Template hangt aan de controller.. dat is wel vreemd..  misschien moet er een tpl code aan een event meegegeven worden bij het attachen aan de view.
		foreach($file as $f)	{
			if(file_exists($f))	{
				$this->addTplObj(new application_template($f));
				$controller->tpl = $this->tpl[$event['tplHash']];
				break;
			}
		}

		// Caching //
                /*
		if(is_a($this->aController->tpl,"application_template"))	{
			$this->aController->tpl->reg = $this->reg;
			if($this->aController->tpl->cache())	{
				// Gecachete template aan de view hangen.
				$this->reg->view->addTplObj($this->aController->tpl);
				// Kijken of er een methode is die instructies voor de cache versie bevat
				$cache_method = $this->aEvent . "_cache";
				if(method_exists($this->aController,$cache_method))	{
					$this->aController->$cache_method();
				}
				$this->reg->view->addMeta("comment","cache");
				$this->reg->view->setTitle("cache");
				return true;
			}
			$this->reg->view->addMeta("comment","nocache");
		}
                 * 
                 */
                return $controller;
        }
        
        public function handleEventResponse($event) {
		$eventResponse = $event['response'];
		if($eventResponse !== null)  {
		    if(is_object($eventResponse))	{
			    if($eventResponse instanceof application_view || $eventResponse instanceof application_view)	{
				    // View geretourneerd, kill de oude view en start een nieuwe //
				    //TODO dit moeten we eens bekijken of dat zo wel werkt eigenlijk.. ik ben bang dat we dit gewoon moeten verwijderen...
				    $this->reg->view = $eventResponse;
			    }	elseif($eventResponse instanceof application_sublayout)	{
				    // Sublayout toevoegen //
				    // Eigenlijk moeten we dit voor netheid vaker gebruiken misschien in _init een sublayout toevoegen anderszijds is sublayout niets anders dan een require met twee place obj calls
				    $this->addSublayout($eventResponse);
			    }	elseif($eventResponse instanceof application_template)	{
				    // Template toevoegen //
				    $this->addTplObj($eventResponse);
			    }	else	{
				    throw new application_exception_controller("Onbekend object geretourneerd.");
			    }
		    }	elseif($eventResponse == "private")	{
			    //todo nog nooit meegemaakt.. optie uit ver verleden.
			    throw new application_exception_controller("Geen geldige methode gecalled.");
		    }	elseif($eventResponse === false)	{
			    // Toegevoegd template verwijderen.
			    //todo kan evt ook weg, als een tpl niet bestaat wordt hij simpelweg niet toegevoegd.. resulteert wel in mogelijke onduidelijkheid
			    // methode bestaat overigens al niet meer.. wel netjes voor de 
			    //$this->detachTemplate($this->reg->controller->activeEvent);
			    
		    }	else	{
			    throw new application_exception_controller("Geen geldige waarde geretourneerd door de methode.");
		    }
		}
	}
        public function dispatch() {	
            parent::dispatch();
            $this->placeHeaders();
            $this->addCss($this->layout['name'],false,true);
            $this->addJs($this->layout['name'],false,true);
	    require $this->layout['file'];
            if(count($this->dbg) > 0)    {
                echo "<pre>";
                foreach($this->dbg as $d)   {
                    print_r($d);
                    echo "<hr size='1'>";
                }
                echo "</pre>";
            }
        }
}
?>