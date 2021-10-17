<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception\Contract;

use Heptacom\HeptaConnect\Core\Reception\Support\PostProcessorDataBag;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Support\Contract\EntityStatusContract;
use Psr\EventDispatcher\EventDispatcherInterface;

interface ReceiveContextInterface extends PortalNodeContextInterface
{
    public function getEntityStatus(): EntityStatusContract;

    public function getEventDispatcher() : EventDispatcherInterface;

    public function getPostProcessingBag(): PostProcessorDataBag;
}
