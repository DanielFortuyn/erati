<?php
class application_view_mobile extends application_view  {
    public function __construct($reg)   {
        parent::__construct($reg);
        $this->setLayout('mobile');
        $this->setStdtplpos('mobile');
        $this->addScript('jquery');
        $this->addScript('default');
    }
}
?>