<?php
class application_view_error extends application_view	{

	function __construct($reg)	{
		parent::__construct($reg);
		$this->setLayout("error");
		$this->setStdTplLoc('main');
		$this->setTitle("Error");	
		$this->addScript('prototype');
	}

}
?>