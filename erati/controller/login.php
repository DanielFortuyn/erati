<?php
class controller_login extends controller_b {
    public $accessLevel = true;
    public $viewType = 'xhtml';
    
    public function def()   {
        return false;
    }
    public function login()    {
	$this->reg->view->setLayout('login');
	/*
	$tpl = $this->reg->view->tpl;
	$this->reg->view = new application_view_login($this->reg);
	$this->reg->view->addTplObj(new application_template('./tpl/login/login.php'));
	$this->reg->view->attachTemplate($this,array('name' => 'login','position' => 'main'));
	
	 * 
	 */
    }
    
    public function logout()    {
	$this->reg->session->destroy();
        $this->reg->view->addError("U bent uitgelogged");
        $this->c->addEvent('login','login');
        return false;
    }
}
?>