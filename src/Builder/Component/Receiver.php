<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Builder\ReceiverToken;
use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Psr\Container\ContainerInterface;

class Receiver extends ReceiverContract
{
    use ResolveArgumentsTrait;

    private string $type;

    /** @var callable|null */
    private $batchMethod;

    /** @var callable|null */
    private $runMethod;

    public function __construct(ReceiverToken $token)
    {
        $this->type = $token->getType();
        $this->batchMethod = $token->getBatch();
        $this->runMethod = $token->getRun();
    }

    public function supports(): string
    {
        return $this->type;
    }

    protected function batch(
        TypedDatasetEntityCollection $entities,
        ReceiveContextInterface $context
    ): void {
        if (\is_callable($batch = $this->batchMethod)) {
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
        if (\is_callable($run = $this->runMethod)) {
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
