<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Mapping;

use Heptacom\HeptaConnect\Core\Component\Messenger\Message\PublishMessage;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingCollection;
use Heptacom\HeptaConnect\Portal\Base\Publication\Contract\PublisherInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\MappingNodeStructInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class Publisher implements PublisherInterface
{
    private StorageInterface $storage;

    private MessageBusInterface $messageBus;

    public function __construct(StorageInterface $storage, MessageBusInterface $messageBus)
    {
        $this->storage = $storage;
        $this->messageBus = $messageBus;
    }

    public function publish(
        string $datasetEntityClassName,
        PortalNodeKeyInterface $portalNodeId,
        string $externalId
    ): MappingInterface {
        $mappingNode = $this->storage->getMappingNode($datasetEntityClassName, $portalNodeId, $externalId);
        $mappingExists = $mappingNode instanceof MappingNodeStructInterface;

        if (!$mappingExists) {
            [$mappingNode] = $this->storage->createMappingNodes([$datasetEntityClassName], $portalNodeId);
        }

        $mapping = (new MappingStruct($portalNodeId, $mappingNode))->setExternalId($externalId);

        if (!$mappingExists) {
            $this->storage->createMappings(new MappingCollection([$mapping]));
        }

        $this->messageBus->dispatch(new PublishMessage($mapping));

        return $mapping;
    }
}
