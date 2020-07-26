<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\ReceiverCollection;

interface PortalNodeInterface
{
    public function getExplorers(): ExplorerCollection;

    public function getEmitters(): EmitterCollection;

    public function getReceivers(): ReceiverCollection;
}
