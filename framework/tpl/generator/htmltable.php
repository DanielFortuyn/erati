<?php
$tplGen = new lib_templateGenerator();
?>
<form id="<?=$table?>" method="post" action="<?=$table?>/save" />
<?php
foreach($fields as $f)  {
    if($f['Field'] == 'id')  {
	echo "\t" . $tplGen->inputHidden($table,$f['Field']);
    }
}
?>
    <table id="<?=$table?>">
<?php
foreach($fields as $f)  {
$field = $f['Field'];
if($f['Field'] != 'id')	{
?>
	<tr id="row_<?=$f['Field']?>">
	    <th><?=ucfirst(strtolower($f['Field']))?></th>
	    <td>&raquo;</td>
	    <td>
		<?php
		$st= strtolower(substr($f['Type'],0,3));
		if($st == 'int' || $st == 'var')	{
		    echo $tplGen->inputText($table, $field);
		} 
		elseif($st == 'tex') {
		    echo $tplGen->textarea($table, $field);
		}
		elseif($st == 'tin') {
		    echo $tplGen->inputCheckBox($table, $field);
		}   
		elseif($st == 'enu') {
		    $options = explode(",",preg_replace('(enum\(|\)|\')', '',$f['Type']));
		    echo $tplGen->inputSelect($table, $field, $options,false);
		}?>
	    </td>
	</tr>
<?php
}
}
    
?>  
	<tr>
	    <td colspan="3"><input type="Submit" value="Verzenden!"/>
	</tr>
    </table>
</form>
