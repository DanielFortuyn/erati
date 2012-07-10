<?php
class lib_xhtml extends lib_element	{
	public function img($filename,$alt = '',$class = '')	{
		?><img src='/_public/_img/<?=$filename?>' alt='<?=$alt?>' class=<?=$class?> /><?php
	}
        public function rIco($iname)    {
            return "<img src='/_public/_img/ico/{$iname}.png' alt='icon {$iname}' class='ico' />";
        }
        public function ico($iname)   {
            echo $this->rIco($iname);
        }
	public function optIco($controller,$event,$parameters,$icon)    {
            $buf .= "<a class='ico' href='/{$controller}/{$event}/";
	    if($parameters) {
		foreach($parameters as $k => $v)    {
		    $buf .= "{$k}/{$v}/";
		}
	    }
            $buf .= "' >";
            $buf .= $this->rIco($icon);
            $buf .= "</a>";
	    return $buf;
        }
	
        
        public function oia($function,$parameters,$icon)    {
            $buf = "<a class='click' onclick=\"{$function}(";
            foreach($parameters as $v)    {
                $buf .= "'{$v}',";
            }
            $buf = substr($buf,0,-1);
            $buf .= ")\" >";
            echo $buf;
            $this->ico($icon);
            echo "</a>";
        }
	/*
	* Functie om links te maken in het systeem
	* @param str controller
	* @param str event 
	* @param str tekst
	* @param array parameters 	| opt
	* @param style type 		| opt
	* @param style name			| opt
	*
	*/
	
	public function a($c,$e,$t,$p = "",$st = "",$sn = "")	{
		$reg = application_register::getInstance();
		$b = "<a ";
		if($st != "")	{
			if($st == "c")	{
				$b .= "class='" . $sn . "'";
			}	elseif($st == 'i')	{
				$b .= "id='" . $sn . "'";
			}	elseif($st == 'b')	{
				$b .= "id='" . $sn[1] . "' class='" . $sn[0] . "'" ;
			}	elseif($st == 's')	{
				$b .= "style='".$sn."'";
			}
		}
		if($c != "")	{
			$b .= " href=\"/" . $c . "/" . $e;
			if($e != "")	{
				$b .= "/";
			}
		}	else	{
			$b .= " href=\"/\"";
		}
		if(is_array($p))	{
			foreach($p as $k => $v)	{
				$b .= $k . "/" . $v . "/"; 
			}
		}
		$b .= "\">" . $t . "</a>";
		
		return $b;
	}
	public function b($c,$e,$t,$p = "",$st = "",$sn = "")	{
		echo $this->a($c,$e,$t,$p,$st,$sn);
	}
        /*
         * @param cv Current Value wordt gebruikt om de selectbox op de huidige waarde in te staken
         * @param onChange onchange attribuut vullen
         */
        public function enum2select($table,$field,$id = false,$class = "",$cv = "",$onchange = "",$pdo = false)    {
            echo parent::enum2select($table, $field, $id, $class, $cv, $onchange, $pdo);
        }
        public function e2s($field,$enums,$cv,$class = '',$id = '')   {
            $buf = "<select ";
            $buf .= ($id) ? "name='{$field}_{$id}' id='{$field}_{$id}' " : "name='{$field}' ";
            $buf .= ($class) ? "class='{$class}' ": "" ;
            //$buf .= ($onchange) ? "onchange=\"{$onchange}\" ": "";
            $buf .= ">\r\n";
            foreach($enums as $e)   {
                if($e == $cv)   {
                    $buf .= "\t<option value='{$e}' selected='selected'>" . ucfirst($e) . "</option>";
                }   else    {
                    $buf .= "\t<option value='{$e}'>" . ucfirst($e) . "</option>";
                }
            }
            $buf .= "</select>\r\n";
            echo $buf;
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
}
?>