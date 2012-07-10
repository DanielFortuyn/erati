<?php

//////////////////////////////////////////////////
//						//
// © Daniel Fortuyn 2012			//
// Ver 2.00a					//
//						//
// Last Edit: Sat, 07 Jul 2012 16:29:37 +0200	//
// CRM 3 update 				//
//						//
// Database:	erati			//
// Tabel:	customer				//
//						//
//////////////////////////////////////////////////


class model_erati_customer extends model_base  {

	protected $id;
	protected $name;
	protected $street;
	protected $house;
	protected $zipCode;
	protected $city;
	protected $discountGroup;
	protected $phone;
	protected $fax;
	protected $priceGroup;
	protected $IBAN;
	protected $currencyCode;
	protected $email;
	protected $VAT;
	protected $kvk;
	protected $branchCode;
	protected $data;

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

	public function setStreet($street)	{
		$this->street = $street;
	}

	public function getStreet()		{
		return $this->street;
	}

	public function setHouse($house)	{
		$this->house = $house;
	}

	public function getHouse()		{
		return $this->house;
	}

	public function setZipCode($zipCode)	{
		$this->zipCode = $zipCode;
	}

	public function getZipCode()		{
		return $this->zipCode;
	}

	public function setCity($city)	{
		$this->city = $city;
	}

	public function getCity()		{
		return $this->city;
	}

	public function setDiscountGroup($discountGroup)	{
		$this->discountGroup = $discountGroup;
	}

	public function getDiscountGroup()		{
		return $this->discountGroup;
	}

	public function setPhone($phone)	{
		$this->phone = $phone;
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

	public function setPriceGroup($priceGroup)	{
		$this->priceGroup = $priceGroup;
	}

	public function getPriceGroup()		{
		return $this->priceGroup;
	}

	public function setIBAN($IBAN)	{
		$this->IBAN = $IBAN;
	}

	public function getIBAN()		{
		return $this->IBAN;
	}

	public function setCurrencyCode($currencyCode)	{
		$this->currencyCode = $currencyCode;
	}

	public function getCurrencyCode()		{
		return $this->currencyCode;
	}

	public function setEmail($email)	{
		$this->email = $email;
	}

	public function getEmail()		{
		return $this->email;
	}

	public function setVAT($VAT)	{
		$this->VAT = $VAT;
	}

	public function getVAT()		{
		return $this->VAT;
	}

	public function setKvk($kvk)	{
		$this->kvk = $kvk;
	}

	public function getKvk()		{
		return $this->kvk;
	}

	public function setBranchCode($branchCode)	{
		$this->branchCode = $branchCode;
	}

	public function getBranchCode()		{
		return $this->branchCode;
	}

	public function setData($data)	{
		$this->data = $data;
	}

	public function getData()		{
		return $this->data;
	}

	// INSERT //

	function insert()	{
		$reg = application_register::getInstance();
		$db = $reg->mysql->erati;
		$sql = "INSERT INTO customer(id,name,street,house,zipCode,city,discountGroup,phone,fax,priceGroup,IBAN,currencyCode,email,VAT,kvk,branchCode,data) VALUES (:id,:name,:street,:house,:zipCode,:city,:discountGroup,:phone,:fax,:priceGroup,:IBAN,:currencyCode,:email,:VAT,:kvk,:branchCode,:data)";
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
		 $sql = "UPDATE customer SET id= :id,name= :name,street= :street,house= :house,zipCode= :zipCode,city= :city,discountGroup= :discountGroup,phone= :phone,fax= :fax,priceGroup= :priceGroup,IBAN= :IBAN,currencyCode= :currencyCode,email= :email,VAT= :VAT,kvk= :kvk,branchCode= :branchCode,data= :data WHERE id =  '" . $this->getId() . "'";
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
		 $sql = "DELETE FROM customer WHERE id = '". $this->getId()."'";
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
		 $stmt->bindParam(':street',$this->getStreet());
		 $stmt->bindParam(':house',$this->getHouse());
		 $stmt->bindParam(':zipCode',$this->getZipCode());
		 $stmt->bindParam(':city',$this->getCity());
		 $stmt->bindParam(':discountGroup',$this->getDiscountGroup());
		 $stmt->bindParam(':phone',$this->getPhone());
		 $stmt->bindParam(':fax',$this->getFax());
		 $stmt->bindParam(':priceGroup',$this->getPriceGroup());
		 $stmt->bindParam(':IBAN',$this->getIBAN());
		 $stmt->bindParam(':currencyCode',$this->getCurrencyCode());
		 $stmt->bindParam(':email',$this->getEmail());
		 $stmt->bindParam(':VAT',$this->getVAT());
		 $stmt->bindParam(':kvk',$this->getKvk());
		 $stmt->bindParam(':branchCode',$this->getBranchCode());
		 $stmt->bindParam(':data',$this->getData());
		 return $stmt;
	}

	// ToString //

	function __toString()	{
		return "customer";
	}

	 // Validate //

	function validate()	{
		$noNull = array('name','street','house','zipcode','city','discountgroup','phone','fax','pricegroup','iban','currencycode','email','vat','kvk','branchcode','data');
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