<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

use Heptacom\HeptaConnect\Portal\Base\Web\Http\Support\DefaultRequestHeaders;
use Psr\Http\Client\ClientInterface;

/**
 * HTTP client that wraps around the PSR-18 @see ClientInterface with configurable behaviour for common use-cases.
 */
abstract class HttpClientContract implements ClientInterface
{
    private ClientInterface $client;

    private DefaultRequestHeaders $defaultRequestHeaders;

    /**
     * @var array<int, bool>
     */
    private array $exceptionTriggers = [];

    private int $maxRedirect = 0;

    private int $maxRetry = 0;

    /**
     * @var array<int, int>
     */
    private array $maxWaitTimeout = [];

    /**
     * Set the underlying use PSR-18 @see ClientInterface
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->defaultRequestHeaders = new DefaultRequestHeaders();
    }

    /**
     * Get the request header configurations that are applied to any request unless they are already present.
     */
    public function getDefaultRequestHeaders(): DefaultRequestHeaders
    {
        return $this->defaultRequestHeaders;
    }

    /**
     * Set the request header configurations that are applied to any request unless they are already present.
     */
    public function withDefaultRequestHeaders(DefaultRequestHeaders $defaultRequestHeaders): self
    {
        $that = clone $this;
        $that->defaultRequestHeaders = $defaultRequestHeaders;

        return $that;
    }

    /**
     * Get the HTTP response status codes that will throw an exception.
     *
     * @return array<int>
     */
    public function getExceptionTriggers(): array
    {
        return \array_keys($this->exceptionTriggers);
    }

    /**
     * Add HTTP response status codes that will throw an exception.
     */
    public function withExceptionTriggers(int ...$codes): self
    {
        $that = clone $this;

        foreach ($codes as $code) {
            $that->exceptionTriggers[$code] = true;
        }

        return $that;
    }

    /**
     * Remove HTTP response status codes, so they will not throw an exception.
     */
    public function withoutExceptionTriggers(int ...$codes): self
    {
        $that = clone $this;

        foreach ($codes as $code) {
            unset($that->exceptionTriggers[$code]);
        }

        return $that;
    }

    /**
     * Get the number of automatically processed redirects.
     * Defaults to 0.
     */
    public function getMaxRedirect(): int
    {
        return $this->maxRedirect;
    }

    /**
     * Sets the number of automatically processed redirects.
     */
    public function withMaxRedirect(int $maxRedirect): self
    {
        $that = clone $this;
        $that->maxRedirect = $maxRedirect;

        return $that;
    }

    /**
     * Get the number of automatically processed retries.
     * Defaults to 0.
     */
    public function getMaxRetry(): int
    {
        return $this->maxRetry;
    }

    /**
     * Sets the number of automatically processed retries.
     */
    public function withMaxRetry(int $maxRetry): self
    {
        $that = clone $this;
        $that->maxRetry = $maxRetry;

        return $that;
    }

    /**
     * Get the maximum time in seconds allowed to wait between retries per HTTP status code.
     *
     * @return array<int, int>
     */
    public function getMaxWaitTimeout(): array
    {
        return $this->maxWaitTimeout;
    }

    /**
     * Add the maximum time allowed timeout in seconds for an HTTP status code.
     */
    public function withMaxWaitTimeout(int $timeout = 10, int $code = 429): self
    {
        $that = clone $this;
        $that->maxWaitTimeout[$code] = $timeout;

        return $that;
    }

    /**
     * Remove the wait timeout for an HTTP status code.
     */
    public function withoutMaxWaitTimeout(int $code): self
    {
        $that = clone $this;
        unset($that->maxWaitTimeout[$code]);

        return $that;
    }

    /**
     * Get the underlying use PSR-18 @see ClientInterface
     */
    protected function getClient(): ClientInterface
    {
        return $this->client;
    }
}
