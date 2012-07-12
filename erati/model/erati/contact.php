<?php

//////////////////////////////////////////////////
//						//
// © Daniel Fortuyn 2012			//
// Ver 2.00a					//
//						//
// Last Edit: Sat, 07 Jul 2012 17:33:11 +0200	//
// CRM 3 update 				//
//						//
// Database:	erati			//
// Tabel:	contact				//
//						//
//////////////////////////////////////////////////


class model_erati_contact extends model_base  {

	protected $id;
	protected $customerId;
	protected $title;
	protected $name;
	protected $type;
	protected $email;
	protected $phone;
	protected $fax;
	protected $mobile;

	public function setId($id)	{
		$this->id = $id;
	}

	public function getId()		{
		return $this->id;
	}

	public function setCustomerId($customerId)	{
		$this->customerId = $customerId;
	}

	public function getCustomerId()		{
		return $this->customerId;
	}

	public function setTitle($title)	{
		$this->title = $title;
	}

	public function getTitle()		{
		return $this->title;
	}

	public function setName($name)	{
		$this->name = $name;
	}

	public function getName()		{
		return $this->name;
	}

	public function setType($type)	{
		$this->type = $type;
	}

	public function getType()		{
		return $this->type;
	}

	public function setEmail($email)	{
		$this->email = $email;
	}

	public function getEmail()		{
		return $this->email;
	}

	public function setPhone($phone)	{
		$this->phone = $this->cleanPhoneNumber($phone);
	}

	public function getPhone()		{
		return $this->phone;
	}

	public function setFax($fax)	{
		$this->fax = $fax;
	}

	public function getFax()		{
		return $this->fax;
	}

	public function setMobile($mobile)	{
		$this->mobile = $this->cleanPhoneNumber($mobile);
	}

	public function getMobile()		{
		return $this->mobile;
	}

	// INSERT //

	function insert()	{
		$reg = application_register::getInstance();
		$db = $reg->mysql->erati;
		$sql = "INSERT INTO contact(id,customerId,title,name,type,email,phone,fax,mobile) VALUES (:id,:customerId,:title,:name,:type,:email,:phone,:fax,:mobile)";
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
		$db = $reg->mysql->erati;
		 $sql = "UPDATE contact SET id= :id,customerId= :customerId,title= :title,name= :name,type= :type,email= :email,phone= :phone,fax= :fax,mobile= :mobile WHERE id =  '" . $this->getId() . "'";
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
		 $sql = "DELETE FROM contact WHERE id = '". $this->getId()."'";
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
		 $stmt->bindParam(':customerId',$this->getCustomerId());
		 $stmt->bindParam(':title',$this->getTitle());
		 $stmt->bindParam(':name',$this->getName());
		 $stmt->bindParam(':type',$this->getType());
		 $stmt->bindParam(':email',$this->getEmail());
		 $stmt->bindParam(':phone',$this->getPhone());
		 $stmt->bindParam(':fax',$this->getFax());
		 $stmt->bindParam(':mobile',$this->getMobile());
		 return $stmt;
	}

	// ToString //

	function __toString()	{
		return "contact";
	}

	 // Validate //

	function validate()	{
		$noNull = array('customerid','title','name','type','email','phone','fax','mobile');
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