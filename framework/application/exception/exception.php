<?php
	class application_exception extends Exception	{
		
		public $reg;
		public $event;
		public $sql;
		public $controller; 
		
		public function initError()	{
			$this->finalError();
		}
		public function eventError()	{
			$reg = application_register::getInstance();
			$reg->view->addError("Event error: " . $this->event . " in class " . get_class($this->controller) . ": " . $this->getMessage());
		}
		public function finalError()	{
                        $this->reg = application_register::getInstance();
			
                        if($this->framework_file_exists("tpl/error.php"))  {
                            require "tpl/error.php";
                            die();
                        }   else    {
                            die("Geen error template gevonden.");
                        }
		}
		
		function stacktrace()	{
			$trace = $this->getTrace();
			$result = "";
			array_shift($trace);
		   
			$result .= '<table class="trace" border="0" cellpadding="3">';

		 
			foreach ($trace as $level => $data)
			{
			  $result .= '<tr><td>#'. $level . '</td>';
			  $result .= '<td>';
			 
			  if (isset($data['class'])) {
				$result .= $data['class'] . $data['type'];
			  }
		 
			  $result .= $data['function'] . "()</td>";
			  $result .= sprintf("<td>[%s:%d]</td>\n", $data['file'], $data['line']);
			  $result .= "</tr>";
		 
			  if (isset($data['args']) && count($data['args'])> 0) {
				$result .= '<tr>';
				$result .= '<td>&nbsp;</td>';
				$result .= '<td valign="top">Arguments:<br /></td>';
				$result .= '<td><pre>';
		 
				foreach ($data['args'] as $key => $argument) {
				  	if($argument->reg->settings)	{
						$argument->reg->settings = null;
					}
					if($argument->reg->view)	{
						$argument->reg->view	= null;
					}
					if($argument->settings)	{
						$argument->settings = null;
					}
					$result .= "#$key: " . htmlspecialchars(print_r($argument, true)) . '<br />';
				}
				$result .= '</pre></td></tr>';
			  }
			}
			$result .= "</table>";
			return $result;
		}
                 public function framework_file_exists($file) {
                     if(file_exists($file)) {
                         return true;
                     }  else    {
                        foreach(explode(PATH_SEPARATOR,  get_include_path()) as $path)  {
                            $fp = $path . DIRECTORY_SEPARATOR . $file;
                            if(file_exists($fp))    {
                                return $fp;
                            }
                        }

                     }
                     return false;
                }       
}
?>