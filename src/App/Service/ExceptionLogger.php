<?php

declare(strict_types=1);

namespace App\Service;

use Throwable;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ExceptionLogger
{
    public function __construct(private Logger $logger)
    {}

    public function log(Throwable $e): bool
    {
        $consoleHandler = new StreamHandler(STDOUT);
        $this->logger->pushHandler($consoleHandler);
        $class = explode('\\', get_class($e));
        $this->logger->error($e->getMessage() . ' line:' . $e->getLine() .
            ' file:' . $e->getFile() . ' error:' . $class[count($class) - 1]);

        return true;
    }
}