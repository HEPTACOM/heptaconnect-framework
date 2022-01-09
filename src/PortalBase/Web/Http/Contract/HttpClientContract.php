<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

use Heptacom\HeptaConnect\Portal\Base\Web\Http\Support\DefaultRequestHeaders;
use Psr\Http\Client\ClientInterface;

abstract class HttpClientContract implements ClientInterface
{
    private ClientInterface $client;

    private DefaultRequestHeaders $defaultRequestHeaders;

    private array $exceptionTriggers = [];

    private int $maxRedirect = 0;

    private int $maxRetry = 0;

    private array $maxWaitTimeout = [];

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->defaultRequestHeaders = new DefaultRequestHeaders();
    }

    public function getDefaultRequestHeaders(): DefaultRequestHeaders
    {
        return $this->defaultRequestHeaders;
    }

    public function withDefaultRequestHeaders(DefaultRequestHeaders $message): self
    {
        $that = clone $this;
        $that->defaultRequestHeaders = $message;

        return $that;
    }

    /**
     * @return array<int>
     */
    public function getExceptionTriggers(): array
    {
        return \array_keys($this->exceptionTriggers);
    }

    public function withExceptionTriggers(int ...$codes): self
    {
        $that = clone $this;

        foreach ($codes as $code) {
            $that->exceptionTriggers[$code] = true;
        }

        return $that;
    }

    public function withoutExceptionTriggers(int ...$codes): self
    {
        $that = clone $this;

        foreach ($codes as $code) {
            unset($that->exceptionTriggers[$code]);
        }

        return $that;
    }

    public function getMaxRedirect(): int
    {
        return $this->maxRedirect;
    }

    public function withMaxRedirect(int $maxRedirect): self
    {
        $that = clone $this;
        $that->maxRedirect = $maxRedirect;

        return $that;
    }

    public function getMaxRetry(): int
    {
        return $this->maxRetry;
    }

    public function withMaxRetry(int $maxRetry): self
    {
        $that = clone $this;
        $that->maxRetry = $maxRetry;

        return $that;
    }

    /**
     * @return array<int, int>
     */
    public function getMaxWaitTimeout(): array
    {
        return $this->maxWaitTimeout;
    }

    public function withMaxWaitTimeout(int $timeout = 10, int $code = 429): self
    {
        $that = clone $this;
        $that->maxWaitTimeout[$code] = $timeout;

        return $that;
    }

    public function withoutMaxWaitTimeout(int $code): self
    {
        $that = clone $this;
        unset($that->maxWaitTimeout[$code]);

        return $that;
    }

    protected function getClient(): ClientInterface
    {
        return $this->client;
    }
}
