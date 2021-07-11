<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Builder\EmitterToken;
use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Psr\Container\ContainerInterface;

class Emitter extends EmitterContract
{
    use ResolveArgumentsTrait;

    private string $type;

    /** @var callable|null */
    private $batchMethod;

    /** @var callable|null */
    private $runMethod;

    /** @var callable|null */
    private $extendMethod;

    public function __construct(EmitterToken $token)
    {
        $this->type = $token->getType();
        $this->batchMethod = $token->getBatch();
        $this->runMethod = $token->getRun();
        $this->extendMethod = $token->getExtend();
    }

    public function supports(): string
    {
        return $this->type;
    }

    protected function batch(iterable $externalIds, EmitContextInterface $context): iterable
    {
        if (\is_callable($batch = $this->batchMethod)) {
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
        if (\is_callable($run = $this->runMethod)) {
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
        if (\is_callable($extend = $this->extendMethod)) {
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
