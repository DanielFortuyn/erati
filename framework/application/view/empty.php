<?php
class application_view_empty extends application_view	{
	public function __construct($reg)	{
		parent::__construct($reg);
        $this->addHeader("Content-Type", "text/plain; charset=utf-8");
		$this->layout = null;	
	}
    public function dispatch() { 
        parent::dispatch();
        $this->placeHeaders();
	    if($this->data) {
		    extract($this->data);
	    }
        if($this->tpl)  {
            foreach($this->tpl as $tpl) {
                if(stream_resolve_include_path($tpl->file))   {
		            $this->attachShortcuts($tpl);
                    $tpl->dispatch();
			        $this->detachShortcuts($tpl);
                }   else    {
                    echo "TPL niet gevonden..";
                }
            }
        }
    }
    public function attachTemplate($controller,$event) {
        $tpl = new application_template("tpl/" . $controller->whoAmI() . "/" . $event['name'] . ".php");
        $this->tpl[$tpl->hash] = $tpl;
        $controller->tpl = $tpl;
    }
	public function handleEventResponse() {}
}
?>