<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Builder\ExplorerToken;
use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Psr\Container\ContainerInterface;

class Explorer extends ExplorerContract
{
    use ResolveArgumentsTrait;

    private string $type;

    /** @var callable|null */
    private $runMethod;

    /** @var callable|null */
    private $isAllowedMethod;

    public function __construct(ExplorerToken $token)
    {
        $this->type = $token->getType();
        $this->runMethod = $token->getRun();
        $this->isAllowedMethod = $token->getIsAllowed();
    }

    public function supports(): string
    {
        return $this->type;
    }

    protected function run(ExploreContextInterface $context): iterable
    {
        if (\is_callable($run = $this->runMethod)) {
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
        if (\is_callable($isAllowed = $this->isAllowedMethod)) {
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
