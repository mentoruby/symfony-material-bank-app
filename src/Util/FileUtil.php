<?php
namespace App\Util;

class FileUtil {
    
    private static $projectDir;
    
    public static function init($projectDir)
    {
        if(!isset(self::$projectDir)) {
            self::$projectDir = $projectDir;
        }
    }
    
    public static function getProjectDir()
    {
        return self::$projectDir;
    }
}
?>