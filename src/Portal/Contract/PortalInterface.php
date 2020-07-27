<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;

interface PortalInterface
{
    public function getExplorers(): ExplorerCollection;

    public function getEmitters(): EmitterCollection;

    public function getReceivers(): ReceiverCollection;
}
