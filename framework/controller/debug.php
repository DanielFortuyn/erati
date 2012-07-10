<?php
class controller_debug extends controller_b {
    public function def()   {
        ?>
<pre>
//////////////////////////////////////////////////
//                                              //
//                                              //
//                                              //
//      &copy; Daniel Fortuyn 2011	                //
//	Baas / Forda Framework                  //
//	Versie 3				//
//						//
//						//
//						//
//						//
//////////////////////////////////////////////////

<?php
unset($this->v->reg);
unset($this->v->tpl);
unset($this->c->reg);
unset($this->tpl->reg);
unset($this->p->reg);
unset($this->reg);
var_dump($this);
?>
</pre>
<?php
die();
        
        
    }
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>