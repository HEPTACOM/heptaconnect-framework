<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission;

use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\MappingCollection;

class EmitterStack implements EmitterStackInterface
{
    /**
     * @var array<array-key, EmitterInterface>
     */
    private array $emitters;

    /**
     * @param iterable<array-key, EmitterInterface> $emitters
     */
    public function __construct(iterable $emitters)
    {
        $this->emitters = iterable_to_array($emitters);
    }

    public function next(MappingCollection $mappings, EmitContextInterface $context): iterable
    {
        $emitter = \array_shift($this->emitters);

        if (!$emitter instanceof EmitterInterface) {
            return [];
        }

        return $emitter->emit($mappings, $context, $this);
    }
}
