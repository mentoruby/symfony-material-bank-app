<?php
namespace App\Entity;

class Balance implements CsvLineInterface
{
    private $balanceId;
    private $accountId;
    private $currency;
    private $amount;
    
    public function __construct() 
    {
    }
    
    public function set($balanceId, $accountId, $currency, $amount)
    {
        $this->balanceId = $balanceId;
        $this->accountId = $accountId;
        $this->currency = $currency;
        $this->amount = $amount;
    }
    
    public function setFromArray(array $row)
    {
        $this->balanceId = $row[0];
        $this->accountId = $row[1];
        $this->currency = $row[2];
        $this->amount = $row[3];
    }
    
    public function getBalanceId()
    {
        return $this->balanceId;
    }
	
    public function setBalanceId($balanceId)
    {
        $this->balanceId = $balanceId;
    }
    
    public function getAccountId()
    {
        return $this->accountId;
    }
	
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }
    
    public function getCurrency()
    {
        return $this->currency;
    }
	
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
    
    public function getAmount()
    {
        return $this->amount;
    }
	
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
    
    public function convertToCsvArray()
    {
        $line = array();
        array_push($line,$this->balanceId);
        array_push($line,$this->accountId);
        array_push($line,$this->currency);
        array_push($line,$this->amount);
        return $line;
    }
}
?>