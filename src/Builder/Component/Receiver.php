<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Builder\ReceiverToken;
use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Opis\Closure\SerializableClosure;
use Psr\Container\ContainerInterface;

class Receiver extends ReceiverContract
{
    use ResolveArgumentsTrait;

    private string $type;

    /** @var SerializableClosure|null */
    private $batchMethod;

    /** @var SerializableClosure|null */
    private $runMethod;

    public function __construct(ReceiverToken $token)
    {
        $this->type = $token->getType();
        $this->batchMethod = \is_callable($token->getBatch()) ? new SerializableClosure($token->getBatch()) : null;
        $this->runMethod = \is_callable($token->getRun()) ? new SerializableClosure($token->getRun()) : null;
    }

    public function supports(): string
    {
        return $this->type;
    }

    protected function batch(
        TypedDatasetEntityCollection $entities,
        ReceiveContextInterface $context
    ): void {
        if ($this->batchMethod instanceof SerializableClosure &&
            \is_callable($batch = $this->batchMethod->getClosure())) {
            $arguments = $this->resolveArguments($batch, $context, function (
                int $propertyIndex,
                string $propertyName,
                string $propertyType,
                ContainerInterface $container
            ) use ($entities) {
                if (\is_a($propertyType, TypedDatasetEntityCollection::class, true)) {
                    return $entities;
                }

                return $this->resolveFromContainer($container, $propertyType, $propertyName);
            });

            $batch(...$arguments);

            return;
        }

        parent::batch($entities, $context);
    }

    protected function run(
        DatasetEntityContract $entity,
        ReceiveContextInterface $context
    ): void {
        if ($this->runMethod instanceof SerializableClosure &&
            \is_callable($run = $this->runMethod->getClosure())) {
            $arguments = $this->resolveArguments($run, $context, function (
                int $propertyIndex,
                string $propertyName,
                string $propertyType,
                ContainerInterface $container
            ) use ($entity) {
                if (\is_a($propertyType, $this->supports(), true)) {
                    return $entity;
                }

                return $this->resolveFromContainer($container, $propertyType, $propertyName);
            });

            $run(...$arguments);

            return;
        }

        parent::run($entity, $context);
    }
}
