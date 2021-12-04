<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Emission;

use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\SecondEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection
 */
class EmitterCollectionTest extends TestCase
{
    public function testBySupport(): void
    {
        $collection = new EmitterCollection();

        $collection->push([
            $this->getEmitter(FirstEntity::class),
            $this->getEmitter(SecondEntity::class),
            $this->getEmitter(FirstEntity::class),
            $this->getEmitter(SecondEntity::class),
            $this->getEmitter(FirstEntity::class),
        ]);
        static::assertNotEmpty($collection->bySupport(FirstEntity::class));
        static::assertNotEmpty($collection->bySupport(SecondEntity::class));
        static::assertCount(3, $collection->bySupport(FirstEntity::class));
        static::assertCount(2, $collection->bySupport(SecondEntity::class));
    }

    private function getEmitter(string $support): EmitterContract
    {
        $emitter = $this->createMock(EmitterContract::class);
        $emitter->expects(static::any())->method('supports')->willReturn($support);

        return $emitter;
    }
}
