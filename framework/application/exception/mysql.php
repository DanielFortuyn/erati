<?php
class application_exception_mysql extends application_exception_event	{
	public function mysqlError($fsqlHelp)	{
		$reg = application_register::getInstance();
		$this->loadstate    = 10;
		$this->sql =        $fsqlHelp->sql;
		$this->event =      $reg->controller->aEvent;
		$this->controller = $reg->controller->aControllername;
		$this->finalError();
		die();
		
	}
}
?>