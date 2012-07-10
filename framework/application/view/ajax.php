<?php
class application_view_ajax extends application_view {
    public $data = array();
    public function __construct($reg)   {
        parent::__construct($this->reg);
        $this->addHeader("Content-Type:", 'text/javascript'); 
    }
    function dispatch() { 
        //parent::dispatch();
        $this->placeHeaders();
        extract($this->data);
        if($this->tpl)  {
            foreach($this->tpl as $tpl) {
                if(stream_resolve_include_path($tpl))   {
                    require $tpl;
                }   else    {
                    echo ini_get('include_path');
                        echo "TPL niet gevonden: {$tpl}";
                }
            }
        }
    }
   public function attachTemplate($controller,$event) {      
        $tpl = "tpl/" . str_replace("_",DIRECTORY_SEPARATOR, $controller->whoAmI()) . "/" . $event['name'] . ".php";
	$this->tpl[$event['tplHash']] = $tpl;
   }
   public function handleEventResponse() {
       if($this->activeEvent['response'] === false)	{
	    unset($this->tpl[$this->activeEvent['tplHash']]);
       }
   }
}
?>
