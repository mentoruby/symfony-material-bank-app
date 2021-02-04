<?php
namespace App\Provider;

use App\Entity\CsvLineInterface;
use SplFileObject;

abstract class BasicProvider
{
    protected $filePath;
    
    public function __construct($filePath) 
    {
        $this->filePath = $filePath;
    }
    
    abstract public function newObject();
    
    public function loadAll()
    {
        $results = new SplFileObject($this->filePath);
        $results->setFlags(
            SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | 
            SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE
        );
        $objects = array();

        foreach ($results as $row) {
            $object = $this->newObject();
            $object->setFromArray($row);
            array_push($objects, $object);
        }
        
        $results = null;
        return $objects;
    }
    
    public function appendToCsv(CsvLineInterface $obj)
    {   
        $writer = new SplFileObject($this->filePath, 'a');
        $writer->fputcsv($obj->convertToCsvArray());
        $writer = null;
    }
    
    public function saveToCsv(array $objects)
    {   
        $writer = new SplFileObject($this->filePath, 'w');
        foreach ($objects as $obj) {
            $writer->fputcsv($obj->convertToCsvArray());
        }
        $writer = null;
    }
}

?>