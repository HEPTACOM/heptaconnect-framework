<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission;

use Heptacom\HeptaConnect\Portal\Base\Builder\Component\Emitter as ShorthandEmitter;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class EmitterStack implements EmitterStackInterface, LoggerAwareInterface
{
    /**
     * @var array<array-key, \Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract>
     */
    private array $emitters;

    /**
     * @var class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    private string $entityType;

    private LoggerInterface $logger;

    /**
     * @param iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract> $emitters
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>          $entityType
     */
    public function __construct(iterable $emitters, string $entityType)
    {
        /** @var array<array-key, \Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract> $arrayEmitters */
        $arrayEmitters = \iterable_to_array($emitters);
        $this->emitters = $arrayEmitters;
        $this->entityType = $entityType;
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function next(iterable $externalIds, EmitContextInterface $context): iterable
    {
        $emitter = \array_shift($this->emitters);

        if (!$emitter instanceof EmitterContract) {
            return [];
        }

        $this->logger->debug(\sprintf('Execute FlowComponent emitter: %s', \get_class($emitter)));

        return $emitter->emit($externalIds, $context, $this);
    }

    public function supports(): string
    {
        return $this->entityType;
    }

    public function listOrigins(): array
    {
        $origins = [];
        foreach ($this->emitters as $emitter) {
            $origins[] = $this->getOrigin($emitter);
        }

        return $origins;
    }

    protected function getOrigin(EmitterContract $emitter): string
    {
        if ($emitter instanceof ShorthandEmitter) {
            $runMethod = $emitter->getRunMethod();

            if ($runMethod instanceof \Closure) {
                $reflection = new \ReflectionFunction($runMethod);

                return $reflection->getFileName() . '::run:' . $reflection->getStartLine();
            }

            $batchMethod = $emitter->getBatchMethod();

            if ($batchMethod instanceof \Closure) {
                $reflection = new \ReflectionFunction($batchMethod);

                return $reflection->getFileName() . '::batch:' . $reflection->getStartLine();
            }

            $extendMethod = $emitter->getExtendMethod();

            if ($extendMethod instanceof \Closure) {
                $reflection = new \ReflectionFunction($extendMethod);

                return $reflection->getFileName() . '::extend:' . $reflection->getStartLine();
            }

            $this->logger->warning('EmitterStack contains unconfigured short-notation explorer', [
                'code' => 1637607653,
            ]);
        }

        $reflection = new \ReflectionClass($emitter);

        return $reflection->getFileName() . ':' . $reflection->getStartLine();
    }
}
