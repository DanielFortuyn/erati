<?php
class application_request	{


	public $controller;
	public $controller_name;
	public $event;
	public $rstring;
        public $run; // Var om geen controller aan te maken ivm modelmaker
	public $r;

	function __construct($reg)	{
		$this->reg = $reg;
		$this->parseRequest();
                $this->reg->loadState += 16;
                unset($this->reg);
	}
	function parseRequest()	{
		$reg = $this->reg;
                $pt = array(0 => false, 1 => false);
		// Check if request adress is correct //
                if($reg->settings->address) {
                    if("http://{$_SERVER['HTTP_HOST']}/" != $reg->settings->address)	{
                        if("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}" != $reg->settings->address)   {
                            header("Location: ".$reg->settings->address);
                            exit();
                        }
                    }
                }   else    {
                    throw new application_exception_init("Waarde 'address' ontbreekt in settings.ini.php");
                }

		// parse url //
		if(substr($_SERVER['REQUEST_URI'],0,1) == "/")	{
                    $requestString = substr($_SERVER['REQUEST_URI'],1);
		}
		if($reg->settings->appdir)  {
                    $requestString = str_replace($reg->settings->appdir, "", $requestString);
                }
                
                $this->controller 		= $reg->settings->default_controller;
                $this->event                    = $reg->settings->default_event;
                if($requestString)  {
                    $pt = explode('/',$requestString);
                    
                    $this->controller            = (count($pt) >= 1)  ? $pt[0] : $this->controller;
                    $this->event                 = (count($pt) > 1) ? $pt[1] : $this->event;
                    
                   
  
                    $this->rstring			= $requestString;
                    $x = 0;
                    
                    for($i=2;$i<count($pt)-1;$i = $i + 2)	{
                            $n = $i + 1;
                            if($pt[$n] != '' && $pt[$i] != '')	{
                                    if($pt[$i] == "_log" && $pt[$n] == "out")	{
                                            $this->logout();
                                    }
                                    if(count(explode("-",trim(urldecode($pt[$n])))) > 1)	{
                                            $this->r[trim($pt[$i])] = explode("-",trim(urldecode($pt[$n])));
                                            $this->r[$x] = array($pt[$i] , $pt[$n]);
                                    } else	{
                                            $this->r[trim($pt[$i])] = trim(urldecode($pt[$n]));
                                            $this->r[$x] = array($pt[$i] , $pt[$n]);
                                    }
                            }
                            $x++;
                    }
                }
                $this->r['controller'] = $this->controller;
                $this->r['event'] = $this->event;
                
		$this->reg->r = $this->r;
		if(is_array($this->reg->session->history))   {
		    array_unshift($this->reg->session->history,$this->r);
		}
		
    
                $this->reg->controller->createController($this->controller);
                $this->reg->viewType = "application_view_{$this->reg->controller->activeController->viewType}";
                
                
	}
        
	public function logout()	{
		$this->reg->session->destroy();
	}
}
?>