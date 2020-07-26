<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Exception;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeInterface;
use Throwable;

class UnexpectedPortalNodeException extends \RuntimeException
{
    protected string $type = 'null';

    public function __construct(?PortalNodeInterface $node, int $code = 0, ?Throwable $previous = null)
    {
        if (!\is_null($node)) {
            $this->type = \get_class($node);
        }

        parent::__construct('Unexpected portal node of type '.$this->type, $code, $previous);
    }

    public function getType(): string
    {
        return $this->type;
    }
}
