<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\ReceiverCollection;

interface PortalNodeExtensionInterface
{
    public function getExplorerDecorators(): ExplorerCollection;

    public function getEmitterDecorators(): EmitterCollection;

    public function getReceiverDecorators(): ReceiverCollection;

    /**
     * @return class-string<\Heptacom\HeptaConnect\Portal\Base\Contract\PortalNodeInterface>
     */
    public function supports(): string;
}
