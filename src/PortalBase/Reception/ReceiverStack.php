<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception;

use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class ReceiverStack implements ReceiverStackInterface, LoggerAwareInterface
{
    /**
     * @var array<array-key, ReceiverContract>
     */
    private array $receivers;

    private LoggerInterface $logger;

    /**
     * @param iterable<array-key, ReceiverContract> $receivers
     */
    public function __construct(iterable $receivers)
    {
        $this->receivers = \iterable_to_array($receivers);
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function next(TypedDatasetEntityCollection $entities, ReceiveContextInterface $context): iterable
    {
        $receiver = \array_shift($this->receivers);

        if (!$receiver instanceof ReceiverContract) {
            return [];
        }

        $this->logger->debug('Execute FlowComponent receiver', [
            'receiver' => $receiver,
        ]);

        return $receiver->receive($entities, $context, $this);
    }
}
