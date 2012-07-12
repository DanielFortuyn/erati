<?php
class controller_generator extends controller_b  {
    public $viewType = 'empty';
    public $db;
    
    public function _init()  {
        if($this->r['db'])  {
            $this->db = $this->reg->mysql->initDb($this->r['db']);
        }   else {
            $reg = application_register::getInstance();
            $this->db = $reg->mysql->default;
        }
        if(!$this->r['table'])   {
            throw new application_exception_init('Invalid paramaters; Tbl ontbreekt',1); 
        }
    }

    public function model()   {
        $tbl = $this->r['table'];
        $classname = "model_{$this->db->name}_{$tbl}";
        $filename = str_replace('_', "/", $classname) . ".php";
        if(file_exists($filename))  {
            $res = $this->db->query("show columns from {$tbl}");
            $this->tpl->setVar('dbname',$this->db->name);
            $this->tpl->setVar('fields',$res->fetchAll());
            $this->tpl->setVar('classname',$classname);
            $this->tpl->setVar('tbl',$tbl);
        }   else    {
             throw new application_exception_init("Invalid paramaters {$filename} {$classname}",2);       // Geen file
        }
        
    }
    public function htmltable() {
        $tbl = $this->r['table'];
        $res = $this->db->query("DESCRIBE {$tbl}");
        $this->v->setVar('fields',$res->fetchAll());
        $this->v->setVar('table',$tbl);   
    }
    public function setters()   {
        $tbl = $this->r['table'];
        $res = $this->db->query("DESCRIBE {$tbl}");
        $this->tpl->setVar('fields',$res->fetchAll());
        $this->tpl->setVar('table',$tbl);  
    }
}
?>
