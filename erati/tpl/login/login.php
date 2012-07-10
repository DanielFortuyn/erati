<?php
$action = ($this->reg->request->event == 'logout') ? " action='/' " : "";
// <input type="submit" value="" style="width:40px; background-repeat:no-repeat; background-image: url(/_public/_img/go.png);"/>
?>
<form method="post" <?=$action?> id='login_form'>
    <input onfocus="cf(this)" onblur="ff(this,'email@company.biz')" type="text" value="email@company.biz" class="inactive" name="email" id="user"/>
    <input onfocus="cf(this)" onblur="ff(this,'password')" type="password" value="password" id="password" name="pass" class="inactive" />
    <?php $this->v->placeNotifications()?>
</form>
