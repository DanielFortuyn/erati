<script>
$(document).ready(function() {
    $("#tabs").tabs();
 });
</script>
<?php

function breakwords($text)   {
    $ta = explode(" ",$text);
    echo implode(" ",array_slice($ta, 0,7));
}

$x = new lib_xhtml();
?>

<div class="grid_12">
    <h1>Welkom </h1>
     <div id="tabs">
        <ul>
            <li><a href="#fragment-1"><span>Notities</span></a></li>
            <li><a href="#fragment-2"><span>User</span></a></li>
            <li><a href="#fragment-3"><span>Sessie_dump</span></a></li>
	    <li><a href="#fragment-4"><span>Register_dump</span></a></li>
        </ul>
         <div id="fragment-1">
          test
             
         </div>
         <div id="fragment-2">
	     <pre>
             <?php
	     print_r($user);
	     ?>
	     </pre>
         </div>
         <div id="fragment-3">
             <pre><?php
		print_r($this->s);
		?>
	     </pre>
         </div>
	  <div id="fragment-4">
		<pre><?php
		print_r($this->reg);
		?>
	     </pre>
         </div>	
</div>
<pre>
</pre>