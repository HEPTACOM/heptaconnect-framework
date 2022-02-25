<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Support;

class DefaultRequestHeaders
{
    private array $headers = [];

    /**
     * @return array<string, array<string>>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $header): bool
    {
        return isset($this->headers[\strtolower($header)]);
    }

    /**
     * @return array<string>
     */
    public function getHeader(string $header): array
    {
        return $this->headers[\strtolower($header)] ?? [];
    }

    public function getHeaderLine(string $header): string
    {
        return \implode(', ', $this->getHeader($header));
    }

    public function withHeader(string $header, array $values): self
    {
        $that = clone $this;
        $that->headers[\strtolower($header)] = \array_values($values);

        return $that;
    }

    public function withAddedHeader(string $header, array $values): self
    {
        return $this->withHeader(
            $header,
            \array_merge($this->getHeader($header), \array_values($values))
        );
    }

    public function withoutHeader($header): self
    {
        $that = clone $this;
        unset($that->headers[\strtolower($header)]);

        return $that;
    }
}
