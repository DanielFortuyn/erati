<?php

//////////////////////////////////////
//								  	//
//  � Daniel Fortuyn 2009		  	//
//  Ver		 : 1.1	 	          	//
//  Last Edit: 17/09/09			  	//
//  setDefaults & parameters voor 1 //
//	regel returns, 				    //
//								    //
//////////////////////////////////////

class lib_fsqlHelp	{
		
	public $query;
	public $table; 
	public $error;
	public $model;

	public $order;
	public $dir;
	public $sql;
	public $count;
	public $cols;
	
	public $start; 
	public $num;
		
	public $reg;
	public $db;
	public $res;
	public $modelPrefix;
        
        public $result = false;
        
        public $factory;
        
        public function __construct()	{
		$reg = application_register::getInstance();
                //var_dump($reg);
                if($reg->settings->mysql_db)    {
                    $this->db = $reg->mysql->initDb($reg->settings->mysql_db);
                }
	}

	public function find($table = '',$query = '',$order = '',$dir = '',$start = '',$num = '',$db = '')	{
		try {
                        $result = false;
			$this->setDefaults($table,$query,$order,$dir,$start,$num,$db);
			$this->selectModel();
			$this->buildQuery();
		
			$stmt = $this->db->prepare($this->sql);	
			$stmt->setFetchMode(PDO::FETCH_CLASS,$this->model);
			if(!$stmt->execute())	{
				$a = $stmt->errorInfo();
				throw new application_exception_mysql("Find error ID: " . $stmt->errorCode() . " Info: " . $a[2]);
			}
			while($object = $stmt->fetch(PDO::FETCH_CLASS))	{
			    if(method_exists($object, 'getId'))	{
				$result[$object->getId()] = $object;
			    }	else	{
				$result[] = $object;
			    }
			}
		}  catch(application_exception_mysql $e)	{
			$e->mysqlError($this);			
			$result = false;
		}
		return $result;
	}

