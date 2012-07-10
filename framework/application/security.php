<?php
class application_security  {
    
    private $loginController;
    private $loginEvent;
    private $authObj;
    private $authProp;
    private $testType;
    private $logInLevel;
    private $key;
    
    public function __construct($reg)   {
        $this->loginController  = ($reg->settings->default_login_controller) ? $reg->settings->default_login_controller : "login";
        $this->loginEvent       = ($reg->settings->default_login_event) ? $reg->settings->default_login_event : "login";
        $this->authObj          = ($reg->settings->default_user_object) ? $reg->settings->default_user_object : "user";
        $this->authProp         = ($reg->settings->default_user_level_method) ? $reg->settings->default_user_level_method : "level";
        $this->testType         = ($reg->settings->default_login_test_type) ? $reg->settings->default_login_test_type : 'binary';
        $this->logInLevel       = ($reg->settings->default_login_level) ? $reg->settings->default_login_level : 1;
        $this->authMeth         = "getLevel";
	$this->key		=  md5("{$reg->settings->keysalt}_".(strftime('%M')*strftime('%H')) . strftime('%x')."_framework"); 
        $reg->loadState += 8;
    }
    public function preRequest()    {
	$reg = application_register::getInstance();
        $return = true;
        if($reg->settings->public !== "true")  {
	    if(!$this->doTest()) {
		$this->gotoLogin();
		$return = false;
	    }
	}
	
        return $return;
    }
    /*
     * Do not execute event
     */
    
    public function preEvent($controllerInstance) {
        $return = false;
        if($this->doTest($controllerInstance->accessLevel)) {
	    $return = true;
	}   else    {
	    $this->gotoLogin();
	}
        return  $return;
    }
    /*
     * Do not execute part of event
     */
    public function inLine($level = false)   {
            return $this->doTest($level);
    }
    /*
     * Hide certrain output
     */
    
    public function toLogin($level = false)    {
        if($this->doTest($level))  {
            return true; 
        }   else    {
            $this->gotoLogin();
            return false;
        }
    }
    /*
     * Deze functie voert de primaire test uit en geeft true of false naargelang iemand aan het criteria voldoet of niet
     * @param int level getal representeert de waarde die gehaald moet worden
     */
    public function doTest($level = false)  {
	$reg = application_register::getInstance();
        if($level === true) {
	    return true;
	}
	if(isset($reg->r['key']))	{
	    if($this->key == $reg->r['key'])	{
		return true;
	    }
	}
	if($reg->session->{$this->authObj})   {
	    $am     = $this->authMeth;
	    $level  = ($level) ? $level : $this->logInLevel;
	    if(($level&$reg->session->{$this->authObj}->$am())==$level) {
		return true;
	    }
	}
	return false;
    }
    public function gotoLogin() {
	$reg = application_register::getInstance();
	// view resetten
	if(!($reg->view instanceof application_view_xhtml))    {
	$reg->view = new application_view_xhtml($reg);
	}
	$reg->view->setLayout('login');	
        $reg->controller->clearEvents();
        $reg->controller->addEvent($this->loginController,$this->loginEvent,'main',true);        
    }
    
}
?>
