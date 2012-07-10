<?php
class application_exception_event extends application_exception_controller	{
	public function eventError()	{
		$reg = application_register::getInstance();
		$reg->view->addError("[Event] Error: " . $this->event . " in class " . get_class($this->controller) . ": " . $this->getMessage());
	}
}
?>