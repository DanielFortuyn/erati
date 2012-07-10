<?php
class lib_tb extends lib_element   {
    public function input($name,$label = false)    {
            echo "<tr>\r\n\t<td>";
            echo ($label) ? $label : ucfirst(strtolower($name));
            echo "\t</td>\r\n";
            echo "\t<td>" . parent::input($name,$label) . "</td>\r\n";
            echo "</tr>\r\n";
    }

    public function textarea($name,$label = false)    {
        echo "<tr>\r\n\t<td style='vertical-align:top;'>";
        echo ($label) ? $label : ucfirst(strtolower($name));
        echo "\t</td>\r\n";
        echo "\t<td>".  parent::textarea($name, $label)."</td>\r\n";
        echo "</tr>\r\n";
    }
    public function  enum2select($table, $field, $id = false, $class = "", $cv = "", $onchange = "", $pdo = false) {
        echo "<tr>\r\n\t<td>";
        echo ucfirst(strtolower($field));
        echo "\t</td>\r\n";
        echo "\t<td>".  parent::enum2select($table, $field, $id, $class, $cv, $onchange, $pdo)."</td>\r\n";
        echo "</tr>\r\n";

    }
}
?>