<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\UnsafeClassString;
use Heptacom\HeptaConnect\Portal\Base\Builder\BindThisTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Exception\InvalidResultException;
use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Psr\Container\ContainerInterface;

final class Emitter extends EmitterContract
{
    use BindThisTrait;
    use ResolveArgumentsTrait;

    private EntityType $entityType;

    private ?\Closure $batchMethod;

    private ?\Closure $runMethod;

    private ?\Closure $extendMethod;

    public function __construct(EmitterToken $token)
    {
        $this->entityType = $token->getEntityType();
        $this->batchMethod = $token->getBatch();
        $this->runMethod = $token->getRun();
        $this->extendMethod = $token->getExtend();
    }

    public function getRunMethod(): ?\Closure
    {
        return $this->runMethod;
    }

    public function getBatchMethod(): ?\Closure
    {
        return $this->batchMethod;
    }

    public function getExtendMethod(): ?\Closure
    {
        return $this->extendMethod;
    }

    protected function supports(): string
    {
        return (string) $this->entityType;
    }

    protected function batch(iterable $externalIds, EmitContextInterface $context): iterable
    {
        $batch = $this->batchMethod;

        if ($batch instanceof \Closure) {
            $batch = $this->bindThis($batch);
            $arguments = $this->resolveArguments($batch, $context, function (
                int $_propertyIndex,
                string $propertyName,
                ?string $propertyType,
                ContainerInterface $container
            ) use ($externalIds) {
                if ($propertyType === 'iterable' && $propertyName === 'externalIds') {
                    return $externalIds;
                }

                return $this->resolveFromContainer($container, $propertyType, $propertyName);
            });

            $result = $batch(...$arguments);

            if (\is_iterable($result)) {
                return $this->validateBatchResult($result);
            }

            throw new InvalidResultException(1637017869, 'Emitter', 'batch', 'iterable of ' . $this->getSupportedEntityType());
        }

        return parent::batch($externalIds, $context);
    }

    protected function run(
        string $externalId,
        EmitContextInterface $context
    ): ?DatasetEntityContract {
        $run = $this->runMethod;

        if ($run instanceof \Closure) {
            $run = $this->bindThis($run);
            $arguments = $this->resolveArguments($run, $context, function (
                int $_propertyIndex,
                string $propertyName,
                ?string $propertyType,
                ContainerInterface $container
            ) use ($externalId) {
                if ($propertyType === 'string') {
                    return $externalId;
                }

                return $this->resolveFromContainer($container, $propertyType, $propertyName);
            });

            $result = $run(...$arguments);

            if ($result === null || $result instanceof DatasetEntityContract) {
                return $result;
            }

            throw new InvalidResultException(1637017870, 'Emitter', 'run', '?' . DatasetEntityContract::class);
        }

        return parent::run($externalId, $context);
    }

    protected function extend(
        DatasetEntityContract $entity,
        EmitContextInterface $context
    ): DatasetEntityContract {
        $extend = $this->extendMethod;

        if ($extend instanceof \Closure) {
            $extend = $this->bindThis($extend);
            $arguments = $this->resolveArguments($extend, $context, function (
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

            $result = $extend(...$arguments);

            if ($result instanceof DatasetEntityContract) {
                return $result;
            }

            throw new InvalidResultException(1637017871, 'Emitter', 'extend', DatasetEntityContract::class);
        }

        return parent::extend($entity, $context);
    }

    /**
     * @throws InvalidResultException
     *
     * @return iterable<DatasetEntityContract>
     */
    private function validateBatchResult(iterable $result): iterable
    {
        /** @var array-key $resultKey */
        foreach ($result as $resultKey => $resultItem) {
            if (!$resultItem instanceof DatasetEntityContract || !$this->isSupported($resultItem)) {
                throw new InvalidResultException(1637017868, 'Emitter', 'batch', (string) $this->getSupportedEntityType());
            }

            yield $resultKey => $resultItem;
        }
    }
}
