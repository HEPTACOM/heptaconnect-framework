<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\FlowComponent\Exception;

use RuntimeException;
use Throwable;

class CodeOriginNotFound extends RuntimeException
{
    private object $flowComponent;

    public function __construct(object $flowComponent, int $code, ?Throwable $throwable = null)
    {
        parent::__construct('Could not find code origin of a flow component', $code, $throwable);
        $this->flowComponent = $flowComponent;
    }

    public function getFlowComponent(): object
    {
        return $this->flowComponent;
    }
}
