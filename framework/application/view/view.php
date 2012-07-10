<?php
//////////////////////////////////////////////////
//                                              //
//   Application View 1.00                      //
//   25-01-2010                                 //
//   Body OnLoad toegevoegd                     //
//                                              //
//////////////////////////////////////////////////
class application_view  {

	public $tpl;
	protected $layout;
	protected $subview;
	
	public $cache;
	public $cacheTimeOut;
	public $cacheFile;
	public $crumbs = array(array('','','Home','',''));
	public $onLoad;
	public $rscript;
        public $headers;
        public $reg;
        public $defaultTplPosition;
	
        public $dbg;
        protected $data;
        
        protected $success; 
        protected $errors;
        protected $comments;
        
	/*
	* Main constructor for the view module, needs the application_register object to be constructed
	* @param application_register reg
	*/
	
	public function __construct($reg)	{
                $this->defaultTplPosition = (isset($reg->settings->default_template_position)) ? $reg->settings->default_template_position : 'main'; 
		$reg->loadState += 64;
	}
	public function addSuccess($success)    {
            $this->success[] = $success;
        }
	public function addComment($comment)	{
		$this->comments[] = $comment;
	}
	public function addError($error)	{
                if(is_array($error))    {
                    foreach($error as $e)   {
                       $this->errors[] = $e;
                    }
                }   else    {
                     $this->errors[] = $error;
                }
	}
	
        public function attachShortcuts($o)	{
                
		$o->reg = application_register::getInstance();      // Register
		$o->r	= &$o->reg->request->r;                     // Geparsed request
		$o->c 	= &$o->reg->controller;                     // Main Controller
                $o->f	= new lib_fsqlHelp();                       //
		$o->v	= $this;                                    // View
		$o->s 	= &$o->reg->session;                        // Sessie
		$o->p	= &$o->reg->post;                           // Post
	}
	public function detachShortcuts($object)    {
	    if($object)	{
		unset($object->reg);    // Register
		unset($object->r);      // Geparsed request
		unset($object->c);      // Main Controller
                unset($object->f);      // FSQL
		unset($object->v);      // View
		unset($object->s);	// Session
		unset($object->p);	// Post
	    }
	}	
        public function addDbg($var)    {
            $this->dbg[] = $var;
        }
	
	public function dispatch()	{
		
	}
        protected function placeHeaders()	{
		if(count($this->headers) > 0)	{
			foreach($this->headers as $h)	{
				header($h);
			}
		}
	}
        public function addHeader($type,$val)	{
		$this->headers[] = $type . ": " . $val;
	}
        public function setVar($var,$val = 'default')	{
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
        //
        public function attachTemplate()    {
            throw new application_exception_init("In uw view is geen template attach handler geimplementeerd.. Gaat uw schamen!",1);
        }
        public function handleEventResponse() {
            throw new application_exception_init("In uw view is geen event response handler geimplementeerd.. Gaat uw schamen!",1);
        }
	public function detachTemplate($event)	{
	    unset($this->tpl[$event['tplHash']]);
	}
}
?>