<?php
namespace App\Entity;

class Account implements CsvLineInterface
{
    private $accountId;
    private $accountName;
    private $status;
    private $passportId;
    private $phone;
    private $address;
    private $currency;
    private $credit;
    
    public function __construct() 
    {
        $this->status = 'Active';
    }
    
    public function set($accountId, $accountName, $status, $passportId, $phone, $address, $currency, $credit)
    {
        $this->accountId = $accountId;
        $this->accountName = $accountName;
        $this->status = $status;
        $this->passportId = $passportId;
        $this->phone = $phone;
        $this->address = $address;
        $this->currency = $currency;
        $this->credit = $credit;
    }
    
    public function setFromArray(array $row)
    {
        $this->accountId = $row[0];
        $this->accountName = $row[1];
        $this->status = $row[2];
        $this->passportId = $row[3];
        $this->phone = $row[4];
        $this->address = $row[5];
        $this->currency = $row[6];
        $this->credit = $row[7];
    }
    
    public function getAccountId()
    {
        return $this->accountId;
    }
	
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }
    
    public function getAccountName()
    {
        return $this->accountName;
    }
	
    public function setAccountName($accountName)
    {
        $this->accountName = $accountName;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
	
    public function setStatus($status)
    {
        $this->status = $status;
    }
    
    public function getPassportId()
    {
        return $this->passportId;
    }
	
    public function setPassportId($passportId)
    {
        $this->passportId = $passportId;
    }
    
    public function getPhone()
    {
        return $this->phone;
    }
	
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
    
    public function getAddress()
    {
        return $this->address;
    }
	
    public function setAddress($address)
    {
        $this->address = $address;
    }
    
    public function getCurrency()
    {
        return $this->currency;
    }
	
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
    
    public function getCredit()
    {
        return $this->credit;
    }
	
    public function setCredit($credit)
    {
        $this->credit = $credit;
    }
    
    public function getActive()
    {
        return ($this->status == 'Active');
    }
    
    public function convertToCsvArray()
    {
        $line = array();
        array_push($line,$this->accountId);
        array_push($line,$this->accountName);
        array_push($line,$this->status);
        array_push($line,$this->passportId);
        array_push($line,$this->phone);
        array_push($line,$this->address);
        array_push($line,$this->currency);
        array_push($line,$this->credit);
        return $line;
    }
}

?>