<?php
class model_base    {
	public function save() {
		$bool = true;
		if(method_exists($this, 'validate'))	{
		    $this->validate();
		}
		if(is_numeric($this->getId())) {
			if(!$this->update())	{
				$bool = false;
			}
		} else {
			if(!$this->insert())	{
			    $bool = false;
			}
		}
		return $bool;
	}
	public function setFromArray($array)	{
		if(is_array($array) or is_object($array)) {
			foreach($array as $k => $v)	{
				 $method = "set" . $k;
				if(method_exists($this,$method))	{
					 $this->$method($v);
				}
			}
		} else {
			 die("setFromArray reports: No array input");
		}
	}
	function getProperties()	{
		return array_keys(get_class_vars(get_class($this)));
	}
	public function jsonEncode($bool = true)	{
		$c = new stdClass;
		foreach(get_object_vars($this) as $k => $v)	{
			$m = "get" . $k;
			$c->$k = $this->$m();
		}
		if($bool)	{
			return json_encode($c);
		}	else	{
			return $c;
		}
	}
	public function getTimeMarkup() {
	    return strftime("%e %b '%g %H:%M",$this->getTime());
	}
	public function getIdMarkup()	{
	    return "ID: " . $this->getId();
	}
	public function setTime($time = false)	{
	    $this->time = ($time) ? $time : time();
	}
	public function setUid($uid = false)	{
	    if(!$uid)	{
		$reg = application_register::getInstance();
		$uid = $this->reg->session->user->getId();
	    }
	    $this->uid = $uid;
	}
	public function cleanPhoneNumber($dirtyNumber)	{
		$cleanNumber = str_replace(array(" ","(",")","-"),array("","","",''), $dirtyNumber);
		return is_numeric($cleanNumber) ? $cleanNumber : "";
	}
}
?>
