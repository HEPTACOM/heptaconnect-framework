<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

use Closure;
use Heptacom\HeptaConnect\Portal\Base\Builder\Builder\EmitterBuilder;
use Heptacom\HeptaConnect\Portal\Base\Builder\Builder\ExplorerBuilder;
use Heptacom\HeptaConnect\Portal\Base\Builder\Builder\ReceiverBuilder;
use Heptacom\HeptaConnect\Portal\Base\Builder\Builder\StatusReporterBuilder;
use Heptacom\HeptaConnect\Portal\Base\Builder\Component\Emitter;
use Heptacom\HeptaConnect\Portal\Base\Builder\Component\Explorer;
use Heptacom\HeptaConnect\Portal\Base\Builder\Component\Receiver;
use Heptacom\HeptaConnect\Portal\Base\Builder\Component\StatusReporter;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\StatusReporterToken;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class FlowComponent implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var ExplorerToken[] */
    private static array $explorerTokens = [];

    /** @var EmitterToken[] */
    private static array $emitterTokens = [];

    /** @var ReceiverToken[] */
    private static array $receiverTokens = [];

    /** @var StatusReporterToken[] */
    private static array $statusReporterTokens = [];

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $type
     */
    public static function explorer(string $type, ?Closure $run = null, ?Closure $isAllowed = null): ExplorerBuilder
    {
        self::$explorerTokens[] = $token = new ExplorerToken($type);
        $builder = new ExplorerBuilder($token);

        if ($run instanceof Closure) {
            $builder->run($run);
        }

        if ($isAllowed instanceof Closure) {
            $builder->isAllowed($isAllowed);
        }

        return $builder;
    }

    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $type
     */
    public static function emitter(
        string $type,
        ?Closure $run = null,
        ?Closure $extend = null,
        ?Closure $batch = null
    ): EmitterBuilder {
        self::$emitterTokens[] = $token = new EmitterToken($type);
        $builder = new EmitterBuilder($token);

        if ($run instanceof Closure) {
            $builder->run($run);
        }

        if ($extend instanceof Closure) {
            $builder->extend($extend);
        }

        if ($batch instanceof Closure) {
            $builder->batch($batch);
        }

        return $builder;
    }

    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $type
     */
    public static function receiver(string $type, ?Closure $run = null, ?Closure $batch = null): ReceiverBuilder
    {
        self::$receiverTokens[] = $token = new ReceiverToken($type);
        $builder = new ReceiverBuilder($token);

        if ($run instanceof Closure) {
            $builder->run($run);
        }

        if ($batch instanceof Closure) {
            $builder->batch($batch);
        }

        return $builder;
    }

    public static function statusReporter(string $topic, ?Closure $run = null): StatusReporterBuilder
    {
        self::$statusReporterTokens[] = $token = new StatusReporterToken($topic);
        $builder = new StatusReporterBuilder($token);

        if ($run instanceof Closure) {
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
            yield new Explorer($explorerToken);
            unset(self::$explorerTokens[$key]);
        }
    }

    /**
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract>
     */
    public function buildEmitters(): iterable
    {
        foreach (self::$emitterTokens as $key => $emitterToken) {
            if ($emitterToken->getRun() instanceof Closure && $emitterToken->getBatch() instanceof Closure) {
                $this->logger->warning(<<<'TXT'
EmitterBuilder: You implement both "run" and "batch". The "run" method will not be executed.
TXT
                );
            }

            yield new Emitter($emitterToken);
            unset(self::$emitterTokens[$key]);
        }
    }

    /**
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract>
     */
    public function buildReceivers(): iterable
    {
        foreach (self::$receiverTokens as $key => $receiverToken) {
            if ($receiverToken->getRun() instanceof Closure && $receiverToken->getBatch() instanceof Closure) {
                $this->logger->warning(<<<'TXT'
ReceiverBuilder: You implement both "run" and "batch". The "run" method will not be executed.
TXT
                );
            }

            yield new Receiver($receiverToken);
            unset(self::$receiverTokens[$key]);
        }
    }

    /**
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract>
     */
    public function buildStatusReporters(): iterable
    {
        foreach (self::$statusReporterTokens as $key => $statusReporterToken) {
            yield new StatusReporter($statusReporterToken);
            unset(self::$statusReporterTokens[$key]);
        }
    }

    public function reset(): void
    {
        self::$explorerTokens = [];
        self::$emitterTokens = [];
        self::$receiverTokens = [];
        self::$statusReporterTokens = [];
    }
}
