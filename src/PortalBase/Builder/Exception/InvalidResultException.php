<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Exception;

class InvalidResultException extends \RuntimeException
{
    private string $flowComponent;

    private string $method;

    private string $expected;

    public function __construct(
        int $code,
        string $flowComponent,
        string $method,
        string $expected,
        ?\Throwable $previous = null
    ) {
        $message = \sprintf('Short-noted %s failed in %s to return %s', $flowComponent, $method, $expected);

        parent::__construct($message, $code, $previous);
        $this->flowComponent = $flowComponent;
        $this->method = $method;
        $this->expected = $expected;
    }

    public function getFlowComponent(): string
    {
        return $this->flowComponent;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getExpected(): string
    {
        return $this->expected;
    }
}
