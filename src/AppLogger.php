<?php
namespace App;

use Psr\Log\LoggerInterface;

class AppLogger {
    private static $logger;
   
    public static function init(LoggerInterface $logger)
    {
        if(!isset(self::$logger)) {
            self::$logger = $logger;
        }
    }

    public static function getLogger()
    {
        return self::$logger;
    }
}
?>