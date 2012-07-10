<?php
class controller_json   {
    public function _init() {
        $this->reg->view->setLayout('json');
    }
    public function fetch() {
        if($this->r['id'] && $this->r['object'])    {
            $obj = lib_fsql::findById($this->r['object'],$this->r['id']);
            $obj = (is_object($obj)) ? $obj : false;
            $this->v->setVar('object',$obj);
        }
    }
    public function klant()   {
        $this->v->setVar('object',lib_fsql::findOne('klant',"klantnr = '{$this->r['klantnr']}' "));
    }
}
?>
