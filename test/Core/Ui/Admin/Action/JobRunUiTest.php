<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Job\Contract\DelegatingJobActorContract;
use Heptacom\HeptaConnect\Core\Job\Type\Emission;
use Heptacom\HeptaConnect\Core\Job\Type\Exploration;
use Heptacom\HeptaConnect\Core\Job\Type\Reception;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\JobRunUi;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\JobKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Job\JobRun\JobRunPayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\JobMissingException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\JobProcessingException;
use PHPUnit\Framework\Constraint\Count;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Job\JobData
 * @covers \Heptacom\HeptaConnect\Core\Job\JobDataCollection
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\JobRunUi
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\JobKeyCollection
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Job\JobRun\JobRunPayload
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\JobMissingException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\JobProcessingException
 */
final class JobRunUiTest extends TestCase
{
    public function testJobsOfAllTypesAreRun(): void
    {
        $jobActor = $this->createMock(DelegatingJobActorContract::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $jobA = $this->createMock(JobKeyInterface::class);
        $jobB = $this->createMock(JobKeyInterface::class);
        $jobC = $this->createMock(JobKeyInterface::class);
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);

        $jobA->method('equals')->willReturnCallback(static fn ($key): bool => $key === $jobA);
        $jobB->method('equals')->willReturnCallback(static fn ($key): bool => $key === $jobB);
        $jobC->method('equals')->willReturnCallback(static fn ($key): bool => $key === $jobC);

        $jobGetAction->method('get')->willReturnCallback(static function () use ($jobC, $jobB, $jobA, $portalNodeKey) {
            $mappingComponent = new MappingComponentStruct($portalNodeKey, FooBarEntity::class(), 'ABC');

            return [
                new JobGetResult(Emission::class, $jobA, $mappingComponent, null),
                new JobGetResult(Reception::class, $jobB, $mappingComponent, null),
                new JobGetResult(Exploration::class, $jobC, $mappingComponent, null),
            ];
        });

        $jobActor->expects(static::exactly(3))
            ->method('performJobs')
            ->withConsecutive([
                Emission::class, new Count(1),
            ], [
                Reception::class, new Count(1),
            ], [
                Exploration::class, new Count(1),
            ]);

        $action = new JobRunUi($jobActor, $jobGetAction);
        $payload = new JobRunPayload();
        $payload->getJobKeys()->push([$jobA, $jobB, $jobC]);

        $action->run($payload);
    }

    public function testMissingJobsAreReportedBeforeOthersAreRun(): void
    {
        $jobActor = $this->createMock(DelegatingJobActorContract::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $jobA = $this->createMock(JobKeyInterface::class);
        $jobB = $this->createMock(JobKeyInterface::class);
        $jobC = $this->createMock(JobKeyInterface::class);
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);

        $jobA->method('equals')->willReturnCallback(static fn ($key): bool => $key === $jobA);
        $jobB->method('equals')->willReturnCallback(static fn ($key): bool => $key === $jobB);
        $jobC->method('equals')->willReturnCallback(static fn ($key): bool => $key === $jobC);

        $jobGetAction->method('get')->willReturnCallback(static function () use ($jobC, $jobA, $portalNodeKey) {
            $mappingComponent = new MappingComponentStruct($portalNodeKey, FooBarEntity::class(), 'ABC');

            return [
                new JobGetResult(Emission::class, $jobA, $mappingComponent, null),
                new JobGetResult(Exploration::class, $jobC, $mappingComponent, null),
            ];
        });

        $jobActor->expects(static::never())->method('performJobs');

        $action = new JobRunUi($jobActor, $jobGetAction);
        $payload = new JobRunPayload();
        $payload->getJobKeys()->push([$jobA, $jobB, $jobC]);

        static::expectExceptionCode(1659721163);
        static::expectException(JobMissingException::class);

        $action->run($payload);
    }

    public function testFailingJobExceptionCanReportProgress(): void
    {
        $jobActor = $this->createMock(DelegatingJobActorContract::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $jobA = $this->createMock(JobKeyInterface::class);
        $jobB = $this->createMock(JobKeyInterface::class);
        $jobC = $this->createMock(JobKeyInterface::class);
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);

        $jobA->method('equals')->willReturnCallback(static fn ($key): bool => $key === $jobA);
        $jobB->method('equals')->willReturnCallback(static fn ($key): bool => $key === $jobB);
        $jobC->method('equals')->willReturnCallback(static fn ($key): bool => $key === $jobC);

        $jobGetAction->method('get')->willReturnCallback(static function () use ($jobC, $jobB, $jobA, $portalNodeKey) {
            $mappingComponent = new MappingComponentStruct($portalNodeKey, FooBarEntity::class(), 'ABC');

            return [
                new JobGetResult(Emission::class, $jobA, $mappingComponent, null),
                new JobGetResult(Reception::class, $jobB, $mappingComponent, null),
                new JobGetResult(Exploration::class, $jobC, $mappingComponent, null),
            ];
        });

        $actingCount = 0;
        $jobActor->method('performJobs')
            ->willReturnCallback(static function () use (&$actingCount): void {
                ++$actingCount;

                if ($actingCount > 1) {
                    throw new \RuntimeException('Yikes');
                }
            });

        $action = new JobRunUi($jobActor, $jobGetAction);
        $payload = new JobRunPayload();
        $payload->getJobKeys()->push([$jobA, $jobB, $jobC]);

        try {
            $action->run($payload);
            static::fail();
        } catch (JobProcessingException $exception) {
            static::assertSame(1659721164, $exception->getCode());
            static::assertCount(1, $exception->getFailedJobs());
            static::assertCount(1, $exception->getProcessedJobs());
            static::assertCount(1, $exception->getNotProcessedJobs());
        }
    }
}
