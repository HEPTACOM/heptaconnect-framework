<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Support\PostProcessorDataBag;
use Heptacom\HeptaConnect\Portal\Base\Support\Contract\EntityStatusContract;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Describes reception specific contexts.
 *
 * @see ReceiverContract
 * @see ReceiverStackInterface
 */
interface ReceiveContextInterface extends PortalNodeContextInterface
{
    /**
     * Gets access to a component that can provide info on an entity's status.
     */
    public function getEntityStatus(): EntityStatusContract;

    /**
     * Store an exception attached to the given identity to be reviewed later.
     */
    public function markAsFailed(DatasetEntityContract $entity, \Throwable $throwable): void;

    /**
     * Gets the event dispatcher for post reception events.
     */
    public function getEventDispatcher(): EventDispatcherInterface;

    /**
     * Gets the data bag to store additional information for the post reception process.
     */
    public function getPostProcessingBag(): PostProcessorDataBag;
}
