<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Job\Transition;

use Heptacom\HeptaConnect\Core\Job\Transition\ExploredPrimaryKeysToEmissionJobsConverter;
use Heptacom\HeptaConnect\Core\Job\Type\Emission;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Job\JobCollection
 * @covers \Heptacom\HeptaConnect\Core\Job\Transition\ExploredPrimaryKeysToEmissionJobsConverter
 * @covers \Heptacom\HeptaConnect\Core\Job\Type\AbstractJobType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 */
final class ExploredPrimaryKeysToEmissionJobsConverterTest extends TestCase
{
    public function testMultiplyEmissionStackResultByRoutes(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());

        $logger->expects(static::never())->method('warning');

        $converter = new ExploredPrimaryKeysToEmissionJobsConverter($logger);

        $result = $converter->convert($portalNodeKey, FooBarEntity::class(), new StringCollection(['entityB', 'entityA']));

        $scenarios = [];

        foreach ($result as $job) {
            static::assertInstanceOf(Emission::class, $job);
            $scenario = '';

            static::assertTrue($portalNodeKey->equals($job->getMappingComponent()->getPortalNodeKey()));

            $scenario .= $job->getMappingComponent()->getEntityType();
            $scenario .= $job->getMappingComponent()->getExternalId();

            $scenarios[] = $scenario;
        }

        \sort($scenarios);

        static::assertSame([
            FooBarEntity::class . 'entityA',
            FooBarEntity::class . 'entityB',
        ], $scenarios);
    }

    public function testEmptyResultOnEmptyEmissionStackResult(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());

        $logger->expects(static::once())->method('warning');

        $converter = new ExploredPrimaryKeysToEmissionJobsConverter($logger);

        $result = $converter->convert($portalNodeKey, FooBarEntity::class(), new StringCollection());

        static::assertCount(0, $result);
    }
}
