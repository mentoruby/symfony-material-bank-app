<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class MessageGenerator
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function info(string $message)
    {
        $this->logger->info($message);
    }

    public function debug(string $message)
    {
        $this->logger->debug($message);
    }

    public function error(string $message)
    {
        $this->logger->error($message);
    }
}
?>