<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Heptacom\HeptaConnect\Portal\Base\Builder\Exception\InvalidResultException;
use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\StatusReporterToken;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReportingContextInterface;
use Opis\Closure\SerializableClosure;
use Psr\Container\ContainerInterface;

final class StatusReporter extends StatusReporterContract
{
    use ResolveArgumentsTrait;

    private string $topic;

    private ?SerializableClosure $runMethod;

    public function __construct(StatusReporterToken $token)
    {
        $run = $token->getRun();

        $this->topic = $token->getTopic();
        $this->runMethod = $run instanceof \Closure ? new SerializableClosure($run) : null;
    }

    public function getRunMethod(): ?\Closure
    {
        return $this->runMethod instanceof SerializableClosure ? $this->runMethod->getClosure() : null;
    }

    public function supportsTopic(): string
    {
        return $this->topic;
    }

    protected function run(StatusReportingContextInterface $context): array
    {
        if ($this->runMethod instanceof SerializableClosure) {
            $run = $this->runMethod->getClosure();
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

                if (\is_array($result)) {
                    return $result;
                }

                throw new InvalidResultException(1637036888, 'StatusReporter', 'run', 'bool|array');
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
