<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Opis\Closure\SerializableClosure;
use Psr\Container\ContainerInterface;

class Emitter extends EmitterContract
{
    use ResolveArgumentsTrait;

    private string $type;

    /** @var SerializableClosure|null */
    private $batchMethod;

    /** @var SerializableClosure|null */
    private $runMethod;

    /** @var SerializableClosure|null */
    private $extendMethod;

    public function __construct(EmitterToken $token)
    {
        $this->type = $token->getType();
        $this->batchMethod = \is_callable($token->getBatch()) ? new SerializableClosure($token->getBatch()) : null;
        $this->runMethod = \is_callable($token->getRun()) ? new SerializableClosure($token->getRun()) : null;
        $this->extendMethod = \is_callable($token->getExtend()) ? new SerializableClosure($token->getExtend()) : null;
    }

    public function supports(): string
    {
        return $this->type;
    }

    protected function batch(iterable $externalIds, EmitContextInterface $context): iterable
    {
        if ($this->batchMethod instanceof SerializableClosure &&
            \is_callable($batch = $this->batchMethod->getClosure())) {
            $arguments = $this->resolveArguments($batch, $context, function (
                int $propertyIndex,
                string $propertyName,
                string $propertyType,
                ContainerInterface $container
            ) use ($externalIds) {
                if ($propertyType === 'iterable' && $propertyName === 'externalIds') {
                    return $externalIds;
                }

                return $this->resolveFromContainer($container, $propertyType, $propertyName);
            });

            return $batch(...$arguments);
        }

        return parent::batch($externalIds, $context);
    }

    protected function run(
        string $externalId,
        EmitContextInterface $context
    ): ?DatasetEntityContract {
        if ($this->runMethod instanceof SerializableClosure &&
            \is_callable($run = $this->runMethod->getClosure())) {
            $arguments = $this->resolveArguments($run, $context, function (
                int $propertyIndex,
                string $propertyName,
                string $propertyType,
                ContainerInterface $container
            ) use ($externalId) {
                if ($propertyType === 'string') {
                    return $externalId;
                }

                return $this->resolveFromContainer($container, $propertyType, $propertyName);
            });

            return $run(...$arguments);
        }

        return parent::run($externalId, $context);
    }

    protected function extend(
        DatasetEntityContract $entity,
        EmitContextInterface $context
    ): DatasetEntityContract {
        if ($this->extendMethod instanceof SerializableClosure &&
            \is_callable($extend = $this->extendMethod->getClosure())) {
            $arguments = $this->resolveArguments($extend, $context, function (
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

            return $extend(...$arguments);
        }

        return parent::extend($entity, $context);
    }
}
