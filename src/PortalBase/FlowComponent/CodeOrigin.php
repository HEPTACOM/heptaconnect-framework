<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\FlowComponent;

class CodeOrigin
{
    private string $filepath;

    private int $startLine;

    private int $endLine;

    public function __construct(string $filepath, int $startLine, int $endLine)
    {
        $this->filepath = $filepath;
        $this->startLine = $startLine;
        $this->endLine = $endLine;
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

    public function __toString()
    {
        return \sprintf('%s:%d-%d', $this->getFilepath(), $this->getStartLine(), $this->getEndLine());
    }
}
