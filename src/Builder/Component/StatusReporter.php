<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\StatusReporterToken;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReportingContextInterface;
use Psr\Container\ContainerInterface;

class StatusReporter extends StatusReporterContract
{
    use ResolveArgumentsTrait;

    private string $topic;

    /** @var callable|null */
    private $runMethod;

    public function __construct(StatusReporterToken $token)
    {
        $this->topic = $token->getTopic();
        $this->runMethod = $token->getRun();
    }

    public function supportsTopic(): string
    {
        return $this->topic;
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

            try {
                $result = $run(...$arguments);

                if (\is_bool($result)) {
                    return [$this->supportsTopic() => $result];
                }

                return $result;
            } catch (\Throwable $throwable) {
                return [
                    $this->supportsTopic() => false,
                    'exception' => $throwable->getMessage(),
                ];
            }
        }

        return parent::run($context);
    }
}
