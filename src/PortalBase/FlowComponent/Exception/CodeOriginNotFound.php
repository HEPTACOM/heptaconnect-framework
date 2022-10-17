<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\FlowComponent\Exception;

class CodeOriginNotFound extends \RuntimeException
{
    public function __construct(private object $flowComponent, int $code, ?\Throwable $throwable = null)
    {
        parent::__construct('Could not find code origin of a flow component', $code, $throwable);
    }

    public function getFlowComponent(): object
    {
        return $this->flowComponent;
    }
}
