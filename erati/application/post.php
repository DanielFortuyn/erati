<?php
class application_post	{
	
	var $unsafe;
	var $data;
	
	function __construct($reg)	{
		$this->unsafe = $_POST;
		foreach($_POST as $k => $v)	{
			$k = trim($k);
			if(!is_array($v))   {
			    $this->$k = strip_tags(trim($v));
			    $this->data[$k] = strip_tags(trim($v));
			} else {
			    $this->$k = $v;
			    $this->data[$k] = $v;
			}
 		}	
		// Log In procedure //
		if(($this->email != "") && $this->pass != "")	{
			$this->login($reg,$this->email,$this->pass);
		}
	}
	private function login($reg,$u,$p)	{
		//$notify = $this->reg->lib('notify');
                $model =  "model_" . $reg->settings->mysql_db . "_" . $reg->settings->default_user_object;
		$res = lib_fsql::findOne($reg->settings->default_user_object,"email = '$u' AND password = MD5('$p')");
		if(!$res)	{
			$reg->view->addError("invalid combination");   
			//$notify->addNote(6,false,array($u,$p,$_SERVER['REMOTE_ADDR']),"Login poging mislukt.");
		} elseif(is_a($res,$model)){
			$res->setLastLoginTime(time());
			$res->save();
			$reg->session->{$reg->settings->default_user_object} = $res;
		    	$reg->view->addComment("U bent nu ingelogged");
			//$notify->addNote(1);
		}		
	}	
}
?>