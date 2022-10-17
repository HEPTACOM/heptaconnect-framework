<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\FlowComponent;

final class CodeOrigin implements \Stringable
{
    public function __construct(
        private string $filepath,
        private int $startLine,
        private int $endLine
    ) {
    }

    public function __toString(): string
    {
        return \sprintf('%s:%d-%d', $this->getFilepath(), $this->getStartLine(), $this->getEndLine());
    }

    public function getFilepath(): string
    {
        return $this->filepath;
    }

    public function getStartLine(): int
    {
        return $this->startLine;
    }

    public function getEndLine(): int
    {
        return $this->endLine;
    }
}
