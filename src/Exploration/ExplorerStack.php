<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration;

use Heptacom\HeptaConnect\Portal\Base\Builder\Component\Explorer as ShorthandExplorer;
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

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function next(ExploreContextInterface $context): iterable
    {
        $explorer = \array_shift($this->explorers);

        if (!$explorer instanceof ExplorerContract) {
            return [];
        }

        $this->logger->debug(\sprintf('Execute FlowComponent explorer: %s', $this->getOrigin($explorer)));

        return $explorer->explore($context, $this);
    }

    public function listOrigins(): array
    {
        $origins = [];
        foreach ($this->explorers as $explorer) {
            $origins[] = $this->getOrigin($explorer);
        }

        return $origins;
    }

    protected function getOrigin(ExplorerContract $explorer): string
    {
        if ($explorer instanceof ShorthandExplorer) {
            $runMethod = $explorer->getRunMethod();

            if ($runMethod instanceof \Closure) {
                $reflection = new \ReflectionFunction($runMethod);

                return $reflection->getFileName() . '::run:' . $reflection->getStartLine();
            }

            $isAllowedMethod = $explorer->getIsAllowedMethod();

            if ($isAllowedMethod instanceof \Closure) {
                $reflection = new \ReflectionFunction($isAllowedMethod);

                return $reflection->getFileName() . '::isAllowed:' . $reflection->getStartLine();
            }

            $this->logger->warning('ExplorerStack contains unconfigured short-notation explorer', [
                'code' => 1637421327,
            ]);
        }

        $reflection = new \ReflectionClass($explorer);

        return $reflection->getFileName() . ':' . $reflection->getStartLine();
    }
}
