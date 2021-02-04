<?php
namespace App\Entity;

class FundTransfer
{
    private $accountId;
    private $transferType;
    private $currency;
    private $amount;
    
    public function set($accountId, $transferType, $currency, $amount)
    {
        $this->accountId = $accountId;
        $this->transferType = $transferType;
        $this->currency = $currency;
        $this->amount = $amount;
    }
    
    public function getAccountId()
    {
        return $this->accountId;
    }
	
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }
    
    public function getTransferType()
    {
        return $this->transferType;
    }
	
    public function setTransferType($transferType)
    {
        $this->transferType = $transferType;
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
}
?>