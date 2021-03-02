<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\StatelessContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Support\Contract\EntityStatusContract;

interface ReceiveContextInterface extends StatelessContextInterface
{
    public function getEntityStatus(): EntityStatusContract;
}
