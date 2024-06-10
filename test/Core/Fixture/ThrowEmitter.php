<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;

final class ThrowEmitter extends EmitterContract
{
    #[\Override]
    public function emit(iterable $externalIds, EmitContextInterface $context, EmitterStackInterface $stack): iterable
    {
        throw new \RuntimeException();
    }

    #[\Override]
    public function supports(): string
    {
        return FooBarEntity::class;
    }
}
