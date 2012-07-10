<?php
class application_register	{
	public      $post;
	public      $settings;
	public      $session;
        public      $security;
	public      $request;
	public      $error;
	public      $log;
	public      $lib = array();
	public      $view;
	public      $controller;
	public      $mysql;
        public      $start;
        public      $end;
        public      $diff;
	public	    $postgresql;
        
        public $viewType = 'application_view_xhtml';
	
        public $troep;
	public $data;
	public $loadState; 
        public $r;
	
        static public function getInstance()	{
                static $instance;
		if(!isset($instance))	{
			$instance = new self();
                        $instance->run();
                }
		return $instance;
	}
        
	function run()	{  
            try	{
                $this->initialize();
                $this->initResources();
		$this->execute();
                $this->end();
                
            }   catch(application_exception_init $e)	{
		$e->loadstate = $this->loadState;
 		$e->initError();
            }
	}
	
	
	private function initialize()   {
            $this->start = time()+microtime();
            session_start();
        }
	function initResources()	{
	    $this->log              = new application_log($this);
	    $this->controller	    = new application_controller($this);
	    $this->settings	    = new application_settings($this);                
	    $this->session	    = new application_session($this);
	    $this->request	    = new application_request($this);
	    $this->security         = new application_security($this);
	    $this->mysql	    = new application_mysql($this);
	    $this->postgresql	    = new application_postgresql($this);

	    $this->view		    = new $this->viewType($this);
	    $this->post		    = new application_post($this);   
	}
	private function execute()  {
	    if($this->security->preRequest())   {
		$this->controller->addEvent($this->request->controller,$this->request->event);
	    }
	    if(method_exists($this->view, 'addDefaultEvents'))  {
		$this->view->addDefaultEvents();
	    }
            $this->controller->callEvents();
            $this->view->dispatch();
	}
	private function end()	{
	    $this->end = time()+microtime();
	    $this->diff = $this->end - $this->start;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	function lib($name)	{
		$name = "lib_" . $name;
		try	{
			if(array_key_exists($name,$this->lib))	{
				if($this->lib[$name] instanceof $name)	{
					return $this->lib[$name];
				}	else	{
					if(class_exists($name))	{
						$lib = new $name();
						$lib->reg = $this;
						$this->lib[$name] = $lib;
						return $lib;		
					}	else	{
						throw new application_exception_init("Aangevraagde library classe niet gevonden: ${name}","Lib");
					}
				}
			} else	{
				if(class_exists($name))	{
					$lib = new $name();
					$lib->reg = $this;
					$this->lib[$name] = $lib;
					return $lib;
				}	else	{
					throw new application_exception_init("Aangevraagde library classe niet gevonden: ${name}","Lib");
				}
			}
		}	catch	 (application_exception_init $e)	{
			$e->libError();
		}
	}
	function __set($var,$val)	{
                echo "Error crappy toegewezen val {$var} : {$val}";
                debug_print_backtrace();
		$this->$var = $val;
	}
	function __get($var)	{
                echo "Error crappy aangeroepen val {$var} : {$val} <pre>";
                debug_print_backtrace();
                echo "</pre>";
		return $this->$var;
	}
        public function framework_file_exists($file) {
             if(file_exists($file)) {
                 return true;
             }  else    {
                foreach(explode(PATH_SEPARATOR,  get_include_path()) as $path)  {
                    $fp = $path . DIRECTORY_SEPARATOR . $file;
                    if(file_exists($fp))    {
                        return $fp;
                    }
                }
                
             }
             return false;
        }
}
?>