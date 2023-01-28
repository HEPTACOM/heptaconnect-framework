<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Component;

use Heptacom\HeptaConnect\Portal\Base\Builder\BindThisTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Exception\InvalidResultException;
use Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\StatusReporterToken;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReportingContextInterface;
use Psr\Container\ContainerInterface;

final class StatusReporter extends StatusReporterContract
{
    use BindThisTrait;
    use ResolveArgumentsTrait;

    private string $topic;

    private ?\Closure $runMethod;

    public function __construct(StatusReporterToken $token)
    {
        $this->topic = $token->getTopic();
        $this->runMethod = $token->getRun();
    }

    public function getRunMethod(): ?\Closure
    {
        return $this->runMethod;
    }

    public function supportsTopic(): string
    {
        return $this->topic;
    }

    protected function run(StatusReportingContextInterface $context): array
    {
        $run = $this->runMethod;

        if ($run instanceof \Closure) {
            $run = $this->bindThis($run);
            $arguments = $this->resolveArguments($run, $context, fn (
                int $_propertyIndex,
                string $propertyName,
                ?string $propertyType,
                ContainerInterface $container
            ) => $this->resolveFromContainer($container, $propertyType, $propertyName));

            try {
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
