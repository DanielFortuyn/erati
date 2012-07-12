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
                if(($this->m->erati->exec("DELETE FROM customer") === false) or ($this->m->erati->exec("ALTER TABLE customer AUTO_INCREMENT = 0") === false))	{
    			    var_dump($this->m);
    			    throw new application_exception_init("SQL werkt niet.");
                }
		        if(($this->m->erati->exec("DELETE FROM contact") === false)	or ($this->m->erati->exec("ALTER TABLE contact AUTO_INCREMENT = 0") === false)) {
    			    var_dump($this->m);
    			    throw new application_exception_init("SQL werkt niet.");
		        }
                $i = 0;
                foreach($f as $k => $l)	{
                    $i++;
                    $v = explode("\t",$l);
                    foreach($v as &$p)   {
                        $p = trim($p);
                    }
    	             
                    $ding = explode(" ",$v[5]);
                    $huisnr = $ding[count($ding)-1];
                    unset($ding[count($ding)-1]);
                    $straat = implode(" ",$ding);


                    $m = new model_erati_customer();

                    $m->setId($v[0]);
                    $m->setName($v[3]);
                    
                    $m->setAttention('');

                    $m->setStreet($straat);
                    $m->setHouse($huisnr);
                    $m->setZipcode($v[6]);
                    $m->setCity($v[7]);
                    $m->setCountry('Nederland');
                    $m->setPhone($v[9]);
                    $m->setFax($v[10]);
                    $m->setEmail($v[17]);
                    $m->setIban($v[12]);
                    $m->setCurrency($v[14]);
                    $m->setVat($v[18]);
                    $m->setKvk($v[19]);
                    $m->setOrigin('import');
                    $m->setTime(time());
                    $m->setBranchId('');                    // bestaat nog niet
                    $m->setDiscountGroupId($v[11]);         // Gevuld met oude prijsgroep, niemand gebruikte namelijk discount groups, echter wel een betere naam.
                    $m->setData(serialize($v));

                    $contactModel = false;
                    
                    if ($v[4] != '') {
                        if((stripos($v[4],'t.a.v.') === false) and (stripos($v[4],'afd. ') === false) and (stripos($v[4],'tav ') === false) && (stripos($v[4],'afdeling') === false) && (stripos($v[4],'locatie') === false)) {
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
                        } else {
                            $m->setAttention(str_replace(array("tav","t.a.v."), array("",""), $v[4]));    
                        }
                    }

                    if(!$m->insert())   {
                        var_dump($m);
                        echo $m->sql;
                        die('error: insert customer');
                    }
                    if($contactModel)   {
                        if (!$contactModel->insert()) {
                            var_dump($contactModel);
                            die('error: save contact');
                        }
                    }
                    //stoppen bij 40 rijen om te testen 
                    /*
                    if($i == 40)    {
                        die('Einde verhaal');
                    }
                    */
                }
            }
            echo "Klaar. {$i} rijen toegevoegd";
            return false;
        }
    }
?>