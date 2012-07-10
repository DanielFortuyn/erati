<?PHP
class controller_home extends controller_b  {
    public $viewType = 'xhtmlprecies';
    public function def()   {
	/*
          $db = $this->reg->mysql->initDb('db');
          $bt = $this->reg->mysql->initDb('bt');
          // Notities
          $notitie = lib_fsql::find('notitie',"","time","DESC",0,25);
          foreach($notitie as $n)  {
              $n->getRType();
          }
          $this->tpl->setVar('notitie',$notitie);
          $best = lib_fsql::find('bestelling',"","time","DESC",0,25,$bt);
          
          foreach($best as $b)  {
              $b->getKlant();
          }
          $this->tpl->setVar('bestelling',$best);
          $this->tpl->setVar('prospect',lib_fsql::find('prospects','','time','desc',0,25,$this->reg->mysql->initDb('db')));
          
          
          $this->tpl->setVar('document',lib_fsql::find('document',"template = 0","time","desc"));
	 * 
	 */
	try {
	    $q = $this->reg->postgresql->erati->query("SELECT * FROM temp");
	    if($q)  {
		foreach($q as $r)   {
		 var_dump($r);
		}
	    }
	    var_dump($this->reg->postgresql->erati->errorInfo());
	    var_dump($q);
	} catch (PDOException $e)   {
	    var_dump($e);
	}
	   
	
	$this->tpl->setVar('user',lib_fsql::find('user'));
    }
    public function search()    {
        $this->tpl->position = 'search';        
    }
    public function menu()  {
        $this->tpl->position = 'menu';
	list($notifications,$num) = $this->getUserNotifcations();
	$this->tpl->setVar('num',$num);
	
    }
    public function getUserNotifcations()	{
	//$types = lib_fsql::find('notificationType',"typeBin & {$this->reg->session->user->getGroups()}");
	$num = 0;
	
	if($types)  {
	    foreach($types as $t)   {
		$notifications = $t->getRecentNotifications($this->reg->session->user->getLastLoginTime());
		if($notifications)  {
		    $num += count($notifications);
		}
	    }
	}
	
	return array($types,$num);
    }
    public function topright()	{
	$this->tpl->position = 'topright';
    }
}