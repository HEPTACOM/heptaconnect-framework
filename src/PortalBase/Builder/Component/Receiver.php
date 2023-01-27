<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Dataset\Base\UnsafeClassString;
use Heptacom\HeptaConnect\Portal\Base\Builder\BindThisTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Psr\Container\ContainerInterface;

final class Receiver extends ReceiverContract
{
    use BindThisTrait;
    use ResolveArgumentsTrait;

    private EntityType $entityType;

    private ?\Closure $batchMethod;

    private ?\Closure $runMethod;

    public function __construct(ReceiverToken $token)
    {
        $this->entityType = $token->getEntityType();
        $this->batchMethod = $token->getBatch();
        $this->runMethod = $token->getRun();
    }

    public function getRunMethod(): ?\Closure
    {
        return $this->runMethod;
    }

    public function getBatchMethod(): ?\Closure
    {
        return $this->batchMethod;
    }

    protected function supports(): string
    {
        return (string) $this->entityType;
    }

    protected function batch(
        TypedDatasetEntityCollection $entities,
        ReceiveContextInterface $context
    ): void {
        $batch = $this->batchMethod;

        if ($batch instanceof \Closure) {
            $batch = $this->bindThis($batch);
            $arguments = $this->resolveArguments($batch, $context, function (
                int $_propertyIndex,
                string $propertyName,
                ?string $propertyType,
                ContainerInterface $container
            ) use ($entities) {
                if (\is_string($propertyType) && \is_a($propertyType, TypedDatasetEntityCollection::class, true)) {
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
        $run = $this->runMethod;

        if ($run instanceof \Closure) {
            $run = $this->bindThis($run);
            $arguments = $this->resolveArguments($run, $context, function (
                int $_propertyIndex,
                string $propertyName,
                ?string $propertyType,
                ContainerInterface $container
            ) use ($entity) {
                if (\is_string($propertyType) && $this->getSupportedEntityType()->isClassStringOfType(new UnsafeClassString($propertyType))) {
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
