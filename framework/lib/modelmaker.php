<?php

//////////////////////////////////////////
//  									//
//	© Daniel Fortuyn 2009				//
//	Ver. 1.06							//
//	Last Edit: 22/02/10					//
//	Edit: return delete func			//
//////////////////////////////////////////


class lib_modelmaker	{

	public $db;
	public $dbname;
	public $dbclass;

        public function __construct($name = "")	{
		$reg = application_register::getInstance();
		$reg->initReg(0);
		
		$this->dbname = ($name) ? $name : $reg->settings->mysql_db;
                $this->db = $reg->mysql->initDb($this->dbname);
		$this->dbclass = " &#36;reg = application_register::getInstance(); \r\n\t\t &#36;db = &#36;reg->mysql->";
                $this->dbclass .= ($name) ? $name : $this->dbname;
                $this->dbclass .= ";\r\n";
	}
	// Singleton
	public function getInstance()	{
		static $instance;
		if(!isset($instance))	{
			$instance = new self();
		}
		return $instance;
	}
	
	function write_dbline($tabs)	{
		return $tabs . $this->dbclass;
	}
	
	// DB
	function makemodel_db($tbname,$dbname = '')	{
                
		header("Content-Type: text/html; charset=utf-8");
		// Kolommen ophalen
		$db = $this->db;

		if(empty($dbname))	{
			$dbname = $db->name;
		}
		$sql = "show columns from `" . $tbname . "`";
		$results = $db->query($sql);
                
                if($db->errorInfo())    {
                    //var_dump($db->errorInfo());
                }
		foreach($results as $row)	{
			$field[] = $row['Field'];
		}
		
		$b = "&lt;?php\r\n\r\n";
		$b .= "//////////////////////////////////////////////////\r\n";
		$b .= "//\t\t\t\t\t\t//\r\n";
		$b .= "// © Daniel Fortuyn ". date('Y') ."\t\t\t//\r\n";
		$b .= "// Ver 1.23\t\t\t\t\t//\r\n";
		$b .= "// Last Edit: 29/11/2011\t\t\t\t//\r\n";
		$b .= "// save method aangepast\t\t//\r\n";
		$b .= "//\t\t\t\t\t\t//\r\n";
                $b .= "// Database:\t{$db->name}\t\t\t\t//\r\n";
                $b .= "// Tabel:\t{$tbname}\t\t\t\t//\r\n";
		$b .= "//\t\t\t\t\t\t//\r\n";
		$b .= "//////////////////////////////////////////////////\r\n\r\n\r\n";
		if(empty($dbname))	{
			$b .= "class model_" . $tbname;
		}	else	{
			$b .= "class model_" . $dbname .  "_" . $tbname;
		}
		if(file_exists("model/" . $dbname . "/functions/" . $tbname . ".php"))	{
			$b .= " extends model_" . $dbname . "_functions_" . $tbname;
		}		
		$b .= " {\r\n\r\n";
		
		foreach($field as $k)	{
			$b .= "\tprotected &#36;$k;\r\n";
		}
		$b .= "\r\n";
		
		foreach($field as $k)	{
			$b .= "\tpublic function set" . ucfirst($k) . "($$k)	{\r\n";
			$b .= "\t\t&#36;this->$k = &#36;$k;\r\n";
			$b .= "\t}\r\n";
			$b .= "\r\n";
			$b .= "\tpublic function get" .ucfirst($k)."()		{\r\n";
			$b .= "\t\treturn &#36;this->$k;\r\n";
			$b .= "\t}\r\n\r\n";
		}
		
		// setFROMARRAY //
		
		$b .= "\tpublic function setFromArray(&#36;array)	{\r\n";
		$b .= "\t\tif(is_array(&#36;array) or is_object(&#36;array)) {\r\n";
		$b .= "\t\t\tforeach(&#36;array as &#36;k => &#36;v)	{\r\n";
		$b .= "\t\t\t\t &#36;method = \"set\" . &#36;k;\r\n";
		$b .= "\t\t\t\tif(method_exists(&#36;this,&#36;method))\t{\r\n";
		$b .= "\t\t\t\t\t &#36;this->&#36;method(&#36;v);\r\n";
		$b .= "\t\t\t\t}\r\n";
		$b .= "\t\t\t}\r\n";
		$b .= "\t\t} else {\r\n";
		$b .= "\t\t\t die(\"setFromArray reports: No array input\");\r\n";
		$b .= "\t\t}\r\n";
		$b .= "\t}\r\n\r\n";
		
		// SAVE //
		
		$b .= "\tpublic function save() {\r\n";
                $b .= "\t\t&#36;bool = true;\r\n";
		$b .= "\t\tif(is_numeric(&#36;this->getId())) {\r\n";
		$b .= "\t\t\tif(!&#36;this->update())	{\r\n";
		$b .= "\t\t\t\t&#36;bool = false;\r\n";
		$b .= "\t\t\t}\r\n";		
		$b .= "\t\t} else {\r\n";
		$b .= "\t\t\tif(!&#36;this->insert())	{\r\n";
		$b .= "\t\t\t\t&#36;bool = false;\r\n";
		$b .= "\t\t\t}\r\n";
		$b .= "\t\t}\r\n";
                $b .= "\t\treturn &#36;bool;\r\n";
		$b .= "\t}\r\n\r\n";
		
		// INSERT //
		
		$b .= "\tfunction insert()	{\r\n";
		
		$b .= $this->write_dbline("\t\t");
		
		$b .= "\t\t &#36;sql = \"INSERT INTO $tbname (";
		$i=0;
		foreach($field as $k)	{
			if($i == 0)	{
				$b .= $k;
			} else	{
				$b .= "," . $k;
			}
			$i++;
		}
		$b .= ") VALUES (";
		$i=0;
		foreach($field as $k)	{
			if($i == 0)	{
				$b .= ":".$k;
			} else	{
				$b .= ",:" . $k;
			}
			$i++;
		}
		$b .= ")\";\r\n";
		
		$b .= "\t\t &#36;stmt = &#36;db->prepare(&#36;sql);\r\n";
		$b .= "\t\t &#36;stmt = &#36;this->bindParams(&#36;stmt);\r\n";
		
		$b .= "\t\t if(!&#36;stmt->execute())	{;\r\n";
		$b .= "\t\t\t&#36;this->resultaat = false;\r\n";
		$b .= "\t\t\t&#36;this->errors = &#36;stmt->errorInfo();\r\n";
		$b .= "\t\t} else {\r\n";
		$b .= "\t\t\t&#36;this->setId(&#36;db->lastInsertId());\r\n";
		$b .= "\t\t\t&#36;this->resultaat = true;\r\n";
		$b .= "\t\t}\r\n";
		$b .= "\t\treturn &#36;this->resultaat;\r\n";
		$b .= "\t}\r\n";
		$b .= "\r\n";
		
		// UPDATE //
		
		$b .= "\tfunction update()	{\r\n";
		
		$b .= $this->write_dbline("\t\t");
		
		$b .= "\t\t &#36;sql = \"UPDATE $tbname SET ";
		$i=0;
		foreach($field as $k)	{
			if($i == 0)	{
				$b .= $k . "= :" . $k;
			} else	{
				$b .= "," . $k . "= :" . $k;
			}
			$i++;
		}
		$b .= " WHERE id =  '\" . &#36;this->getId() . \"'\";\r\n";
		
		$b .= "\t\t &#36;stmt = &#36;db->prepare(&#36;sql);\r\n";
		$b .= "\t\t &#36;stmt = &#36;this->bindParams(&#36;stmt);\r\n";
		$b .= "\t\t if(!&#36;stmt->execute())	{\r\n";
		$b .= "\t\t\t&#36;this->resultaat = false;\r\n";
		$b .= "\t\t\t&#36;this->errors = &#36;stmt->errorInfo();\r\n";
		$b .= "\t\t} else {\r\n";
		$b .= "\t\t\t&#36;this->resultaat = true;\r\n";
		$b .= "\t\t}\r\n";
		$b .= "\t\t return &#36;this->resultaat;\r\n";
		$b .= "\r\n";
		$b .= "\t}\r\n";
		$b .= "\r\n";
			
		// DELETE	
		
		$b .= "\tpublic function delete()	{\r\n";
		$b .= "\t\t &#36;sql = \"DELETE FROM $tbname WHERE id = '\". &#36;this->getId().\"'\";\r\n";
		$b .= $this->write_dbline("\t\t");
		$b .= "\t\t if(&#36;db->exec(&#36;sql) === false)	{\r\n";
		$b .= "\t\t\t die(&#36;db->errorInfo());\r\n";
		$b .= "\t\t }\r\n";
		$b .= "\t\t return true; \r\n"; 
		$b .= "\t}\r\n";
		
		// bindParams
		
		$b .= "\tprivate function bindParams(&#36;stmt)	{\r\n";
			foreach($field as $k)	{
				$method = "get" .ucfirst($k);
				$b .= "\t\t &#36;stmt->bindParam(':$k',&#36;this->$method());\r\n";
			}
		$b .= "\t\t return &#36;stmt;\r\n";
		$b .= "\t}\r\n";
	
		// JsonEnconde
		
		$b .= "\tpublic function jsonEncode(&#36;bool = true)	{\r\n";
		$b .= "\t\t&#36;c = new stdClass;\r\n";
		$b .= "\t\tforeach(get_object_vars(&#36;this) as &#36;k => &#36;v)\t{\r\n";
		$b .= "\t\t\t&#36;m = \"get\" . &#36;k;\r\n";
		$b .= "\t\t\t&#36;c->&#36;k = &#36;this->&#36;m();\r\n";
		$b .= "\t\t}\r\n";
                $b .= "\t\tif(&#36;bool)\t{\r\n";
                $b .= "\t\t\treturn json_encode(&#36;c);\r\n";
                $b .= "\t\t}\telse\t{\r\n";
		$b .= "\t\t\treturn &#36;c;\r\n";
                $b .= "\t\t}\r\n";  
		$b .= "\t}\r\n";
		
		
		// ToString
		
		$b .= "\tfunction __toString()	{\r\n";
		$b .= "\t\treturn \"$tbname\";\r\n";
		$b .= "\t}\r\n";
		
                
                // getProperties
                
                $b .= "\tfunction getProperties()	{\r\n";
		$b .= "\t\treturn array_keys(get_class_vars(get_class(&#36;this)));\r\n";
		$b .= "\t}\r\n";
		$b .= "}\r\n";
                $b .= "?>\r\n";
		echo "<pre>".$b."</pre>";
	}
	public function connections($c)     {
            $b = "";
            if($c)  {
                foreach($c as $v)   {
                    if(!is_array($v) || count($v) < 2)  {
                        $b = "\tpublic function get{ucfirst($v[0])}\t{\r\n";
                        $b .= "\t\t&#36;this->{$v[0]} = lib_fsql::find{$v[2]}({$v[0]},$v[1]])\t{\r\r\n";
                        $b .= "\t\treturn &#36this->{$v[0]};\r\n";
                        $b .= "\t}\r\n";
                    }
                } 
            }
            return $b;
        }
	function makefactory_db($tbname)	{
		echo "<pre>";
		echo "&lt;?php\r\n\r\n";
		echo "/////////////////////////////////\r\n";
		echo "//                             //\r\n";
		echo "//    © Daniel Fortuyn 2009    //\r\n";
		echo "//          Ver 0.1            //\r\n";
		echo "//     Last Edit: 24/08/09     //\r\n";
		echo "//	 aanmaak factory maker   //\r\n";
		echo "//                             //\r\n";
		echo "/////////////////////////////////\r\n\r\n";
		echo "class lib_factory_" . $tbname . " {\r\n\r\n";
		echo "\t function __construct()";
		echo "}\r\n";
		echo "?&gt;\r\n";
		echo "</pre>";
	}
}
?>