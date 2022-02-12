<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 12.02.2022
 * Time: 13:17
*/

namespace App\Service\LoggerService;

use Psr\Log\LoggerInterface;

final class LoggerService
{
    private const logFormat = '*** [%s]: [%s] =>';
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    public function debug(string $identifier, $message = null, $params = null) {
        $desc = sprintf(self::logFormat, $identifier, $message);
        $this->logger->debug($desc, $params);
    }

    public function error(string $identifier, $message = null, $params = []) {
        $desc = sprintf(self::logFormat, $identifier, $message);
        $this->logger->debug($desc, $params);
    }

    public function warning(string $identifier, $message = null, $params = []) {
        $desc = sprintf(self::logFormat, $identifier, $message);
        $this->logger->debug($desc, $params);
    }

    public function info(string $identifier, $message = null, $params = []) {
        $desc = sprintf(self::logFormat, $identifier, $message);
        $this->logger->debug($desc, $params);
    }

    public function success(string $identifier, $message = null, $params = []) {
        $desc = sprintf(self::logFormat, $identifier, $message);
        $this->logger->debug($desc, $params);
    }
}
