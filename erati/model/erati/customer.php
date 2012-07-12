<?php

//////////////////////////////////////////////////
//						//
// © Daniel Fortuyn 2012			//
// Ver 2.00a					//
//						//
// Last Edit: Thu, 12 Jul 2012 18:50:53 +0200	//
// CRM 3 update 				//
//						//
// Database:	erati			//
// Tabel:	customer			//
//						//
//////////////////////////////////////////////////


class model_erati_customer extends model_base  {

	protected $id;
	protected $name;
	protected $attention;
	protected $street;
	protected $house;
	protected $zipcode;
	protected $city;
	protected $country;
	protected $phone;
	protected $fax;
	protected $email;
	protected $iban;
	protected $currency;
	protected $vat;
	protected $kvk;
	protected $origin;
	protected $time;
	protected $uid;
	protected $branchId;
	protected $discountGroupId;
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

	public function setAttention($attention)	{
		$this->attention = $attention;
	}

	public function getAttention()		{
		return $this->attention;
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

	public function setZipcode($zipcode)	{
		$this->zipcode = $zipcode;
	}

	public function getZipcode()		{
		return $this->zipcode;
	}

	public function setCity($city)	{
		$this->city = $city;
	}

	public function getCity()		{
		return $this->city;
	}

	public function setCountry($country)	{
		$this->country = $country;
	}

	public function getCountry()		{
		return $this->country;
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

	public function setEmail($email)	{
		$this->email = $email;
	}

	public function getEmail()		{
		return $this->email;
	}

	public function setIban($iban)	{
		$this->iban = $iban;
	}

	public function getIban()		{
		return $this->iban;
	}

	public function setCurrency($currency)	{
		$this->currency = $currency;
	}

	public function getCurrency()		{
		return $this->currency;
	}

	public function setVat($vat)	{
		$this->vat = $vat;
	}

	public function getVat()		{
		return $this->vat;
	}

	public function setKvk($kvk)	{
		$this->kvk = $kvk;
	}

	public function getKvk()		{
		return $this->kvk;
	}

	public function setOrigin($origin)	{
		$this->origin = $origin;
	}

	public function getOrigin()		{
		return $this->origin;
	}

	public function getTime()		{
		return $this->time;
	}

	public function getUid()		{
		return $this->uid;
	}

	public function setBranchId($branchId)	{
		$this->branchId = $branchId;
	}

	public function getBranchId()		{
		return $this->branchId;
	}

	public function setDiscountGroupId($discountGroupId)	{
		$this->discountGroupId = $discountGroupId;
	}

	public function getDiscountGroupId()		{
		return $this->discountGroupId;
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
		$sql = "INSERT INTO customer (id,name,attention,street,house,zipcode,city,country,phone,fax,email,iban,currency,vat,kvk,origin,time,uid,branchId,discountGroupId,data) VALUES (:id,:name,:attention,:street,:house,:zipcode,:city,:country,:phone,:fax,:email,:iban,:currency,:vat,:kvk,:origin,:time,:uid,:branchId,:discountGroupId,:data)";
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
		 $sql = "UPDATE customer SET id= :id,name= :name,attention= :attention,street= :street,house= :house,zipcode= :zipcode,city= :city,country= :country,phone= :phone,fax= :fax,email= :email,iban= :iban,currency= :currency,vat= :vat,kvk= :kvk,origin= :origin,time= :time,uid= :uid,branchId= :branchId,discountGroupId= :discountGroupId,data= :data WHERE id =  '" . $this->getId() . "'";
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
		 $stmt->bindParam(':attention',$this->getAttention());
		 $stmt->bindParam(':street',$this->getStreet());
		 $stmt->bindParam(':house',$this->getHouse());
		 $stmt->bindParam(':zipcode',$this->getZipcode());
		 $stmt->bindParam(':city',$this->getCity());
		 $stmt->bindParam(':country',$this->getCountry());
		 $stmt->bindParam(':phone',$this->getPhone());
		 $stmt->bindParam(':fax',$this->getFax());
		 $stmt->bindParam(':email',$this->getEmail());
		 $stmt->bindParam(':iban',$this->getIban());
		 $stmt->bindParam(':currency',$this->getCurrency());
		 $stmt->bindParam(':vat',$this->getVat());
		 $stmt->bindParam(':kvk',$this->getKvk());
		 $stmt->bindParam(':origin',$this->getOrigin());
		 $stmt->bindParam(':time',$this->getTime());
		 $stmt->bindParam(':uid',$this->getUid());
		 $stmt->bindParam(':branchId',$this->getBranchId());
		 $stmt->bindParam(':discountGroupId',$this->getDiscountGroupId());
		 $stmt->bindParam(':data',$this->getData());
		 return $stmt;
	}

	// ToString //

	function __toString()	{
		return "customer";
	}

	 // Validate //

	function validate()	{
		$noNull = array('name','street','house','zipcode','city','country','phone','fax','email','iban','currency','vat','kvk','origin','time','branchid','discountgroupid','data');
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
