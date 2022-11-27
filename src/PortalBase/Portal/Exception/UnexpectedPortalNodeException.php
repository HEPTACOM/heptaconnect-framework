<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Exception;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;

class UnexpectedPortalNodeException extends \RuntimeException
{
    protected string $type = 'null';

    public function __construct(?PortalContract $node, int $code = 0, ?\Throwable $previous = null)
    {
        if ($node !== null) {
            $this->type = $node::class;
        }

        parent::__construct('Unexpected portal node of type ' . $this->type, $code, $previous);
    }

    public function getType(): string
    {
        return $this->type;
    }
}
