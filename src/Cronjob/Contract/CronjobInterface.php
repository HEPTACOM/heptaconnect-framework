<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\CronjobKeyInterface;

interface CronjobInterface
{
    public function getKey(): CronjobKeyInterface;

    public function getCronExpression(): string;

    /**
     * @return class-string<\Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobHandlerContract>
     */
    public function getHandler(): string;

    public function getPayload(): ?array;
}
