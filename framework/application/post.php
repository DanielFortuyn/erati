<?php
class application_post	{
	
	public $unsafe;
        public $safe;
	public $data;
	
	function __construct($reg)	{
		$this->unsafe = $_POST;
		foreach($_POST as $k => $v)	{
			$k = trim($k);
			$this->$k = strip_tags(trim($v));
                        $this->safe[$k] = strip_tags(trim($v));
		}
                $reg->loadState += 64;
	}
        
        public function __set($name, $value) {
	    echo "<h1>haloo</h1>";
            $this->data[$name] = $value;
        }
        public function __get($name)    {
            return $this->data[$name];
        }
}
?>