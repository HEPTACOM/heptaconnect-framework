<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;

abstract class PortalExtensionContract
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

    /**
     * @return class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
     */
    public abstract function supports(): string;
}
