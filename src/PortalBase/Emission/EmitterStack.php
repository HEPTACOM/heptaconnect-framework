<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission;

use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class EmitterStack implements EmitterStackInterface, LoggerAwareInterface
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

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function next(iterable $externalIds, EmitContextInterface $context): iterable
    {
        $emitter = \array_shift($this->emitters);

        if (!$emitter instanceof EmitterContract) {
            return [];
        }

        $this->logger->debug('Execute FlowComponent emitter', [
            'emitter' => $emitter,
        ]);

        return $emitter->emit($externalIds, $context, $this);
    }

    public function supports(): string
    {
        return $this->entityType;
    }
}
