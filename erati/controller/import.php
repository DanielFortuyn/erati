<?php
class controller_import extends controller_b    {

    var $ftp_p = "Rx56OMsn";
    var $ftp_u = "gnirts_FTP";
    var $ftp_h = "fs.baasbv.eu";
    public $viewType = 'empty';
    var $ftp;

    public function def()  {
        //throw new application_error_init("Aaaah geen import methode opgegeven");
    }
    public function _init() {
        //$this->reg->controller->accessLevel(1);
         set_time_limit(240);
         $this->ftp = "ftp://" . $this->ftp_u . ":" . $this->ftp_p . "@" . $this->ftp_h  . "/";
         //$n = $this->reg->lib('notify');
	 //$n->addNote(7,false,$this->reg->r,"De update: " . $this->reg->controller->activeEvent['name'] . " is gestart.");
    }
    public function klantAfspraak() {
        // Klantafspraak //
        foreach(file($this->ftp . "TRANSFER/klantafs.txt") as $k => $v)    {
            
            $m = new model_bt_prijsafspraak();
            
        }   
        var_dump($v);
    }
    public function postcode()  {
        set_time_limit(5000);    
        $files = glob("_public/_csv/6pp/*");
        var_dump($files);
        foreach($files as $f)   {
            $inhoud = file($f);
            foreach($inhoud as $f)  {
                $pts = explode(",",$f);
                $m = new model_crm_postcode();
                $m->setWoonplaats($pts[0]);
                $m->setPostcode(str_replace(" ","",$pts[1]));
                $m->setStraat($pts[2]);
                $m->save();
            }   
        }
        die();
    }
    public function klantAfleverAdres() {
        $this->ftp = "ftp://" . $this->ftp_u . ":" . $this->ftp_p . "@" . $this->ftp_h  . "/";
        foreach(file($this->ftp . "TRANSFER/AFLEVER.ASC") as $v)  {

            $plaats = "";
            $straat = "";
            $pts = explode("\t",$v);
            $a = new model_crm_klantAfleverAdres();
            $a->setKlantnr($pts[1]);
            $a->setNaam($pts[2]);
            $a->setTel($pts[6]);
            $a->setFax($pts[7]);
            $spts = explode(" ",$pts[4]);
            for($i=0;$i<=count($spts)-2;$i++)   {
                $straat .= $spts[$i] . " ";
            }
            $a->setStraat(trim($straat));
            $a->setHuisnr($spts[count($spts)-1]);
            $ppts = explode(" ",$pts[5]);
            $a->setPostcode($ppts[0] . $ppts[1]);
            for($i=2;$i<count($ppts);$i++)  {
                $plaats .=  $ppts[$i] . " ";
            }
            $a->setPlaats(trim($plaats));
            $a->save();
        }
        return false;
    }
    /* Let op oude CRM gebruikt een oude tabel. Die moet ook geupdate worden, daartoe moet de eerste Variabele gewijzigd worden. Default is de nieuwe Tabel */
    public function omzet() {
            echo "<pre>";
            $db = ($this->r['db']) ? 'db' : 'crm';
            
            $this->ftp = "ftp://" . $this->ftp_u . ":" . $this->ftp_p . "@" . $this->ftp_h  . "/";
            if($this->reg->r['year'])	{
                $y = $this->reg->r['year'];
            }	else	{
                $y = date('Y');
            }
            
            $file = "_public/_upload/omzet" . $y . ".txt";
            if($y == date('Y')) {
                if(!copy($this->ftp.'transfer/OMZTOTE.ASC',$file))   {
                    die("Copy fail");
                }
            }   else    {
                if($this->r['copy'])    {
                    $file = "_public/_upload/omzet" . $y . ".txt";
                    if(!copy($this->ftp.'transfer/OMZTOTE.ASC',$file))   {
                        die("Copy fail");
                    }
                }
            }
            
            if(file_exists($file))  {
                foreach(file($file) as $v)  {
                    $pts = explode("\t",$v);
                    $klantnr = explode(" ",$pts[1]);
                    $klantnr = $klantnr[0];
                    $sdb = $this->reg->mysql->initDb($db);
                    $m = lib_fsql::findOne('klantOmzet',array('klantnr' => $klantnr),$sdb);
                    $yf = 'setOmzet' . $y;
                    
                  
                    if(is_a($m,"model_{$db}_klantOmzet"))  {
                        $m->$yf(trim($pts[2]));
                    }   else    {
                        $model = "model_{$db}_klantOmzet";
                        $m = new $model();
                        echo "Nieuwe klant!\r\n";
                        $m->$yf(trim($pts[2]));
                        $m->setKlantnr($klantnr);
                    }
                    if($m->getKlantnr() == 9025)    {
                        var_dump($m);
                    }
                    if(!$m->save()) {
                        var_dump($m);
                        die("Er is iets mis!");
                    }
                    
                }
            }
            echo "</pre>";
    }
    function verktot()		{

         //	VOLGEND JAAR UPDATE:
         //  - Nieuw model maken
         //  - Nieuwe tabel maken
         //  - Nieuwe export maken nawexp.asc

        //***LET OP ***//
        //Exporteren regels per klant, gesorteerd op  KLANTNUMMER als eerste en dan ARTIKELNUMMER //

            $y = ($this->r['y']) ? $this->r['y'] : date('Y');
            
            $db = $this->reg->mysql->initDb('db');
            $db->exec("TRUNCATE verktot$y");
            $f = file($this->ftp.'transfer/omzarte.asc');

            $foo = array();

            foreach($f as $l)	{
                    $pts    = explode("\t",$l);
                    $model  = "model_db_verktot" . $y;

                    if(!is_a($foo[$pts[0]][$pts[1]],$model)) {
                        $m = new $model();
                        $m->setKlantnr($pts[0]);
                        $m->setArtnr(strtoupper($pts[1]));
                        $m->setAantal($pts[2]);
                        $foo[$pts[0]][$pts[1]] = $m;
                    }   else    {
                        $foo[$pts[0]][$pts[1]]->setAantal($foo[$pts[0]][$pts[1]]->getAantal() + $pts[2]);
                    }
            }
            if($foo)    {
                foreach($foo as $klant) {
                    if($klant)  {
                        foreach($klant as $artikel) {
                            $artikel->save();
                        }
                    }
                }
            }
            echo "Voltooid.";
    }
    public function inkoop()    {
        echo "<pre>";
        $f = file($this->ftp.'transfer/VRDEXP.ASC');
        if($f)  {
            foreach($f as $l)   {
                $pts= explode("\t",$l);
                if($pts[0] == "I" && $pts[1][0] == "V")  {
                    $model = new model_crm_leverancierInkoop();
                    $model->setArtnr($pts[1]);
                    $model->setLevnr($pts[8]);
                    $model->setTime(strtotime($pts[6]));
                    $model->setNum($pts[12]);
                    $model->save();
                }
            }
        }
        echo "</pre>";
    }
    
