<?php
class application_exception_controller extends application_exception_init {
	public function controllerError()	{
		$reg = application_register::getInstance();
		$reg->view->addError("[Controller] Error: " . $this->event . " in class " . get_class($reg->controller->activeController) . ": " . $this->getMessage());
        }
}
?>