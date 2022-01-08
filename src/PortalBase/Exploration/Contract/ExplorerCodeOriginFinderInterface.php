<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration\Contract;

use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\Exception\CodeOriginNotFound;

interface ExplorerCodeOriginFinderInterface
{
    /**
     * @throws CodeOriginNotFound
     */
    public function findOrigin(ExplorerContract $explorer): CodeOrigin;
}
