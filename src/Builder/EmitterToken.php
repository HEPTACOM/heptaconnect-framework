<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Psr\Container\ContainerInterface;

class EmitterToken
{
    private string $type;

    /** @var callable|null */
    private $run = null;

    /** @var callable|null */
    private $extend = null;

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

    public function setRun(callable $run): void
    {
        $this->run = $run;
    }

    public function getExtend(): ?callable
    {
        return $this->extend;
    }

    public function setExtend(callable $extend): void
    {
        $this->extend = $extend;
    }

    public function build(): EmitterContract
    {
        return new class ($this->type, $this->run, $this->extend) extends EmitterContract {
            use ResolveArgumentsTrait;

            private string $type;

            /** @var callable|null */
            private $runMethod;

            /** @var callable|null */
            private $extendMethod;

            public function __construct(string $type, ?callable $run, ?callable $extend)
            {
                $this->type = $type;
                $this->runMethod = $run;
                $this->extendMethod = $extend;
            }

            public function supports(): string
            {
                return $this->type;
            }

            protected function run(
                string $externalId,
                EmitContextInterface $context
            ): ?DatasetEntityContract {
                if (\is_callable($run = $this->runMethod)) {
                    $arguments = $this->resolveArguments($run, $context->getContainer(), function (
                        int $propertyIndex,
                        string $propertyName,
                        string $propertyType,
                        ContainerInterface $container
                    ) use ($externalId) {
                        if ($propertyType === 'string') {
                            return $externalId;
                        }

                        return $container->get($propertyType);
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
                    $arguments = $this->resolveArguments($extend, $context->getContainer(), function (
                        int $propertyIndex,
                        string $propertyName,
                        string $propertyType,
                        ContainerInterface $container
                    ) use ($entity) {
                        if (\is_a($propertyType, $this->supports(), true)) {
                            return $entity;
                        }

                        return $container->get($propertyType);
                    });

                    return $extend(...$arguments);
                }

                return parent::extend($entity, $context);
            }
        };
    }
}
