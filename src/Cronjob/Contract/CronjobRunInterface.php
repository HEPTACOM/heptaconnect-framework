<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\CronjobRunKeyInterface;

interface CronjobRunInterface extends CronjobInterface
{
    public function getRunKey(): CronjobRunKeyInterface;
}
