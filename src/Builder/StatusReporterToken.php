<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReportingContextInterface;
use Psr\Container\ContainerInterface;

class StatusReporterToken
{
    private string $topic;

    /** @var callable|null */
    private $run = null;

    public function __construct(string $topic)
    {
        $this->topic = $topic;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function getRun(): ?callable
    {
        return $this->run;
    }

    public function setRun(callable $run): void
    {
        $this->run = $run;
    }

    public function build(): StatusReporterContract
    {
        return new class($this->topic, $this->run) extends StatusReporterContract {
            use ResolveArgumentsTrait;

            private string $type;

            /** @var callable|null */
            private $runMethod;

            public function __construct(string $type, ?callable $run)
            {
                $this->type = $type;
                $this->runMethod = $run;
            }

            public function supportsTopic(): string
            {
                return $this->type;
            }

            protected function run(StatusReportingContextInterface $context): array
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
        };
    }
}
