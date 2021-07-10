<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class FlowComponent implements LoggerAwareInterface
{
    /** @var ExplorerToken[] */
    private static array $explorerTokens = [];

    /** @var EmitterToken[] */
    private static array $emitterTokens = [];

    /** @var ReceiverToken[] */
    private static array $receiverTokens = [];

    /** @var StatusReporterToken[] */
    private static array $statusReporterTokens = [];

    private LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function explorer(string $type, ?callable $run = null, ?callable $isAllowed = null): ExplorerBuilder
    {
        self::$explorerTokens[] = $token = new ExplorerToken($type);
        $builder = new ExplorerBuilder($token);

        if (\is_callable($run)) {
            $builder->run($run);
        }

        if (\is_callable($isAllowed)) {
            $builder->isAllowed($isAllowed);
        }

        return $builder;
    }

    public static function emitter(
        string $type,
        ?callable $run = null,
        ?callable $extend = null,
        ?callable $batch = null
    ): EmitterBuilder {
        self::$emitterTokens[] = $token = new EmitterToken($type);
        $builder = new EmitterBuilder($token);

        if (\is_callable($run)) {
            $builder->run($run);
        }

        if (\is_callable($extend)) {
            $builder->extend($extend);
        }

        if (\is_callable($batch)) {
            $builder->batch($batch);
        }

        return $builder;
    }

    public static function receiver(string $type, ?callable $run = null, ?callable $batch = null): ReceiverBuilder
    {
        self::$receiverTokens[] = $token = new ReceiverToken($type);
        $builder = new ReceiverBuilder($token);

        if (\is_callable($run)) {
            $builder->run($run);
        }

        if (\is_callable($batch)) {
            $builder->batch($batch);
        }

        return $builder;
    }

    public static function statusReporter(string $topic, ?callable $run = null): StatusReporterBuilder
    {
        self::$statusReporterTokens[] = $token = new StatusReporterToken($topic);
        $builder = new StatusReporterBuilder($token);

        if (\is_callable($run)) {
            $builder->run($run);
        }

        return $builder;
    }

    /**
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract>
     */
    public function buildExplorers(): iterable
    {
        foreach (self::$explorerTokens as $key => $explorerToken) {
            yield $explorerToken->build();
            unset(self::$explorerTokens[$key]);
        }
    }

    /**
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract>
     */
    public function buildEmitters(): iterable
    {
        foreach (self::$emitterTokens as $key => $emitterToken) {
            if (\is_callable($emitterToken->getRun()) && \is_callable($emitterToken->getBatch())) {
                $this->logger->warning(<<<'TXT'
EmitterBuilder: You implement both "run" and "batch". The "run" method will not be executed.
TXT
                );
            }

            yield $emitterToken->build();
            unset(self::$emitterTokens[$key]);
        }
    }

    /**
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract>
     */
    public function buildReceivers(): iterable
    {
        foreach (self::$receiverTokens as $key => $receiverToken) {
            if (\is_callable($receiverToken->getRun()) && \is_callable($receiverToken->getBatch())) {
                $this->logger->warning(<<<'TXT'
ReceiverBuilder: You implement both "run" and "batch". The "run" method will not be executed.
TXT
                );
            }

            yield $receiverToken->build();
            unset(self::$receiverTokens[$key]);
        }
    }

    /**
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract>
     */
    public function buildStatusReporters(): iterable
    {
        foreach (self::$statusReporterTokens as $key => $statusReporterToken) {
            yield $statusReporterToken->build();
            unset(self::$statusReporterTokens[$key]);
        }
    }

    public function reset(): void
    {
        self::$explorerTokens = [];
        self::$emitterTokens = [];
        self::$receiverTokens = [];
    }
}
