<?php
namespace App\Provider;

use SplFileObject;
use App\AppLogger;
use App\Entity\FundTransfer;
use App\Entity\Transaction;
use App\Provider\BasicProvider;
use App\Util\FileUtil;

class TransactionProvider extends BasicProvider
{
    public function __construct()
    {
        parent::__construct(FileUtil::getProjectDir().'/src/Data/Transactions.csv');
    }
    
    public function newObject()
    {
        return new Transaction();
    }
    
    public function loadTransactionsByAccountId($accountId)
    {
        $results = new SplFileObject($this->filePath);
        $results->setFlags(
            SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | 
            SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE
        );
        $list = array();
        
        foreach ($results as $row) {
            if($accountId == $row[3]) {
                $txn = new Transaction();
                $txn->setFromArray($row);
                array_push($list, $txn);
            }
        }
        
        $results = null;
        return $list;
    }
    
    private function convertFundTransferToTransaction(FundTransfer $fundTransfer) 
    {
        date_default_timezone_set("Asia/Hong_Kong");
        $txn = new Transaction();
        $txn->setTranDate(date('Ymd'));
        $txn->setTranTime(date('H:i:s'));
        $txn->setAccountId($fundTransfer->getAccountId());
        $txn->setTranType($fundTransfer->getTransferType());
        $txn->setTranCurr($fundTransfer->getCurrency());
        $txn->setTranAmount($fundTransfer->getAmount());
        return $txn;
    }
    
    public function saveTransaction(FundTransfer $fundTransfer) 
    {
        $newTran = $this->convertFundTransferToTransaction($fundTransfer);

        $results = new SplFileObject($this->filePath);
        $results->setFlags(
            SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | 
            SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE
        );
        
        $maxTransactionId = 0;
        foreach ($results as $row) {
            $txn = new Transaction();
            $txn->setFromArray($row);
            if($txn->getTranId() > $maxTransactionId) {
                $maxTransactionId = $txn->getTranId();
            }
        }
        $newTran->setTranId(++$maxTransactionId);
        
        $this->appendToCsv($newTran);
        $results = null;
    }
}
?>