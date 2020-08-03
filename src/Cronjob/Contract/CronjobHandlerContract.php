<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract;

abstract class CronjobHandlerContract
{
    abstract public function handle(CronjobInterface $cronjob): void;

    abstract protected function getDecorated(): CronjobHandlerContract;
}
