<?php
namespace App\Entity;

class Transaction implements CsvLineInterface
{   
    private $tranId;
    private $tranDate;
    private $tranTime;
    private $accountId;
    private $tranType;
    private $tranCurr;
    private $tranAmount;
    
    public function __construct() 
    {
    }
    
    public function set($tranId, $tranDate, $tranTime, $accountId, $tranType, $tranCurr, $tranAmount)
    {
        $this->tranId = $tranId;
        $this->tranDate = $tranDate;
        $this->tranTime = $tranTime;
        $this->accountId = $accountId;
        $this->tranType = $tranType;
        $this->tranCurr = $tranCurr;
        $this->tranAmount = $tranAmount;
    }
    
    public function setFromArray(array $row)
    {
        $this->tranId = $row[0];
        $this->tranDate = $row[1];
        $this->tranTime = $row[2];
        $this->accountId = $row[3];
        $this->tranType = $row[4];
        $this->tranCurr = $row[5];
        $this->tranAmount = $row[6];
    }
    
    public function getTranId()
    {
        return $this->tranId;
    }
	
    public function setTranId($tranId)
    {
        $this->tranId = $tranId;
    }
    
    public function getTranDate()
    {
        return $this->tranDate;
    }
	
    public function setTranDate($tranDate)
    {
        $this->tranDate = $tranDate;
    }
    
    public function getTranTime()
    {
        return $this->tranTime;
    }
	
    public function setTranTime($tranTime)
    {
        $this->tranTime = $tranTime;
    }
    
    public function getAccountId()
    {
        return $this->accountId;
    }
	
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }
    
    public function getTranType()
    {
        return $this->tranType;
    }
	
    public function setTranType($tranType)
    {
        $this->tranType = $tranType;
    }
    
    public function getTranCurr()
    {
        return $this->tranCurr;
    }
	
    public function setTranCurr($tranCurr)
    {
        $this->tranCurr = $tranCurr;
    }
    
    public function getTranAmount()
    {
        return $this->tranAmount;
    }
	
    public function setTranAmount($tranAmount)
    {
        $this->tranAmount = $tranAmount;
    }
    
    public function convertToCsvArray()
    {   
        $line = array();
        array_push($line,$this->tranId);
        array_push($line,$this->tranDate);
        array_push($line,$this->tranTime);
        array_push($line,$this->accountId);
        array_push($line,$this->tranType);
        array_push($line,$this->tranCurr);
        array_push($line,$this->tranAmount);
        return $line;
    }
}
?>