<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Reception;

use Heptacom\HeptaConnect\Core\Component\LogMessage;
use Heptacom\HeptaConnect\Core\Event\PostReceptionEvent;
use Heptacom\HeptaConnect\Core\Mapping\Exception\MappingNodeAreUnmergableException;
use Heptacom\HeptaConnect\Core\Reception\Contract\ReceptionActorInterface;
use Heptacom\HeptaConnect\Core\Reception\PostProcessing\SaveMappingsData;
use Heptacom\HeptaConnect\Core\Reception\Support\PrimaryKeyChangesAttachable;
use Heptacom\HeptaConnect\Core\Router\CumulativeMappingException;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepObjectIteratorContract;
use Psr\Log\LoggerInterface;

class ReceptionActor implements ReceptionActorInterface
{
    private LoggerInterface $logger;

    private DeepObjectIteratorContract $deepObjectIterator;

    public function __construct(LoggerInterface $logger, DeepObjectIteratorContract $deepObjectIterator)
    {
        $this->logger = $logger;
        $this->deepObjectIterator = $deepObjectIterator;
    }

    public function performReception(
        TypedDatasetEntityCollection $entities,
        ReceiverStackInterface $stack,
        ReceiveContextInterface $context
    ): void {
        if ($entities->count() < 1) {
            return;
        }

        foreach ($this->deepObjectIterator->iterate($entities) as $object) {
            if (!$object instanceof DatasetEntityContract) {
                continue;
            }

            $attachable = new PrimaryKeyChangesAttachable(\get_class($object));
            $attachable->setForeignKey($object->getPrimaryKey());
            $object->attach($attachable);
        }

        try {
            foreach ($stack->next($entities, $context) as $receivedEntity) {
                $context->getPostProcessingBag()->add(new SaveMappingsData($receivedEntity));
            }
        } catch (\Throwable $exception) {
            $this->logger->critical(LogMessage::RECEIVE_NO_THROW(), [
                'type' => $entities->getType(),
                'portalNodeKey' => $context->getPortalNodeKey(),
                'stack' => $stack,
                'exception' => $exception,
            ]);

            if ($exception instanceof CumulativeMappingException) {
                foreach ($exception->getExceptions() as $innerException) {
                    $errorContext = [];

                    if ($innerException instanceof MappingNodeAreUnmergableException) {
                        $errorContext = [
                            'fromNode' => $innerException->getFrom(),
                            'intoNode' => $innerException->getInto(),
                        ];
                    }

                    $this->logger->critical(LogMessage::RECEIVE_NO_THROW() . '_INNER', [
                        'exception' => $innerException,
                    ] + $errorContext);
                }
            }
        } finally {
            $context->getEventDispatcher()->dispatch(new PostReceptionEvent($context));

            /** @var SaveMappingsData $saveMapping */
            foreach ($context->getPostProcessingBag()->of(SaveMappingsData::class) as $saveMapping) {
                $this->logger->emergency(LogMessage::RECEIVE_NO_SAVE_MAPPINGS_NOT_PROCESSED(), [
                    'entity' => $saveMapping->getEntity(),
                ]);
            }
        }
    }
}