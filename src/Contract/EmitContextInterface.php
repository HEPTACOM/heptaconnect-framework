<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface;
use Symfony\Component\OptionsResolver\Options;

interface EmitContextInterface extends PortalNodeAwareInterface
{
    public function getConfig(MappingInterface $mapping): Options;

    public function emit(DatasetEntityInterface $datasetEntity, MappingInterface $mapping): void;
}
