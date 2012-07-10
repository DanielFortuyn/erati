<?php
    class lib_templateGenerator   {
	public function ni($val) {
	    return "name='{$val}' id='{$val}' ";
	}
	public function inputText($table,$field) {
	    $b = "<input type='text' ";
	    $b .= $this->ni($field);
	    $b .= $this->inputValue($table,$field);
	    $b .= "/>\r\n";
	    return $b;
	}
	public function inputHidden($table,$field)	{
	    $b = "<input type='hidden' ";
	    $b .= $this->ni($field);
	    $b .= $this->inputValue($table,$field);
	    $b .= "/>\r\n";
	    return $b;
	}
	public function inputCheckbox($table,$field)	{
	    $b = "<input type='checkbox' ";
	    $b .= $this->ni($field);
	    $b .= "value='1'";
	    $b .= "/>\r\n";
	    return $b;
	}
	public function inputSelect($table,$field,$options,$selected) {
	    $b = "<select ";
	    $b .= $this->ni($field);
	    $b .= ">\r\n";
	    foreach($options as $o) {
		if($o == $selected) {
		    $b .= "\t<option value='{$o}'>".ucfirst($o)."</option>\r\n";
		}   else    {
		    $b .= "\t<option value='{$o}' selected='selected'>".ucfirst($o)."</option>\r\n";
		}
	    }
	    $b .= "</select>\r\n";
	    return $b;
	}
	public function textarea($table,$field)  {
	    $b = "<textarea ";
	    $b .= $this->ni($field);
	    $b .= "/>" . "<?=$$table" . "->get" . ucfirst($field) . "();?>" . "</textarea>\r\n";
	    return $b;
	}
	public function inputValue($table,$field)	{
	    return "value='<?=$$table" . "->get" . ucfirst($field) . "();?>'";
	}
    }
?>