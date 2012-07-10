<?php
$x = $this->reg->lib('xhtml');
?>
<h3 id="version">ERATI <?=$this->reg->settings->version?></h1>
<p id="user"><?=$x->rIco('user')?> <?=$this->reg->session->user->getName()?> <?=$x->optIco('login','logout',false,'door_in');?></p>