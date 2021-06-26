<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Psr\Container\ContainerInterface;

class ReceiverToken
{
    private string $type;

    /** @var callable|null */
    private $run = null;

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

    public function build(): ReceiverContract
    {
        return new class ($this->type, $this->run) extends ReceiverContract {
            use ResolveArgumentsTrait;

            private string $type;

            /** @var callable|null */
            private $runMethod;

            public function __construct(string $type, ?callable $run)
            {
                $this->type = $type;
                $this->runMethod = $run;
            }

            public function supports(): string
            {
                return $this->type;
            }

            protected function run(
                DatasetEntityContract $entity,
                ReceiveContextInterface $context
            ): void {
                if (\is_callable($run = $this->runMethod)) {
                    $arguments = $this->resolveArguments($run, $context->getContainer(), function (
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

                    $run(...$arguments);

                    return;
                }

                parent::run($entity, $context);
            }
        };
    }
}
