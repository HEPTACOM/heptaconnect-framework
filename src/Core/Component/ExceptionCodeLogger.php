<?php

namespace Heptacom\HeptaConnect\Core\Component;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class ExceptionCodeLogger extends AbstractLogger
{
    private LoggerInterface $decorated;

    public function __construct(LoggerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function log($level, $message, array $context = [])
    {
        $codeMessage = '';
        foreach ($context as $throwable) {
            if ($throwable instanceof \Throwable) {
                $codeMessage .= '['.\get_class($throwable).' Code: '.$throwable->getCode().'] ';
            }
        }
        $this->decorated->log($level, $codeMessage.$message, $context);
    }
}
