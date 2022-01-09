<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Builder\Exception\InvalidResultException;
use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Opis\Closure\SerializableClosure;
use Psr\Container\ContainerInterface;

class Emitter extends EmitterContract
{
    use ResolveArgumentsTrait;

    /**
     * @var class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    private string $type;

    private ?SerializableClosure $batchMethod;

    private ?SerializableClosure $runMethod;

    private ?SerializableClosure $extendMethod;

    public function __construct(EmitterToken $token)
    {
        $batch = $token->getBatch();
        $run = $token->getRun();
        $extend = $token->getExtend();

        $this->type = $token->getType();
        $this->batchMethod = $batch instanceof \Closure ? new SerializableClosure($batch) : null;
        $this->runMethod = $run instanceof \Closure ? new SerializableClosure($run) : null;
        $this->extendMethod = $extend instanceof \Closure ? new SerializableClosure($extend) : null;
    }

    public function supports(): string
    {
        return $this->type;
    }

    public function getRunMethod(): ?\Closure
    {
        return $this->runMethod instanceof SerializableClosure ? $this->runMethod->getClosure() : null;
    }

    public function getBatchMethod(): ?\Closure
    {
        return $this->batchMethod instanceof SerializableClosure ? $this->batchMethod->getClosure() : null;
    }

    public function getExtendMethod(): ?\Closure
    {
        return $this->extendMethod instanceof SerializableClosure ? $this->extendMethod->getClosure() : null;
    }

    protected function batch(iterable $externalIds, EmitContextInterface $context): iterable
    {
        if ($this->batchMethod instanceof SerializableClosure) {
            $batch = $this->batchMethod->getClosure();
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

            /** @var mixed $result */
            $result = $batch(...$arguments);

            if (\is_iterable($result)) {
                return $this->validateBatchResult($result);
            }

            throw new InvalidResultException(1637017869, 'Emitter', 'batch', 'iterable of ' . $this->supports());
        }

        return parent::batch($externalIds, $context);
    }

    protected function run(
        string $externalId,
        EmitContextInterface $context
    ): ?DatasetEntityContract {
        if ($this->runMethod instanceof SerializableClosure) {
            $run = $this->runMethod->getClosure();
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

            /** @var mixed $result */
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
        if ($this->extendMethod instanceof SerializableClosure) {
            $extend = $this->extendMethod->getClosure();
            $arguments = $this->resolveArguments($extend, $context, function (
                int $_propertyIndex,
                string $propertyName,
                ?string $propertyType,
                ContainerInterface $container
            ) use ($entity) {
                if (\is_string($propertyType) && \is_a($propertyType, $this->supports(), true)) {
                    return $entity;
                }

                return $this->resolveFromContainer($container, $propertyType, $propertyName);
            });

            /** @var mixed $result */
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
                throw new InvalidResultException(1637017868, 'Emitter', 'batch', $this->supports());
            }

            yield $resultKey => $resultItem;
        }
    }
}
