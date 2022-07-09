<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityTypeClassString;
use Heptacom\HeptaConnect\Dataset\Base\UnsafeClassString;
use Heptacom\HeptaConnect\Portal\Base\Builder\BindThisTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Exception\InvalidResultException;
use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Opis\Closure\SerializableClosure;
use Psr\Container\ContainerInterface;

final class Explorer extends ExplorerContract
{
    use BindThisTrait;
    use ResolveArgumentsTrait;

    private EntityTypeClassString $entityType;

    private ?SerializableClosure $runMethod;

    private ?SerializableClosure $isAllowedMethod;

    public function __construct(ExplorerToken $token)
    {
        $run = $token->getRun();
        $isAllowed = $token->getIsAllowed();

        $this->entityType = $token->getEntityType();
        $this->runMethod = $run instanceof \Closure ? new SerializableClosure($run) : null;
        $this->isAllowedMethod = $isAllowed instanceof \Closure ? new SerializableClosure($isAllowed) : null;
    }

    public function getRunMethod(): ?\Closure
    {
        return $this->runMethod instanceof SerializableClosure ? $this->runMethod->getClosure() : null;
    }

    public function getIsAllowedMethod(): ?\Closure
    {
        return $this->isAllowedMethod instanceof SerializableClosure ? $this->isAllowedMethod->getClosure() : null;
    }

    protected function supports(): string
    {
        return $this->entityType->getClassString();
    }

    protected function run(ExploreContextInterface $context): iterable
    {
        if ($this->runMethod instanceof SerializableClosure) {
            $run = $this->bindThis($this->runMethod->getClosure());
            $arguments = $this->resolveArguments($run, $context, function (
                int $_propertyIndex,
                string $propertyName,
                ?string $propertyType,
                ContainerInterface $container
            ) {
                return $this->resolveFromContainer($container, $propertyType, $propertyName);
            });

            /** @var mixed $result */
            $result = $run(...$arguments);

            if (\is_iterable($result)) {
                return $this->validateRunResult($result);
            }

            throw new InvalidResultException(1637034101, 'Explorer', 'run', 'iterable of string|int|' . DatasetEntityContract::class);
        }

        return parent::run($context);
    }

    protected function isAllowed(
        string $externalId,
        ?DatasetEntityContract $entity,
        ExploreContextInterface $context
    ): bool {
        if ($this->isAllowedMethod instanceof SerializableClosure) {
            $isAllowed = $this->bindThis($this->isAllowedMethod->getClosure());
            $arguments = $this->resolveArguments($isAllowed, $context, function (
                int $_propertyIndex,
                string $propertyName,
                ?string $propertyType,
                ContainerInterface $container
            ) use ($externalId, $entity) {
                if ($propertyType === 'string') {
                    return $externalId;
                }

                if (\is_string($propertyType) && $this->getSupportedEntityType()->isClassStringOfType(new UnsafeClassString($propertyType))) {
                    return $entity;
                }

                return $this->resolveFromContainer($container, $propertyType, $propertyName);
            });

            /** @var mixed $result */
            $result = $isAllowed(...$arguments);

            if (\is_bool($result)) {
                return $result;
            }

            throw new InvalidResultException(1637034102, 'Explorer', 'isAllowed', 'bool');
        }

        return parent::isAllowed($externalId, $entity, $context);
    }

    /**
     * @throws InvalidResultException
     *
     * @return iterable<array-key, string|int|DatasetEntityContract>
     */
    private function validateRunResult(iterable $result): iterable
    {
        /**
         * @var array-key $resultKey
         * @var mixed     $resultItem
         */
        foreach ($result as $resultKey => $resultItem) {
            if ((\is_string($resultItem) || \is_int($resultItem) || $resultItem instanceof DatasetEntityContract) && $this->isSupported($resultItem)) {
                yield $resultKey => $resultItem;

                continue;
            }

            throw new InvalidResultException(1637034100, 'Explorer', 'run', 'string|int|' . $this->getSupportedEntityType()->getClassString());
        }
    }
}