    private function splitTitleName($titleName) {
        $titles = "";
        $titleArray = array("Dr.h.c.","Ir.","Drs.","Dhr.","Mevr.","Bacc.","Kand.","Ing.","Bc.");
        $titleArrayWithoutPeriod = array("Ir","Drs","Dhr","Mevr","Bacc","Kand","Ing","Bc");
        $name = explode(" ", $titleName);
        foreach ($name as $title) {
            $title = ucfirst(strtolower($title));
            if ($title == "Prof." || $title == "Prof") {
                echo "Prof.\r\n";
                if ($titles != "") {
                    $titles = "Prof." . " " . $titles;
                }
                else {
                    $titles = "Prof.";
                }
                $titleName = str_ireplace("Prof. ", "", $titleName);
            }
            else if ($title == "Mr." || $title == "Mr") {
                echo "Mr.\r\n";
                if (stripos($titles, "Prof.")) {
                    $titles = str_ireplace("Prof.", "Prof. Mr.", $titles);
                }
                else {
                    if ($titles != "") {
                        $titles = "Mr." . " " . $titles;
                    }
                    else {
                        $titles = "Mr.";
                    }
                }
                $titleName = str_ireplace("Mr. ", "", $titleName);
            }
            else if ($title == "Dr." || $title == "Dr") {
                echo "Dr.\r\n";
                if (stripos($titles, "Mr.")) {
                    $titles = str_ireplace("Mr.", "Mr. Dr.", $titles);
                }
                else if (stripos($titles, "Prof.")) {
                    $titles = str_ireplace("Prof.", "Prof. Dr.", $titles);
                }
                else {
                    if ($titles != "") {
                        $titles = "Dr." . " " . $titles;
                    }
                    else {
                        $titles = "Dr.";
                    }
                }
                $titleName = str_ireplace("Dr. ", "", $titleName);
            }
            else if (in_array($title, $titleArray)) {
                if ($titles != "") {
                    $titles = $titles . " " . $title;
                }
                else {
                    $titles = $title;
                }
                $titleName = str_ireplace($title, "", $titleName);
            }
            else if (in_array($title, $titleArrayWithoutPeriod)) {
                if ($titles != "") {
                    $titles = $titles . ". " . $title;
                }
                else {
                    $titles = $title . ".";
                }
                $titleName = str_ireplace($title, "", $titleName);
            }
            else {
                break;
            }
        }
        return array("title" =>$titles, "name" => $titleName);
    }
    
