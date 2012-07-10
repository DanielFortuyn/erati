<?php

$this->addScript('default');
$this->addScript('jquery.corner');
$this->addMeta('Charset','UTF-8');
$this->addRemoteScript("https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js");
$this->addRemoteScript("https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js");

$this->addCss('style');
$this->addCss('ui');

$this->placeHtmlComment('top'); 
?>
<?php $this->placeDoctype(); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <?php $this->placeHead(); ?>
    <?php $this->placeBody(); ?>
    <div id="header" class="container_12">
        <div id="logo" class="grid_2 "><img id="logo" src="_public/_img/logo.png" alt="logo" /></div>
        <div id="topSearch" class="grid_8 ">
            <?php
                $this->placeObj('search');
            ?>
        </div>
        <div class="grid_2">
            <?php
                $this->placeObj('topright');
            ?>
        </div>
        <div class="clear"></div>
        <hr class="grid_12" />
        <?php
            $this->placeObj('menu');
        ?>
        <hr class="grid_12" />
    </div>         
    <div id="content" class="container_12">
        <?php
            $this->placeNotifications();
        ?> 
        <?php
        $this->placeObj('main');
        ?>

    </div>
    <div id="footer" class="container_12">
        
    </div>
       
</body>
</html>