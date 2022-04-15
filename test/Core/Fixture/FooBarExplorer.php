<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;

final class FooBarExplorer extends ExplorerContract
{
    private int $count;

    public function __construct(int $count)
    {
        $this->count = $count;
    }

    public function explore(ExploreContextInterface $context, ExplorerStackInterface $stack): iterable
    {
        for ($c = 0; $c < $this->count; ++$c) {
            yield (string) $c;
        }

        yield from $stack->next($externalIds, $context);
    }

    public function supports(): string
    {
        return FooBarEntity::class;
    }
}
