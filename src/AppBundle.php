<?php

namespace App;

use App\AppLogger;
use App\Util\FileUtil;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function boot()
    {
        parent::boot();
        AppLogger::init($this->container->get('monolog.logger.app'));
        FileUtil::init($this->container->get('kernel')->getProjectDir());
    }
}
?>