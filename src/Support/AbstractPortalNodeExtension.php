<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Support;

use Heptacom\HeptaConnect\Portal\Base\Contract\PortalNodeInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeExtensionInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;

abstract class AbstractPortalNodeExtension implements PortalNodeExtensionInterface
{
    public function getExplorerDecorators(): ExplorerCollection
    {
        return new ExplorerCollection();
    }

    public function getEmitterDecorators(): EmitterCollection
    {
        return new EmitterCollection();
    }

    public function getReceiverDecorators(): ReceiverCollection
    {
        return new ReceiverCollection();
    }

    public function supports(): string
    {
        return PortalNodeInterface::class;
    }
}
