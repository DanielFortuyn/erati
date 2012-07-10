<?php
//////////////////////////////////////////////////
//                                              //
//   Hoofd controller 2.00                      //
//   14-06-2012                                 //
//   properties gemaakt voor actieve event &    //
//   controller                                 //
//                                              //
//////////////////////////////////////////////////
class application_controller	{
	public $reg;
	private $events = array();
	private $controllers = array();
	
	public $activeController;
	public $activeEvent;

	public function __construct($reg)		{
		$this->reg = $reg;
                $this->reg->loadState += 1;
	}
	
	/*
	* Voegt een event toe aan de lijst met uit te voeren events.
	* @param controller string  / application_interface_controller
	* @param event string (optional) <-- kiest de methode in de controller.
	* @param level nog niet in gebruik
	*
	*/
	public function addEvent($controller,$event = 'def',$position = false,$level = true)	{
		//echo $controller;
		if($this->reg->security->doTest($level))    {
		    $this->events[] = array($controller,$event,$position,$level);
		}   
	}
        public function clearEvents()   {
                $this->events = array();
        }
	public function callEvents()	{
	    try	{                    
                while (list($key, list($controller,$event,$position,$level)) = each($this->events)) {
                    
                    $this->activeEvent['name']	    = ($event) ? $event : 'def';
                    $this->activeEvent['position']  = ($position) ? $position : $this->reg->view->defaultTplPosition;
		    $this->activeEvent['level']	    = $level;
		    $this->activeEvent['tplHash']   = md5("{$controller}_{$this->activeEvent['name']}_{$this->activeEvent['position']}");
		    $this->activeEvent['response']  = null;
                    
		    $this->createController($controller);
		    // Check if controller exists, security of maak hem aan en zet als actief.
		    if($this->activeController)	{
			$this->executeEvent();
                        $this->reg->view->handleEventResponse($this->activeEvent);
		    }
                }  
            } catch(application_exception_controller $e)	{
                // Catch de executie error
                $e->eventNo = $key;
                $e->level = $level;
		$e->position = $position;
                $e->controller = $controller;
                $e->event = $event;
                $e->controllerError();
            }
         }
        
        public function createController($controller)  {
	    if(!isset($this->controllers[$controller]))    {
		// Classname maken
		$controllerClassName = "controller_" . str_replace("/",DIRECTORY_SEPARATOR,$controller);
		$controllerInstance = new $controllerClassName();
		$this->controllers[$controller] = $controllerInstance;	
	    }
	    $this->activeController = $this->controllers[$controller];
        }

	/*
	* Handle event: Functie creert de omgeving waarin uiteindelijk de methode gecalled wordt.
	*
	*
	*/
	private function executeEvent()	{
                try	{
                    if($this->reg->security->preEvent($this->activeController)) {
			$this->attachShortcuts($this->activeController);
                        $this->reg->view->attachTemplate($this->activeController,$this->activeEvent);
                        if(method_exists($this->activeController, "_init"))  {
			    $this->activeController->_init();
			}
			// Executie van de methode in de controller //
			if(method_exists($this->activeController,$this->activeEvent['name']))   {
			    $this->activeEvent['response'] = $this->activeController->{$this->activeEvent['name']}();
			}   else    {
			    throw new application_exception_init("Geen geldig event gecalled:" . $this->activeEvent['name']);
			}
			$this->detachShortcuts($this->activeController);
                    }
		} catch (application_exception_event $e)	{
			$e->controller = $this->activeController;
			$e->event = $this->activeEvent;
			$e->eventError();
		}
	}
	private function attachShortcuts($o)	{
		$o->reg = &application_register::getInstance();	// Register
		$o->r	= &$o->reg->r;                 // Geparsed request
		$o->c 	= $this;                                // Main Controller
                if($o->reg->mysql)  {
                    $o->f	= new lib_fsqlHelp();		// Sql
                }
		$o->v	= &$o->reg->view;			// View
		$o->s 	= &$o->reg->session;                    // Sessie
		$o->p	= &$o->reg->post;			// Post
                $o->m   = &$o->reg->mysql;
	}
	private function detachShortcuts($controllerInstance)	{
	    if($controllerInstance) {	
		    unset($controllerInstance->reg);    // Register
		    unset($controllerInstance->r);              // Geparsed request
		    unset($controllerInstance->c);             // Main Controller
		    unset($controllerInstance->f);                 //
		    unset($controllerInstance->v);              // View
		    unset($controllerInstance->s);
		    unset($controllerInstance->p);	
		    unset($controllerInstance->m);	
		    unset($controllerInstance->tpl);	
	    }
	}
}
?>