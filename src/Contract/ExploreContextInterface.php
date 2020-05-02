<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

use Symfony\Component\OptionsResolver\Options;

interface ExploreContextInterface
{
    public function getPortalNode(): PortalNodeInterface;

    public function getConfig(): Options;
}
