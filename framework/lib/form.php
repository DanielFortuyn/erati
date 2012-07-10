<?php
class lib_form	{
	
	public $db;
	
	function recaptcha()	{
		require_once('recaptchalib.php');
		
		// Get a key from http://recaptcha.net/api/getkey
		$publickey = "6LeHDggAAAAAANGAb2j9mmB1PuvzhEyh4dCm0tEP";
		$privatekey = "6LeHDggAAAAAALUUbOuQnGODTDDcncg-yVYzrTCw";
	
		echo recaptcha_get_html($publickey, $error);
	}
	
	function inputtext($name,$value = '')	{
		echo "<input maxlength='250' type='text' name='".$name."' id='".$name."' value='".$value."'/>";
	}
	function inputpass($name,$value = '')	{
		echo "<input maxlength='250' type='password' name='".$name."' id='".$name."' value='".$value."'/>";
	}
	function inputcheck($name,$value = '')	{
		if($value)	{
			echo "<input type='checkbox' checked='checked' name='".$name."' id='".$name."' value='1'/>";
		}	else	{
			echo "<input type='checkbox' name='".$name."' id='".$name."' value='1'/>";
		}
	}
	function inputSubmit($value = '')	{
		if(empty($value))	{
			echo "<input type='submit' value='Verzenden!' />";
		}	else	{
			echo "<input type='submit' value='${value}' />";
		}
	}
	// ENUM
	
	function write_tblenum($tbl,$name,$val='',$class = '')	{
		$pts = 	$this->return_tblenum($tbl,$name);	
		echo "<select name='".$name."' id='".$name."' class='".$class."'>\r\n";
		foreach($pts as $v)	{
			if($val == $v)	{
				echo "<option value='$v' selected='selected' >" . ucfirst(strtolower($v)) .  "</option>\r\n";
			}	else	{
				echo "<option value='$v' >" . ucfirst(strtolower($v)) .  "</option>\r\n";
			}
		}
		echo "</select>\r\n";
	}
	function return_tblenum($tbl,$name)	{
		$res = $this->db->query("SHOW COLUMNS FROM $tbl LIKE '$name'");
		if($res)	{
			foreach($res as $row)	{
				$pts = explode(",",str_replace("'","",substr($row['Type'],5,-1)));
			}
		}	else	{
			die("lib_form: error tblenum");
		}
		return $pts;
	}
}
?>