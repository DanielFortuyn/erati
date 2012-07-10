<?php
$r	    = $this->reg->request->r;
$dbname	    = $r['db'];
$db	    = $this->reg->mysql->initDb($dbname);
$classname  = "model_{$r['db']}_{$r['table']}"; 
$sql	    = "show columns from `" . $r['table']. "`";
$results = $db->query($sql);
                
if($db->errorInfo())    {
    //var_dump($db->errorInfo());
}
$nullFields = '';
foreach($results as $row)	{
    $field[] = $row['Field'];
    $nullFields .= ($row['Null'] == 'NO' && $row['Field'] != 'id') ? "'" . strtolower($row['Field']) . "'," : "";
}
$nullFields = substr($nullFields,0,-1);

		
$b = "<?php\r\n\r\n";
$b .= "//////////////////////////////////////////////////\r\n";
$b .= "//\t\t\t\t\t\t//\r\n";
$b .= "// © Daniel Fortuyn ". date('Y') ."\t\t\t//\r\n";
$b .= "// Ver 2.00a\t\t\t\t\t//\r\n";
$b .= "//\t\t\t\t\t\t//\r\n";
$b .= "// Last Edit: ".date('r')."\t//\r\n";
$b .= "// CRM 3 update \t\t\t\t//\r\n";
$b .= "//\t\t\t\t\t\t//\r\n";
$b .= "// Database:\t{$db->name}\t\t\t//\r\n";
$b .= "// Tabel:\t{$r['table']}\t\t\t\t//\r\n";
$b .= "//\t\t\t\t\t\t//\r\n";
$b .= "//////////////////////////////////////////////////\r\n\r\n\r\n";

                $b .= "class {$classname}";
		if(file_exists("model/" . str_replace("_","/",$dbname) . "/functions/" . $r['table']. ".php"))	{
			$b .= " extends model_" . $dbname . "_functions_" . $r['table'];
		}   else    {
			$b .= " extends model_base ";
		}		
		$b .= " {\r\n\r\n";
		
		foreach($field as $k)	{
			$b .= "\tprotected $$k;\r\n";
		}
		$b .= "\r\n";
		
		foreach($field as $k)	{
			if($k != 'time' && $k != 'uid')	    {	    
			    $b .= "\tpublic function set" . ucfirst($k) . "($$k)	{\r\n";
			    $b .= "\t\t\$this->$k = $$k;\r\n";
			    $b .= "\t}\r\n";  
			    $b .= "\r\n";
			}
			$b .= "\tpublic function get" .ucfirst($k)."()		{\r\n";
			$b .= "\t\treturn \$this->$k;\r\n";
			$b .= "\t}\r\n\r\n";
			
		}
                
                // INSERT //
                $b .= "\t// INSERT //\r\n\r\n";
                $b .= "\tfunction insert()	{\r\n";
		
		$b .= "\t\t\$reg = application_register::getInstance();\r\n";
                $b .= "\t\t\$db = \$reg->mysql->{$db->name};\r\n";
		
		$b .= "\t\t\$sql = \"INSERT INTO ". $r['table'] . "(";
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
		
		$b .= "\t\t\$stmt = \$db->prepare(\$sql);\r\n";
		$b .= "\t\t\$stmt = \$this->bindParams(\$stmt);\r\n";
		
		$b .= "\t\tif(!\$stmt->execute())	{;\r\n";
		$b .= "\t\t\t\$this->resultaat = false;\r\n";
		$b .= "\t\t\t\$this->errors = \$stmt->errorInfo();\r\n";
		$b .= "\t\t} else {\r\n";
		$b .= "\t\t\t\$this->setId(\$db->lastInsertId());\r\n";
		$b .= "\t\t\t\$this->resultaat = true;\r\n";
		$b .= "\t\t}\r\n";
		$b .= "\t\treturn \$this->resultaat;\r\n";
		$b .= "\t}\r\n";
		$b .= "\r\n";
                
                // UPDATE //
		$b .= "\t// UPDATE //\r\n\r\n";
		$b .= "\tfunction update()	{\r\n";
		
		$b .= "\t\t\$reg = application_register::getInstance();\r\n";
                $b .= "\t\t\$db = \$reg->mysql->{$db->name};\r\n";
		
		$b .= "\t\t \$sql = \"UPDATE {$r['table']} SET ";
		$i=0;
		foreach($field as $k)	{
			if($i == 0)	{
				$b .= $k . "= :" . $k;
			} else	{
				$b .= "," . $k . "= :" . $k;
			}
			$i++;
		}
		$b .= " WHERE id =  '\" . \$this->getId() . \"'\";\r\n";
		
		$b .= "\t\t\$stmt = \$db->prepare(\$sql);\r\n";
		$b .= "\t\t\$stmt = \$this->bindParams(\$stmt);\r\n";
		$b .= "\t\tif(!\$stmt->execute())	{\r\n";
		$b .= "\t\t\t\$this->resultaat = false;\r\n";
		$b .= "\t\t\t\$this->errors = \$stmt->errorInfo();\r\n";
		$b .= "\t\t} else {\r\n";
		$b .= "\t\t\t\$this->resultaat = true;\r\n";
		$b .= "\t\t}\r\n";
		$b .= "\t\treturn \$this->resultaat;\r\n";
		$b .= "\r\n";
		$b .= "\t}\r\n";
		$b .= "\r\n";
			
		// DELETE	
		$b .= "\t// DELETE //\r\n\r\n";
		$b .= "\tpublic function delete()	{\r\n";
		$b .= "\t\t \$sql = \"DELETE FROM {$r['table']} WHERE id = '\". \$this->getId().\"'\";\r\n";
		$b .= "\t\t\$reg = application_register::getInstance();\r\n";
                $b .= "\t\t\$db = \$reg->mysql->{$db->name};\r\n";
		$b .= "\t\t if(\$db->exec(\$sql) === false)	{\r\n";
		$b .= "\t\t\t die(\$db->errorInfo());\r\n";
		$b .= "\t\t }\r\n";
		$b .= "\t\t return true; \r\n"; 
		$b .= "\t}\r\n\r\n";
		
		// bindParams
		$b .= "\t// BindParams//\r\n\r\n";
		$b .= "\tprivate function bindParams(\$stmt)	{\r\n";
			foreach($field as $k)	{
				$method = "get" .ucfirst($k);
				$b .= "\t\t \$stmt->bindParam(':$k',\$this->$method());\r\n";
			}
		$b .= "\t\t return \$stmt;\r\n";
		$b .= "\t}\r\n\r\n";		
		
		// ToString
		$b .= "\t// ToString //\r\n\r\n";
		$b .= "\tfunction __toString()	{\r\n";
		$b .= "\t\treturn \"{$r['table']}\";\r\n";
		$b .= "\t}\r\n\r\n";
		
		// validate 
		$b .= "\t // Validate //\r\n\r\n";
		$b .= "\tfunction validate()	{\r\n";
		$b .= "\t\t\$noNull = array({$nullFields});\r\n";
		$b .= "\t\tforeach(\$noNull as \$n)\t{\r\n";
		$b .= "\t\t\t\$getMethod = 'get' . ucfirst(\$n);\r\n";
		$b .= "\t\t\t\$setMethod = 'set' . ucfirst(\$n);\r\n";
		$b .= "\t\t\tif(!\$this->\$getMethod())\t{\r\n";
		$b .= "\t\t\t\t\$this->\$setMethod(false);\r\n";
		$b .= "\t\t\t\tif(!\$this->\$getMethod())\t{\r\n";
		$b .= "\t\t\t\t\tthrow new application_exception_mysql(\"Save niet mogelijk door missende waarde: {\$n}\");\r\n";
		$b .= "\t\t\t\t\treturn false;\r\n";
		$b .= "\t\t\t\t}\r\n";
		$b .= "\t\t\t}\r\n";
		$b .= "\t\t}\r\n";
		$b .= "\t\treturn true;\r\n";
		$b .= "\t}\r\n";
		
		$b .= "}\r\n";
                $b .= "?>\r\n";
		
		
echo $b;
?>