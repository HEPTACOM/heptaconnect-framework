<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Job\Transition;

use Heptacom\HeptaConnect\Core\Job\JobCollection;
use Heptacom\HeptaConnect\Core\Job\Transition\ExploredPrimaryKeysToEmissionJobsConverter;
use Heptacom\HeptaConnect\Core\Job\Type\AbstractJobType;
use Heptacom\HeptaConnect\Core\Job\Type\Emission;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalType;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\StringCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(JobCollection::class)]
#[CoversClass(ExploredPrimaryKeysToEmissionJobsConverter::class)]
#[CoversClass(AbstractJobType::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(MappingComponentStruct::class)]
#[CoversClass(PortalContract::class)]
#[CoversClass(PortalType::class)]
#[CoversClass(PreviewPortalNodeKey::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(StringCollection::class)]
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
