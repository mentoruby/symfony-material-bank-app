<?php
namespace App\Provider;

use SplFileObject;
use App\AppLogger;
use App\Entity\Account;
use App\Util\FileUtil;

class AccountProvider extends BasicProvider
{
    public function __construct()
    {
        parent::__construct(FileUtil::getProjectDir().'/src/Data/Accounts.csv');
    }
    
    public function newObject()
    {
        return new Account();
    }
    
    public function loadAccounts()
    {
        $results = new SplFileObject($this->filePath);
        $results->setFlags(
            SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | 
            SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE
        );
        $list = array();

        foreach ($results as $row) {
            $account = new Account();
            $account->setFromArray($row);
            array_push($list, $account);
        }
        
        $results = null;
        return $list;
    }
    
    public function loadAccountByAccountId($accountId)
    {
        $results = new SplFileObject($this->filePath);
        $results->setFlags(
            SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | 
            SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE
        );

        foreach ($results as $row) {
            if($accountId == $row[0]) {
                $account = new Account();
                $account->setFromArray($row);
                return $account;
            }
        }
        
        $results = null;
        return null;
    }
    
    public function createAccount($inputAcc)
    {
        $results = new SplFileObject($this->filePath);
        $results->setFlags(
            SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | 
            SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE
        );
        
        $maxAccountId = 0;
        foreach ($results as $row) {
            $account = new Account();
            $account->setFromArray($row);
            
            if($account->getAccountId() > $maxAccountId) {
                $maxAccountId = $account->getAccountId();
            }
        }
        $inputAcc->setAccountId(++$maxAccountId);
        
        $this->appendToCsv($inputAcc);
        $results = null;
    }
    
    public function updateAccount($inputAcc)
    {   
        $results = new SplFileObject($this->filePath);
        $results->setFlags(
            SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | 
            SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE
        );
        $list = array();
        
        foreach ($results as $row) {
            $account = new Account();
            $account->setFromArray($row);
            
            if($account->getAccountId() == $inputAcc->getAccountId()) {
              array_push($list, $inputAcc);
            } else {
              array_push($list, $account);
            }
        }
        
        $this->saveToCsv($list);
        $results = null;
    }
}
?>