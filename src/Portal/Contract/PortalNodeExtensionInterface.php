<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;

interface PortalNodeExtensionInterface
{
    public function getExplorerDecorators(): ExplorerCollection;

    public function getEmitterDecorators(): EmitterCollection;

    public function getReceiverDecorators(): ReceiverCollection;

    /**
     * @return class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeInterface>
     */
    public function supports(): string;
}