    public function klant() {
            $str = "ftp://" . $this->ftp_u . ":" . $this->ftp_p. "@fs.baasbv.eu/TRANSFER/nawexp.asc";
            $f = file($str);
            if(count($f))	{
                    if($this->m->erati->exec("DELETE FROM customer") === false)	{
			var_dump($this->m);
			throw new application_exception_init("SQL werkt niet.");
                    }
		    if($this->m->erati->exec("DELETE FROM contact") === false)	{
			var_dump($this->m);
			throw new application_exception_init("SQL werkt niet.");
		    }
		  
		  
                    foreach($f as $k => $l)	{
                        $v = explode("\t",$l);
                        foreach($v as &$p)   {
                            $p = trim($p);
                        }
			
                        $m = new model_erati_customer();
                        $m->setId($v[0]);
                        $m->setName($v[3]);
                       
                        $ding = explode(" ",$v[5]);
                        $huisnr = $ding[count($ding)-1];
                        unset($ding[count($ding)-1]);
                        $straat = implode(" ",$ding);
                        $m->setStreet($straat);
                        $m->setHouse($huisnr);
                       
                        $m->setZipCode($v[6]);
                        $m->setCity($v[7]);
                        $m->setDiscountGroup($v[8]);
                        $m->setPhone($v[9]);
                        $m->setFax($v[10]);
                        $m->setPriceGroup($v[11]);
                        $m->setIBAN($v[12]);
                        $m->setCurrencyCode($v[14]);
                        $m->setEmail($v[17]);
                        $m->setVAT($v[18]);
                        $m->setKvk($v[19]);
                        $m->setBranchCode(0);
                        $m->setData(serialize($v));
			
                        if(!$m->insert())	{
			    die('error: insert customer');
			}
                        if ($v[4] != '') {
                            
                            $contactModel = new model_erati_contact();
                            $contactModel->setCustomerId($m->getId());
                            if ($v[15] == "") {
                                $titleName = $this->splitTitleName($v[4]);
                                $contactModel->setName($titleName["name"]);
                                $contactModel->setTitle($titleName["title"]);
                            }
                            else {
                                $contactModel->setName($v[4]);
                                $contactModel->setTitle($v[15]);
                            }
                            $contactModel->setType('default');
                            $contactModel->setEmail('oude@import.nl');
                            $contactModel->setMobile($v[16]);
                            if (!$contactModel->insert()) {
				var_dump($contactModel);
                                die('error: save contact');
                            }
                        }
                    }
            }
            echo 'Klaar.';
    }
    
    public function notities()    {
        $oudeNotities = lib_fsql::find('notities','','','','','',$this->reg->mysql->initDb('db'));
        echo "<pre>";
        if($oudeNotities)   {
            foreach($oudeNotities as $n)    {
                $nw = new model_crm_notitie();
                if($n->getRtype() == 'k')   {
                    $nw->setRType('klant');
                    $nw->setFid($n->getKlantnummer());
                }   elseif($n->getRtype() == 'p')    {
                    $nw->setRType('prospect');
                    $nw->setFid($n->getPid());
                }   else    {
                    echo $nw->getRtype();
                    var_dump($n);
                }
                $nw->setTime($n->getTime());
                $nw->setContactpersoon($n->getContactpersoon());
                $nw->setOnderwerp($n->getAanleiding());
                $nw->setType($n->getType());
               
                $data['msg'] = "Dit betreft een import uit het oude systeem!";
                $data['tel'] = $n->getTel();
                $data['aanleiding'] = $n->getAanleiding();
                $data['afspraak'] = $n->getAfspraak();
                $data['bijzonderheden'] = $n->getBijzonderheden();
                $data['besproken'] = $n->getBesproken();
                $data['flag'] = $n->getFlag();
                
                $nw->setUid($n->getUser());
                $nw->setData(serialize($data));
                
                if(!$nw->save())    {
                    var_dump($nw);
                    die();
                }
            }  
        }
        $oudeNotities = lib_fsql::find('levNote','','','','','',$this->reg->mysql->initDb('db'));
        echo "<pre>";
        if($oudeNotities)   {
            foreach($oudeNotities as $n)    {
                $nw = new model_crm_notitie();
                $nw->setRType('leverancier');
                $nw->setFid($n->getLevnr());
                $nw->setUid($n->getUid());
                $nw->setContactpersoon($n->getContactpersoon());
                $nw->setOnderwerp($n->getOnderwerp());
                $nw->setNotitie($n->getText());
                $nw->setType($n->getType());
                $nw->setTime($n->getTime());
                $nw->setData(serialize("Dit is een import uit het oude systeem!"));
                if(!$nw->save())    {
                    var_dump($nw);
                    die();
                }
            }
        }
        return false;
    }
}
?>