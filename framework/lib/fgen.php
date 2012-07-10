<?php
class lib_fgen {
    function genForm($pd,$tname)  {

        $sql = "show columns from " . $tbname;
        $results = $db->query($sql);

        foreach($results as $row)	{
                $field[$row['Field']] = $row['Field'];
        }

        $b = "&lt;?php\r\n\r\n";
        $b .= "////////////////////////////////////\r\n";
        $b .= "//                                //\r\n";
        $b .= "// © Daniel Fortuyn ". date('Y') ."          //\r\n";
        $b .= "// Ver 1.10                       //\r\n";
        $b .= "// Last Edit: 6/03/10             //\r\n";
        $b .= "// jsonEncode toegevoegd          //\r\n";
        $b .= "//                                //\r\n";
        $b .= "//                                //\r\n";
        $b .= "////////////////////////////////////\r\n\r\n";
    }
}
?>
