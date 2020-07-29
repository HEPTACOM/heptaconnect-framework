<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class PortalContract
{
    public function getExplorers(): ExplorerCollection
    {
        return new ExplorerCollection();
    }

    public function getEmitters(): EmitterCollection
    {
        return new EmitterCollection();
    }

    public function getReceivers(): ReceiverCollection
    {
        return new ReceiverCollection();
    }

    public function getConfigurationTemplate(): OptionsResolver
    {
        return new OptionsResolver();
    }
}
