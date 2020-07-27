<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Router;

use Heptacom\HeptaConnect\Core\Component\Messenger\Message\EmitMessage;
use Heptacom\HeptaConnect\Core\Component\Messenger\Message\PublishMessage;
use Heptacom\HeptaConnect\Core\Emission\Contract\EmitServiceInterface;
use Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface;
use Heptacom\HeptaConnect\Core\Reception\Contract\ReceiveServiceInterface;
use Heptacom\HeptaConnect\Core\Router\Contract\RouterInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Mapping\TypedMappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\TypedMappingCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class Router implements RouterInterface, MessageSubscriberInterface
{
    private EmitServiceInterface $emitService;

    private ReceiveServiceInterface $receiveService;

    private StorageInterface $storage;

    private MappingServiceInterface $mappingService;

    public function __construct(
        EmitServiceInterface $emitService,
        ReceiveServiceInterface $receiveService,
        StorageInterface $storage,
        MappingServiceInterface $mappingService
    ) {
        $this->emitService = $emitService;
        $this->receiveService = $receiveService;
        $this->storage = $storage;
        $this->mappingService = $mappingService;
    }

    public static function getHandledMessages(): iterable
    {
        yield PublishMessage::class => ['method' => 'handlePublishMessage'];
        yield EmitMessage::class => ['method' => 'handleEmitMessage'];
    }

    public function handlePublishMessage(PublishMessage $message): void
    {
        $mapping = $message->getMapping();

        $this->emitService->emit(new TypedMappingCollection($mapping->getDatasetEntityClassName(), [$mapping]));
    }

    public function handleEmitMessage(EmitMessage $message): void
    {
        $mappedDatasetEntityStruct = $message->getMappedDatasetEntityStruct();
        $mapping = $mappedDatasetEntityStruct->getMapping();

        $targetPortalNodeKeys = $this->storage->getRouteTargets(
            $mapping->getPortalNodeKey(),
            $mapping->getDatasetEntityClassName()
        );

        $typedMappedDatasetEntityCollections = [];

        /** @var PortalNodeKeyInterface $targetPortalNodeKey */
        foreach ($targetPortalNodeKeys as $targetPortalNodeKey) {
            $targetMapping = $this->mappingService->reflect($mapping, $targetPortalNodeKey);
            $entityClassName = $targetMapping->getDatasetEntityClassName();

            $typedMappedDatasetEntityCollections[$entityClassName] ??= new TypedMappedDatasetEntityCollection($entityClassName);
            $typedMappedDatasetEntityCollections[$entityClassName]->push([
                new MappedDatasetEntityStruct($targetMapping, $mappedDatasetEntityStruct->getDatasetEntity()),
            ]);
        }

        foreach ($typedMappedDatasetEntityCollections as $typedMappedDatasetEntityCollection) {
            $this->receiveService->receive($typedMappedDatasetEntityCollection);
        }
    }
}
