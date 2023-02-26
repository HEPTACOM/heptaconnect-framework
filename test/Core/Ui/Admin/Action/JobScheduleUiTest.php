<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Flow\MessageQueueFlow\Message\JobMessage;
use Heptacom\HeptaConnect\Core\Job\Type\Emission;
use Heptacom\HeptaConnect\Core\Job\Type\Exploration;
use Heptacom\HeptaConnect\Core\Job\Type\Reception;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\JobScheduleUi;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Schedule\JobSchedulePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Schedule\JobScheduleResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Job\JobScheduleActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\JobKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Job\JobSchedule\JobScheduleCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\JobsMissingException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Flow\MessageQueueFlow\Message\JobMessage
 * @covers \Heptacom\HeptaConnect\Core\Job\JobData
 * @covers \Heptacom\HeptaConnect\Core\Job\JobDataCollection
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContextFactory
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\JobScheduleUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Contract\JobStateChangePayloadContract
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Job\Schedule\JobScheduleResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\JobKeyCollection
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Job\JobSchedule\JobScheduleCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Job\JobSchedule\JobScheduleResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\JobsMissingException
 */
final class JobScheduleUiTest extends TestCase
{
    use UiActionTestTrait;

    public function testJobsOfAllTypesAreScheduled(): void
    {
        $bus = $this->createMock(MessageBusInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $jobScheduleAction = $this->createMock(JobScheduleActionInterface::class);
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
        $jobScheduleAction
            ->expects(static::once())
            ->method('schedule')
            ->willReturnCallback(static function (JobSchedulePayload $payload): JobScheduleResult {
                static::assertCount(3, $payload->getJobKeys());
                static::assertNotEmpty($payload->getMessage());

                return new JobScheduleResult($payload->getJobKeys(), new JobKeyCollection());
            });

        $bus->expects(static::once())
            ->method('dispatch')
            ->willReturnCallback(static function (JobMessage $message): Envelope {
                static::assertCount(3, $message->getJobKeys()->asArray());

                return new Envelope($message);
            });

        $action = new JobScheduleUi($this->createAuditTrailFactory(), $bus, $jobGetAction, $jobScheduleAction);
        $criteria = new JobScheduleCriteria();
        $criteria->getJobKeys()->push([$jobA, $jobB, $jobC]);

        $action->schedule($criteria, $this->createUiActionContext());
    }

    public function testMissingJobsAreReportedBeforeOthersAreScheduled(): void
    {
        $bus = $this->createMock(MessageBusInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $jobScheduleAction = $this->createMock(JobScheduleActionInterface::class);
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

        $jobScheduleAction->expects(static::never())->method('schedule');
        $bus->expects(static::never())->method('dispatch');

        $action = new JobScheduleUi($this->createAuditTrailFactory(), $bus, $jobGetAction, $jobScheduleAction);
        $criteria = new JobScheduleCriteria();
        $criteria->getJobKeys()->push([$jobA, $jobB, $jobC]);

        try {
            $action->schedule($criteria, $this->createUiActionContext());
            static::fail('JobMissingException expected');
        } catch (JobsMissingException $exception) {
            static::assertSame(1677424700, $exception->getCode());
            static::assertTrue($exception->getJobs()->contains($jobB));
        }
    }

    public function testSkippedJobsWillNotTriggerMessage(): void
    {
        $bus = $this->createMock(MessageBusInterface::class);
        $jobGetAction = $this->createMock(JobGetActionInterface::class);
        $jobScheduleAction = $this->createMock(JobScheduleActionInterface::class);
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
        $jobScheduleAction
            ->expects(static::once())
            ->method('schedule')
            ->willReturnCallback(static function (JobSchedulePayload $payload): JobScheduleResult {
                static::assertCount(3, $payload->getJobKeys());
                static::assertNotEmpty($payload->getMessage());

                return new JobScheduleResult(new JobKeyCollection(), $payload->getJobKeys());
            });
        $bus->expects(static::never())->method('dispatch');

        $action = new JobScheduleUi($this->createAuditTrailFactory(), $bus, $jobGetAction, $jobScheduleAction);
        $criteria = new JobScheduleCriteria();
        $criteria->getJobKeys()->push([$jobA, $jobB, $jobC]);

        $result = $action->schedule($criteria, $this->createUiActionContext());
        static::assertCount(3, $result->getSkippedJobKeys());
        static::assertCount(0, $result->getScheduledJobKeys());
    }
}
