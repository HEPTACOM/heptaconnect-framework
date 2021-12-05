<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract;

interface JobInterface
{
    public function getJobType(): string;

    public function getMapping(): MappingComponentStructContract;

    public function getPayloadKey(): ?JobPayloadKeyInterface;
}
