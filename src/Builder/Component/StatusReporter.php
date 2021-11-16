<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Closure;
use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\StatusReporterToken;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReportingContextInterface;
use Opis\Closure\SerializableClosure;
use Psr\Container\ContainerInterface;

class StatusReporter extends StatusReporterContract
{
    use ResolveArgumentsTrait;

    private string $topic;

    private ?SerializableClosure $runMethod;

    public function __construct(StatusReporterToken $token)
    {
        $run = $token->getRun();

        $this->topic = $token->getTopic();
        $this->runMethod = $run instanceof Closure ? new SerializableClosure($run) : null;
    }

    public function supportsTopic(): string
    {
        return $this->topic;
    }

    protected function run(StatusReportingContextInterface $context): array
    {
        if ($this->runMethod instanceof SerializableClosure &&
            \is_callable($run = $this->runMethod->getClosure())) {
            $arguments = $this->resolveArguments($run, $context, function (
                int $_propertyIndex,
                string $propertyName,
                ?string $propertyType,
                ContainerInterface $container
            ) {
                return $this->resolveFromContainer($container, $propertyType, $propertyName);
            });

            try {
                /** @var mixed $result */
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
