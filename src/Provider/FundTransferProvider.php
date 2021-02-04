<?php
namespace App\Provider;

use SplFileObject;
use App\AppLogger;
use App\Entity\Balance;
use App\Entity\FundTransfer;
use App\Provider\BalanceProvider;
use App\Provider\BasicProvider;
use App\Util\FileUtil;

class FundTransferProvider extends BasicProvider
{
    public function __construct()
    {
        parent::__construct(FileUtil::getProjectDir().'/src/Data/Balances.csv');
    }
    
    public function newObject()
    {
        return new Balance();
    }
    
    public function saveFund(FundTransfer $fundTransfer, BalanceProvider $provider)
    {
        $accountId = $fundTransfer->getAccountId();
        $currency = $fundTransfer->getCurrency();
        $amount = $fundTransfer->getAmount();
        $transferType = $fundTransfer->getTransferType();
        
        if($transferType == "W") {
          $amount = $amount * -1;
        }
        
        $results = new SplFileObject($this->filePath);
        $results->setFlags(
          SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | 
          SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE
      );
        $balance = null;
        
        foreach ($results as $row) {
            if($accountId == $row[1] && $currency == $row[2]) {
                $balance = new Balance();
                $balance->setFromArray($row);
                break;
            }
        }
        
        if(!isset($balance)) {
          $balance = new Balance();
          $balance->setAccountId($accountId);
          $balance->setCurrency($currency);
        }
        
        $orgAmount = $balance->getAmount();
        if(!isset($orgAmount)) {
          $orgAmount = 0;
        }
        $newAmount = $orgAmount + $amount;
        $balance->setAmount($newAmount);
        
        $provider->saveBalance($balance);

        $results = null;
    }
}
?>