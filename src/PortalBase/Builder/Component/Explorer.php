<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\UnsafeClassString;
use Heptacom\HeptaConnect\Portal\Base\Builder\BindThisTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Exception\InvalidResultException;
use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Psr\Container\ContainerInterface;

final class Explorer extends ExplorerContract
{
    use BindThisTrait;
    use ResolveArgumentsTrait;

    private EntityType $entityType;

    private ?\Closure $runMethod;

    private ?\Closure $isAllowedMethod;

    public function __construct(ExplorerToken $token)
    {
        $this->entityType = $token->getEntityType();
        $this->runMethod = $token->getRun();
        $this->isAllowedMethod = $token->getIsAllowed();
    }

    public function getRunMethod(): ?\Closure
    {
        return $this->runMethod;
    }

    public function getIsAllowedMethod(): ?\Closure
    {
        return $this->isAllowedMethod;
    }

    protected function supports(): string
    {
        return (string) $this->entityType;
    }

    protected function run(ExploreContextInterface $context): iterable
    {
        $run = $this->runMethod;

        if ($run instanceof \Closure) {
            $run = $this->bindThis($run);
            $arguments = $this->resolveArguments($run, $context, fn (
                int $_propertyIndex,
                string $propertyName,
                ?string $propertyType,
                ContainerInterface $container
            ) => $this->resolveFromContainer($container, $propertyType, $propertyName));

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
        $isAllowedMethod = $this->isAllowedMethod;

        if ($isAllowedMethod instanceof \Closure) {
            $isAllowed = $this->bindThis($isAllowedMethod);
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

            throw new InvalidResultException(1637034100, 'Explorer', 'run', 'string|int|' . $this->getSupportedEntityType());
        }
    }
}
