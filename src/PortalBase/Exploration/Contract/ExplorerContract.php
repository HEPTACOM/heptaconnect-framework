<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidClassNameException;
use Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidSubtypeClassNameException;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\UnsupportedDatasetEntityException;
use Psr\Log\LoggerInterface;

/**
 * Base class for every explorer implementation with various boilerplate-reducing entrypoints for rapid development.
 * When used as source for identities or entities, you need to implement @see supports, run
 * When used as decorator to limit previous exploration, you should implement @see supports, isAllowed
 */
abstract class ExplorerContract
{
    private ?EntityType $supportedEntityType = null;

    /**
     * First entrypoint to handle an exploration in this flow component.
     * It allows direct stack handling manipulation. @see ExplorerStackInterface
     * You most likely want to implement @see run instead.
     *
     * @return iterable<array-key, DatasetEntityContract|string|int>
     */
    public function explore(ExploreContextInterface $context, ExplorerStackInterface $stack): iterable
    {
        yield from $this->exploreCurrent($context);
        yield from $this->exploreNext($context, $stack);
    }

    /**
     * Returns the supported entity type.
     *
     * @throws InvalidClassNameException
     * @throws InvalidSubtypeClassNameException
     */
    final public function getSupportedEntityType(): EntityType
    {
        return $this->supportedEntityType ??= new EntityType($this->supports());
    }

    /**
     * Must return the supported entity type.
     *
     * @return class-string<DatasetEntityContract>
     */
    abstract protected function supports(): string;

    /**
     * The entrypoint for handling an exploration with the least need of additional programming.
     * This is executed when this explorer on the stack is expected to act.
     * It can be skipped when @see explore is implemented accordingly.
     * Returns anything that is either an identity for following emission or an entity that is already of emission quality.
     *
     * @return iterable<array-key, DatasetEntityContract|string|int>
     */
    protected function run(ExploreContextInterface $context): iterable
    {
        return [];
    }

    /**
     * Returns true, when the given entity is an identity or of the supported entity type.
     *
     * @param DatasetEntityContract|string|int|null $entity
     */
    final protected function isSupported($entity): bool
    {
        if ($entity === null) {
            return false;
        }

        return \is_string($entity) || \is_int($entity) || $this->getSupportedEntityType()->isObjectOfType($entity);
    }

    /**
     * Pre-implemented stack handling for processing the next explorer in the stack.
     * Expected to only be called by @see explore
     * It verifies results from the next explorer with @see isAllowed
     *
     * @return iterable<array-key, DatasetEntityContract|string|int>
     */
    final protected function exploreNext(
        ExploreContextInterface $context,
        ExplorerStackInterface $stack
    ): iterable {
        try {
            foreach ($stack->next($context) as $key => $entity) {
                if ($this->isSupported($entity)) {
                    if ($this->performAllowanceCheck($entity, $context)) {
                        yield $key => $entity;
                    }
                } else {
                    throw new UnsupportedDatasetEntityException();
                }
            }
        } catch (\Throwable $exception) {
            /** @var LoggerInterface $logger */
            $logger = $context->getContainer()->get(LoggerInterface::class);
            $logger->critical(\sprintf(
                'FlowComponent explorer encountered exception in exploreNext(): %s',
                $exception->getMessage()
            ));
        }
    }

    /**
     * Pre-implemented stack handling for processing this explorer in the stack.
     * Expected to only be called by @see explore
     *
     * @return iterable<array-key, DatasetEntityContract|string|int>
     */
    final protected function exploreCurrent(ExploreContextInterface $context): iterable
    {
        try {
            foreach ($this->run($context) as $key => $entity) {
                if ($this->isSupported($entity)) {
                    yield $key => $entity;
                } else {
                    throw new UnsupportedDatasetEntityException();
                }
            }
        } catch (\Throwable $exception) {
            /** @var LoggerInterface $logger */
            $logger = $context->getContainer()->get(LoggerInterface::class);
            $logger->critical(\sprintf(
                'FlowComponent explorer encountered exception in exploreCurrent(): %s',
                $exception->getMessage()
            ));
        }
    }

    /**
     * Returns the result of @see isAllowed depending on the scenario of a direct emission or identity list.
     *
     * @param DatasetEntityContract|string|int $entity
     */
    final protected function performAllowanceCheck($entity, ExploreContextInterface $context): bool
    {
        if (\is_string($entity)) {
            return $this->isAllowed($entity, null, $context);
        }

        if (\is_int($entity)) {
            return $this->isAllowed((string) $entity, null, $context);
        }

        $primaryKey = $entity->getPrimaryKey();

        if ($entity instanceof DatasetEntityContract && $primaryKey !== null) {
            return $this->isAllowed($primaryKey, $entity, $context);
        }

        return false;
    }

    /**
     * The entrypoint for handling a filtered exploration with the least need of additional programming.
     * Returns true, when the identity or entity is allowed to pass.
     */
    protected function isAllowed(string $externalId, ?DatasetEntityContract $entity, ExploreContextInterface $context): bool
    {
        return true;
    }
}