	public function findOne($table = '',$query = '',$db = '')	{
		try	{
                        $result = false;
			$this->setDefaults($table,$query,null,null,null,null,$db);
			$this->selectModel();
			$this->start = 0;
			$this->num = 1;
			$this->buildQuery();
			$stmt = $this->db->prepare($this->sql);	
			$stmt->setFetchMode(PDO::FETCH_CLASS,$this->model);
			if(!$stmt->execute())	{
				$a = $stmt->errorInfo();
				throw new application_exception_mysql("Find error ID: " . $stmt->errorCode() . " Info: " . $a[2]);
			}
			while($object = $stmt->fetch(PDO::FETCH_CLASS))	{
				$result = $object;
			}
		} catch(application_exception_mysql $e)	{
			$e->mysqlError($this);			
			$result = false;
		}
		return $result;
	}	
	public function findById($table = '',$query = '',$db = '')	{
		try {   
                        $result = false;
			$this->setDefaults($table,$query,null,null,null,null,$db);
			$this->selectModel();
			$this->query = "id = '".$query."'";
			$this->buildQuery();
			$stmt = $this->db->prepare($this->sql);	
			$stmt->setFetchMode(PDO::FETCH_CLASS,$this->model);
			if(!$stmt->execute())	{
				$a = $stmt->errorInfo();
				throw new application_exception_mysql("Find error ID: " . $stmt->errorCode() . " Info: " . $a[2]);
			}
			while($object = $stmt->fetch(PDO::FETCH_CLASS))	{
				$result = $object;
			}
		} catch(application_exception_mysql $e)	{
			$e->mysqlError($this);			
			$result = false;
		}
		return $result;
	}
        public function findByFk($table = '',$fk = '',$fkid = '',$db = '')	{
		try {   
                        $result = false;
			$this->setDefaults($table,null,null,null,null,null,$db);
			$this->selectModel();
			$this->query = "{$fk} = '".$fd."'";
			$this->buildQuery();
			$stmt = $this->db->prepare($this->sql);	
			$stmt->setFetchMode(PDO::FETCH_CLASS,$this->model);
			if(!$stmt->execute())	{
				$a = $stmt->errorInfo();
				throw new application_exception_mysql("Find error ID: " . $stmt->errorCode() . " Info: " . $a[2]);
			}
			while($object = $stmt->fetch(PDO::FETCH_CLASS))	{
				$result = $object;
			}
		} catch(application_exception_mysql $e)	{
			$e->mysqlError($this);			
			$result = false;
		}
		return $result;
	}
	public function findNum($table = '',$query = '',$db = '')	{
		$this->setDefaults($table,$query,null,null,null,null,$db);
		
		$sql = "SELECT COUNT(*) as num FROM " . $this->table;

                $sql = $this->buildWhere($sql);

		$this->sql = $sql;
		$res = $this->db->query($sql);
		if($res)	{
			foreach($res as $r)	{
				$result = $r['num'];
				return $r['num'];
			}
		}	else	{
			$this->error = $this->db->errorInfo();
			print_r($this->error);
			return false;
		}	
	}
	public function join($query,$model)	{
		try	{
                        $this->sql = $query;
			if($model)	{
				if(substr($model,0,5) != 'model')	{
					$this->model = "model_" . $this->db->name . "_" . $model;
				}	else	{
					$this->model  = $model;
				}
			}	else	{
				$this->model = 'model_' . $this->db->name . "_join";
			}

			$stmt = $this->db->prepare($this->sql);	
			$stmt->setFetchMode(PDO::FETCH_CLASS,$this->model);
			if(!$stmt->execute())	{
				$a = $stmt->errorInfo();
				throw new application_exception_mysql("Find error ID: " . $stmt->errorCode() . " Info: " . $a[2]);
			}
			while($object = $stmt->fetch(PDO::FETCH_CLASS))	{
				$result[] = $object;
			}
                        
		} catch(application_exception_mysql $e)	{
			$e->mysqlError($this);			
			$result = false;
		}
                return $result;
	}
	// PRIVATES
	private function setDefaults($table = '',$query = '',$order = '',$dir = '',$start = '',$num = '',$db = '')	{
		if(is_a($db,"PDO"))	{
			$this->db = $db;
		}
		// Geen Db is altijd fout.
		if(!is_a($this->db,"PDO"))	{
			die("Geen geldige database verbinding [lib_fsqlHelp.php]");
		}
		// Geen tabel is altijd fout.
		if(($this->table == "") && $table == "")	{
			die("Geen tabel geselecteerd [lib_fsqlHelp.php]");
		}
		// Result cleanen 
		unset($result);
		
		// Primary variables 
		$this->table 		= (empty($table)) ? $this->table : $table;
		$this->query 		= (empty($query)) ? $this->query : $query;
		
		// Optioneel
		$this->order		= (empty($order)) ? $this->order : $order;
		$this->dir		= (empty($dir))	  ? $this->dir : $dir;
		$this->start		= (empty($this->start)) ? $start : $this->start;
		$this->num		= (empty($this->num)) ? $num : $this->num ;
		
		// Reguliere defaults
		
		$this->start		= (empty($this->start)) ? 0 : $this->start;
		$this->num		= (empty($this->num)) ? 0 : $this->num;	
		$this->modelPrefix	= (empty($this->modelPrefix)) ? "model_" : $this->modelPrefix;
	}
	private function selectModel()	{	
		if(empty($this->model))	{
			$this->model = (empty($this->db->name)) ? $this->model = $this->modelPrefix . $this->table : $this->model = $this->modelPrefix . $this->db->name . "_" . $this->table;
		}	else	{
			if(substr($this->model,0,strlen($this->modelPrefix)) != $this->modelPrefix)	{
				$this->model = $this->modelPrefix . $this->model;
			}
		}
		if(!class_exists($this->model))	{
			die("Terminal Error: Non-existent Class [lib_factory selectModel()]");
		}
	}
        private function buildWhere($sql)   {
            if(!empty($this->query)){
                if(is_array($this->query))	{
                    if(count($this->query) > 0)	{
                        $sql .= " WHERE ";
			foreach($this->query as $k => $v)	{
                            if(is_numeric($k))	{
                                // Non key val
                                if(count($this->query) >= 3 && count($this->query) <= 4)	{
                                    $sql .= $v[0] . " " . $v[1] . " '" . $v[2] . "' ";
                                    $sql .= ($v[3] != "") ? $v[3] : "AND";
                                    $sql .= " ";
                                }   else	{
                                        die("Zoek parameters zijn niet in de haak, enkel arrays in arrays met drie parameters.");
                                }
                            }	else	{
                                // key val
                                $sql .=  $k . " = '" . $v . "' AND ";
                            }
                        }
                        $sql = substr($sql,0,-4);
                    }
                }   else	{
                $sql .= " WHERE " . $this->query;
                }
            }
            return $sql;
        }
	private function buildQuery()	{
		$sql = "SELECT ";
		if(is_array($this->cols))	{
			foreach($this->cols as $c)	{
				$sql .= $c . ",";
			}
			$sql = substr($sql,0,-1);
		}	else {
			$sql .= " * "; 
		}
		$sql .= " FROM " . $this->table;

                $sql = $this->buildWhere($sql);
		
		if($this->order != "")	{
			$sql .= " ORDER BY " . $this->order . " " . $this->dir;
		}
		if($this->num != "")	{
			$sql .= " LIMIT " . $this->start . "," . $this->num;
		}
		
		$this->sql = $sql;
	}
	// Deprecated
	public function findOOP()	{
		echo "Deprecated";
		unset($result);
		$this->selectModel();
		$this->buildQuery();
		try {
			$stmt = $this->db->prepare($this->sql);	
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			while($array = $stmt->fetch(PDO::FETCH_ASSOC))	{
				$m = new $mn();
				$m->setFromArray($array);
				$result[] = $m;
			}
		} catch(Exception $e)	{
			$this->error = $e->getMessage();
			$result = false;
			die($this->error);
		}
		return $result;
	}
}
?>