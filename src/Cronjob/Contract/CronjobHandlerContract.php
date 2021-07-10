<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract;

/**
 * @internal
 */
abstract class CronjobHandlerContract
{
    abstract public function handle(CronjobContextInterface $context): void;

    abstract protected function getDecorated(): CronjobHandlerContract;
}
