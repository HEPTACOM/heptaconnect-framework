<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

interface MappingNodeStructInterface
{
    public function getId(): string;

    /**
     * @return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    public function getDatasetEntityClassName(): string;
}
