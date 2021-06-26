<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Psr\Container\ContainerInterface;

class ExplorerToken
{
    private string $type;

    /** @var callable|null */
    private $run = null;

    /** @var callable|null */
    private $isAllowed = null;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRun(): ?callable
    {
        return $this->run;
    }

    public function setRun(?callable $run): void
    {
        $this->run = $run;
    }

    public function getIsAllowed(): ?callable
    {
        return $this->isAllowed;
    }

    public function setIsAllowed(?callable $isAllowed): void
    {
        $this->isAllowed = $isAllowed;
    }

    public function build(): ExplorerContract
    {
        return new class($this->type, $this->run, $this->isAllowed) extends ExplorerContract {
            use ResolveArgumentsTrait;

            private string $type;

            /** @var callable|null */
            private $runMethod;

            /** @var callable|null */
            private $isAllowedMethod;

            public function __construct(string $type, ?callable $run, ?callable $isAllowed)
            {
                $this->type = $type;
                $this->runMethod = $run;
                $this->isAllowedMethod = $isAllowed;
            }

            public function supports(): string
            {
                return $this->type;
            }

            protected function run(ExploreContextInterface $context): iterable
            {
                if (\is_callable($run = $this->runMethod)) {
                    $arguments = $this->resolveArguments($run, $context->getContainer(), function (
                        int $propertyIndex,
                        string $propertyName,
                        string $propertyType,
                        ContainerInterface $container
                    ) {
                        return $container->get($propertyType);
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
                    $arguments = $this->resolveArguments($isAllowed, $context->getContainer(), function (
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

                        return $container->get($propertyType);
                    });

                    return $isAllowed(...$arguments);
                }

                return parent::isAllowed($externalId, $entity, $context);
            }
        };
    }
}
