<?php
	class controller_b implements application_interface_controller	{
		
                public $tpl;
		public $reg;
		public $accessLevel = true;
                public $viewType    = 'xhtml';
                public $name        = '';
                public $fullName    = '';
                
                public function def()   {
                    
                }
                public function whoAmI()    {
                    return str_replace("controller_", '', get_class($this));
                }
	}
?>