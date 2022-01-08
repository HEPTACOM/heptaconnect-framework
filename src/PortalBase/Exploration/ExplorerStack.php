<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration;

use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ExplorerStack implements ExplorerStackInterface, LoggerAwareInterface
{
    /**
     * @var array<array-key, ExplorerContract>
     */
    private array $explorers;

    private LoggerInterface $logger;

    /**
     * @param iterable<array-key, ExplorerContract> $explorers
     */
    public function __construct(iterable $explorers)
    {
        $this->explorers = \iterable_to_array($explorers);
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function next(ExploreContextInterface $context): iterable
    {
        $explorer = \array_shift($this->explorers);

        if (!$explorer instanceof ExplorerContract) {
            return [];
        }

        $this->logger->debug(\sprintf('Execute FlowComponent explorer: %s', \get_class($explorer)));

        return $explorer->explore($context, $this);
    }
}
