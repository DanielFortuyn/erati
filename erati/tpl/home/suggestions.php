[<?php
$x=0;
if($klant)  {
    foreach($klant as $k)    {
        echo ($x!=0) ? "," : "";
        $x++;
        echo "{ \"label\": \"{$k->getNaam()}\", \"category\": \"Klanten\",\"id\": \"{$k->getId()}\" }";
    }
}
if($leverancier)  {
    foreach($leverancier as $k)    {
        echo ($x!=0) ? "," : "";
        $x++;
        echo "{ \"label\": \"{$k->getNaam()}\", \"category\": \"Leveranciers\", \"id\": \"{$k->getId()}\" }";
    }
}

if($prospect)  {
    foreach($prospect as $k)    {
        echo ($x!=0) ? "," : "";
        $x++;
        echo "{ \"label\": \"{$k->getNaam()}\", \"category\": \"Prospect\", \"id\": \"{$k->getId()}\" }";
    }
}
if($product)  {
    foreach($product as $k)    {
        echo ($x!=0) ? "," : "";
        $x++;
        $str = substr($k->getOmschrijving(),0,40);
        echo "{ \"label\": \"{$k->getArtnr()} {$str}\", \"category\": \"Product\",\"id\": \"{$k->getId()}\" }";
    }
}
?>]
