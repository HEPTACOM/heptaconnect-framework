<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Closure;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Opis\Closure\SerializableClosure;
use Psr\Container\ContainerInterface;

class Explorer extends ExplorerContract
{
    use ResolveArgumentsTrait;

    private string $type;

    /** @var SerializableClosure|null */
    private $runMethod;

    /** @var SerializableClosure|null */
    private $isAllowedMethod;

    public function __construct(ExplorerToken $token)
    {
        $run = $token->getRun();
        $isAllowed = $token->getIsAllowed();

        $this->type = $token->getType();
        $this->runMethod = $run instanceof Closure ? new SerializableClosure($run) : null;
        $this->isAllowedMethod = $isAllowed instanceof Closure ? new SerializableClosure($isAllowed) : null;
    }

    public function supports(): string
    {
        return $this->type;
    }

    protected function run(ExploreContextInterface $context): iterable
    {
        if ($this->runMethod instanceof SerializableClosure &&
            \is_callable($run = $this->runMethod->getClosure())) {
            $arguments = $this->resolveArguments($run, $context, function (
                int $propertyIndex,
                string $propertyName,
                string $propertyType,
                ContainerInterface $container
            ) {
                return $this->resolveFromContainer($container, $propertyType, $propertyName);
            });

            return $run(...$arguments);
        }

        return parent::run($context);
    }

    protected function isAllowed(
        string $externalId,
        ?DatasetEntityContract $entity,
        ExploreContextInterface $context
    ): bool {
        if ($this->isAllowedMethod instanceof SerializableClosure &&
            \is_callable($isAllowed = $this->isAllowedMethod->getClosure())) {
            $arguments = $this->resolveArguments($isAllowed, $context, function (
                int $propertyIndex,
                string $propertyName,
                string $propertyType,
                ContainerInterface $container
            ) use ($externalId, $entity) {
                if ($propertyType === 'string') {
                    return $externalId;
                } elseif (\is_a($propertyType, $this->supports(), true)) {
                    return $entity;
                }

                return $this->resolveFromContainer($container, $propertyType, $propertyName);
            });

            return $isAllowed(...$arguments);
        }

        return parent::isAllowed($externalId, $entity, $context);
    }
}
