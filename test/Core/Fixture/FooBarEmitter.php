<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;

final class FooBarEmitter extends EmitterContract
{
    public function __construct(private int $count)
    {
    }

    public function emit(iterable $externalIds, EmitContextInterface $context, EmitterStackInterface $stack): iterable
    {
        for ($c = 0; $c < $this->count; ++$c) {
            yield new FooBarEntity();
        }

        yield from $stack->next($externalIds, $context);
    }

    public function supports(): string
    {
        return FooBarEntity::class;
    }
}
