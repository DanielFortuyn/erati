<?php
class application_view_xhtmlprecies extends application_view_xhtml  {
    public $version;
    public function __construct($reg) {
        parent::__construct($reg);
	$this->addHeader('Accept-Charset','utf-8');
	$this->addHeader('Content-Type','text/html; charset=utf-8');
	$this->setTitle($reg->settings->title);
	$this->version = "0.01a";
    }
    public function addDefaultEvents()	{
	$reg = application_register::getInstance();
	$reg->controller->addEvent('home','search');
        $reg->controller->addEvent('home','menu');
        $reg->controller->addEvent('home','topright');
    }
}
?>
