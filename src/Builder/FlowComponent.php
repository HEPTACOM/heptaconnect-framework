<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

class FlowComponent
{
    /** @var ExplorerToken[] */
    private static array $explorerTokens = [];

    /** @var EmitterToken[] */
    private static array $emitterTokens = [];

    /** @var ReceiverToken[] */
    private static array $receiverTokens = [];

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

    public static function emitter(string $type, ?callable $run = null, ?callable $extend = null): EmitterBuilder
    {
        self::$emitterTokens[] = $token = new EmitterToken($type);
        $builder = new EmitterBuilder($token);

        if (\is_callable($run)) {
            $builder->run($run);
        }

        if (\is_callable($extend)) {
            $builder->extend($extend);
        }

        return $builder;
    }

    public static function receiver(string $type, ?callable $run = null): ReceiverBuilder
    {
        self::$receiverTokens[] = $token = new ReceiverToken($type);
        $builder = new ReceiverBuilder($token);

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
            yield $receiverToken->build();
            unset(self::$receiverTokens[$key]);
        }
    }
}
