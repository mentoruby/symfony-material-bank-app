<?php
namespace App\Provider;

use SplFileObject;
use App\AppLogger;
use App\Entity\Balance;
use App\Util\FileUtil;

class BalanceProvider extends BasicProvider
{
    public function __construct()
    {
        parent::__construct(FileUtil::getProjectDir().'/src/Data/Balances.csv');
    }
    
    public function newObject()
    {
        return new Balance();
    }
    
    public function loadBalancesByAccountId($accountId)
    {
        $results = new SplFileObject($this->filePath);
        $results->setFlags(
            SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | 
            SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE
        );
        $list = array();
        
        foreach ($results as $row) {
            if($accountId == $row[1]) {
                $balance = new Balance();
                $balance->setFromArray($row);
                array_push($list, $balance);
            }
        }
        
        $results = null;
        return $list;
    }
    
    public function saveBalance(Balance $newBalance) 
    {
        $results = new SplFileObject($this->filePath);
        $results->setFlags(
            SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | 
            SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE
        );
        $list = array();
        
        $maxBalanceId = 0;
        foreach ($results as $row) {
            $balance = new Balance();
            $balance->setFromArray($row);
            
            if($newBalance->getBalanceId()!=null && ($balance->getBalanceId() == $newBalance->getBalanceId())) {
              array_push($list, $newBalance);
            } else {
              array_push($list, $balance);
            }
            
            if($balance->getBalanceId() > $maxBalanceId) {
                $maxBalanceId = $balance->getBalanceId();
            }
        }
        
        if($newBalance->getBalanceId() == null) {
          $newBalance->setBalanceId(++$maxBalanceId);
          array_push($list, $newBalance);
        }
        
        $this->saveToCsv($list);
        $results = null;
    }
}
?>