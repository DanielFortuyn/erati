<?php
class controller_generator extends controller_b  {
    public $viewType = 'empty';
    public function model()   {
        if($this->r['table'] && $this->r['db'])   {
            $classname = "model_{$this->r['db']}_{$this->r['table']}";
            $filename = str_replace('_', "/", $classname) . ".php";
            if(file_exists($filename))  {
		    $this->v->setVar('classname',$classname);
            }   else    {
                 throw new application_exception_init("Invalid paramaters {$filename}",2);       // Geen file
            }
        }   else    {
             throw new application_exception_init('Invalid paramaters',1);           // Niet alles opgegegeven
        }
    }
    public function htmltable() {
        if($this->r['table'] && $this->r['db'])   {
            $tbl = $this->r['table'];
            $db = $this->reg->mysql->initDb($this->r['db']);
            $res = $db->query("DESCRIBE {$tbl}");
            $this->v->setVar('fields',$res->fetchAll());
            $this->v->setVar('table',$tbl);
            
        }   else    {
            throw new application_exception_init('Invalid parameters',1);             // Niet alles opgegeven.
        }
    }
}
?>
