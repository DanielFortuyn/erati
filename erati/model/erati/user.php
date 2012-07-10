<?php

//////////////////////////////////////////////////
//						//
// © Daniel Fortuyn 2012			//
// Ver 2.00a					//
//						//
// Last Edit: Mon, 09 Jul 2012 18:35:58 +0200	//
// CRM 3 update 				//
//						//
// Database:	erati			//
// Tabel:	user				//
//						//
//////////////////////////////////////////////////


class model_erati_user extends model_base  {

	protected $id;
	protected $name;
	protected $password;
	protected $email;
	protected $level;
	protected $department;
	protected $lastLoginTime;

	public function setId($id)	{
		$this->id = $id;
	}

	public function getId()		{
		return $this->id;
	}

	public function setName($name)	{
		$this->name = $name;
	}

	public function getName()		{
		return $this->name;
	}

	public function setPassword($password)	{
		$this->password = $password;
	}

	public function getPassword()		{
		return $this->password;
	}

	public function setEmail($email)	{
		$this->email = $email;
	}

	public function getEmail()		{
		return $this->email;
	}

	public function setLevel($level)	{
		$this->level = $level;
	}

	public function getLevel()		{
		return $this->level;
	}

	public function setDepartment($department)	{
		$this->department = $department;
	}

	public function getDepartment()		{
		return $this->department;
	}

	public function setLastLoginTime($lastLoginTime)	{
		$this->lastLoginTime = $lastLoginTime;
	}

	public function getLastLoginTime()		{
		return $this->lastLoginTime;
	}

	// INSERT //

	function insert()	{
		$reg = application_register::getInstance();
		$db = $reg->postgresql->erati;
		$sql = "INSERT INTO user(id,name,password,email,level,department,lastLoginTime) VALUES (:id,:name,:password,:email,:level,:department,:lastLoginTime)";
		$stmt = $db->prepare($sql);
		$stmt = $this->bindParams($stmt);
		if(!$stmt->execute())	{;
			$this->resultaat = false;
			$this->errors = $stmt->errorInfo();
		} else {
			$this->setId($db->lastInsertId());
			$this->resultaat = true;
		}
		return $this->resultaat;
	}

	// UPDATE //

	function update()	{
		$reg = application_register::getInstance();
		$db = $reg->postgresql->erati;
		$sql = "UPDATE user SET id= :id,name= :name,password= :password,email= :email,level= :level,department= :department,lastLoginTime= :lastLoginTime WHERE id =  '" . $this->getId() . "'";
		$stmt = $db->prepare($sql);
		$stmt = $this->bindParams($stmt);
		if(!$stmt->execute())	{
			$this->resultaat = false;
			$this->errors = $stmt->errorInfo();
		} else {
			$this->resultaat = true;
		}
		return $this->resultaat;

	}

	// DELETE //

	public function delete()	{
		 $sql = "DELETE FROM user WHERE id = '". $this->getId()."'";
		$reg = application_register::getInstance();
		$db = $reg->mysql->erati;
		 if($db->exec($sql) === false)	{
			 die($db->errorInfo());
		 }
		 return true; 
	}

	// BindParams//

	private function bindParams($stmt)	{
		 $stmt->bindParam(':id',$this->getId());
		 $stmt->bindParam(':name',$this->getName());
		 $stmt->bindParam(':password',$this->getPassword());
		 $stmt->bindParam(':email',$this->getEmail());
		 $stmt->bindParam(':level',$this->getLevel());
		 $stmt->bindParam(':department',$this->getDepartment());
		 $stmt->bindParam(':lastLoginTime',$this->getLastLoginTime());
		 return $stmt;
	}

	// ToString //

	function __toString()	{
		return "user";
	}

	 // Validate //

	function validate()	{
		$noNull = array('name','password','email','level','department','lastlogintime');
		foreach($noNull as $n)	{
			$getMethod = 'get' . ucfirst($n);
			$setMethod = 'set' . ucfirst($n);
			if(!$this->$getMethod())	{
				$this->$setMethod(false);
				if(!$this->$getMethod())	{
					throw new application_exception_mysql("Save niet mogelijk door missende waarde: {$n}");
					return false;
				}
			}
		}
		return true;
	}
}
?>
