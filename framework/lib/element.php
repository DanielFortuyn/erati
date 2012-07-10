<?php
class lib_element   {
    public function input($name,$label = false)    {
        return "<input type='text' name='{$name}' value='{$this->p->$name}' id='{$name}' />";
    }
    public function tblRecord($name,$object,$properties = false,$header = false) {
        echo "<table id={$name}>\r\n";
        if(!$properties)    {
            $properties = $object->getProperties();
        }
        foreach($properties as $k => $p)  {
            $mm = (is_array($p)) ? "get{$p[1]}" : "get{$p}";
            echo "<tr>";
            if(is_callable(array($object,$mm)) && $object->$mm() != null)  {
                $cHeader = ($header) ? $header[$k] : $p;
                echo "<td>{$cHeader}</td>";
                $tm = (is_array($p)) ? "get{$p[1]}Markup" : "get{$p}Markup";   
                $x  = $p[0];
                if(is_callable(array($object,$tm)))  {
                    echo (is_array($p)) ?  "<td class='{$cHeader}'>{$object->$x->$tm()}</td>\r\n" : "<td class='{$cHeader}'>{$object->$tm()}</td>\r\n";
                }   else    {
                    echo (is_array($p)) ?  "<td class='{$cHeader}'>{$object->$x->$mm()}</td>\r\n" : "<td class='{$cHeader}'>{$object->$mm()}</td>\r\n";
                }
            }   
            echo "</tr>";
        }
        echo "</table>";
    }
    public function tbl($name,$objects,$properties = false,$header = false)  {
        $i = 0;
        if(!$properties)    {
	    $k = array_keys($objects);
            $properties = $objects[$k[0]]->getProperties();
        }
        echo "<table cellspacing='0' id={$name}>\r\n";
        // Headers
        echo "<tr>";
        foreach($properties as $k => $p)  {
            $cHeader[$k] = (is_array($header)) ? $header[$k] : $p;
            $cHeader[$k] = (is_array($cHeader[$k])) ? $cHeader[$k][1] : $cHeader[$k];
            echo "<th>{$cHeader[$k]}</th>";
        }
        echo "</tr>";
        // Table data
        //echo "<tr><td>" . var_dump($header) . "</td></tr>"
        foreach($objects as $k => $v)    {
            echo ($i%2) ? "<tr id='row_{$v->getId()}'>" : "<tr id='row_{$v->getId()}' class='uneven'>";
            foreach($properties as $k => $p)  {
                $methodName         = is_array($p) ? $p[count($p)-1] : $p;
                $possibleMethods    = array("get{$methodName}RowMarkup","get{$methodName}Markup","get{$methodName}");
                $subObject          = false;
                $found              = false;
                if(is_array($p))      {
                    $m = "get" . ucfirst(strtolower($p[0]));
                    $subObject = $v->$m();
                }
                if($subObject)  {
                   foreach($possibleMethods as $pm) {
                       if(is_callable(array($subObject,$pm)))    {                           
                            if($subObject->$pm() !== null)   {
                                echo "<td class='{$cHeader[$k]}'>{$subObject->$pm()}</td>";
                                $found = true;
                                break;
                            }
                       }
                   }
                }   else    {
                   foreach($possibleMethods as $pm) {
                       if(is_callable(array($v,$pm)) )    {
                            if($v->$pm() !== null)   {
                                echo "<td class='{$cHeader[$k]}'>{$v->$pm()}</td>";
                                $found = true;
                                break;
                            }
                       }
                   }
                }
                if(!$found) {
                    echo "<td>Vervallen</td>";
                }
            }
            echo "</tr>";
            $i++;
        }
        echo "</table>\r\n";
    }
    public function textarea($name,$label = false)    {
        return "<textarea type='text' name='{$name}' id='{$name}' >{$this->p->$name}</textarea>";
    }
    public function array2select($field,$options,$selected = false,$id = false,$onchange = false)	{
	$buf = "<select ";
        $buf .= ($id) ? "name='{$field}_{$id}' id='{$field}_{$id}' " : "name='{$field}' id='{$field}' " ;
        $buf .= ($class) ? "class='{$class}' ": "" ;
        $buf .= ($onchange) ? "onchange=\"{$onchange}\" ": "";
        $buf .= ">\r\n";
        foreach($options as $e)   {
            if($e == $selected)   {
                $buf .= "\t<option value='{$e}' selected='selected'>" . ucfirst($e) . "</option>";
            }   else    {
                $buf .= "\t<option value='{$e}'>" . ucfirst($e) . "</option>";
            }
        }
        $buf .= "</select>\r\n";
        return $buf;
    }
    public function enum2select($table,$field,$id = false,$class = "",$cv = "",$onchange = "",$pdo = false)    {
        $reg = application_register::getInstance();
        $pdo = ($pdo) ? $pdo : $reg->mysql->{$reg->settings->mysql_db};
        foreach($pdo->query("DESCRIBE $table $field") as $v) {
            $enums = eregi_replace ('(enum\(|\)|\')', '', $v['Type']);
            $enums = explode(',',$enums);
        }
        $buf = "<select ";
        $buf .= ($id) ? "name='{$field}_{$id}' id='{$field}_{$id}' " : "name='{$field}' id='{$field}' " ;
        $buf .= ($class) ? "class='{$class}' ": "" ;
        $buf .= ($onchange) ? "onchange=\"{$onchange}\" ": "";
        $buf .= ">\r\n";
        foreach($enums as $e)   {
            if($e == $cv)   {
                $buf .= "\t<option value='{$e}' selected='selected'>" . ucfirst($e) . "</option>";
            }   else    {
                $buf .= "\t<option value='{$e}'>" . ucfirst($e) . "</option>";
            }
        }
        $buf .= "</select>\r\n";
        return $buf;
    }
}
?>