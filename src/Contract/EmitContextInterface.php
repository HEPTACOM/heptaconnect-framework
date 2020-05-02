<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

use Symfony\Component\OptionsResolver\Options;

interface EmitContextInterface extends PortalNodeAwareInterface
{
    public function getConfig(MappingInterface $mapping): Options;
}
