<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test\Bridge;

use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeServiceExceptionInterface;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Support\Psr11StorageFacade;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Storage\Base\Bridge\Exception\StorageFacadeServiceException
 * @covers \Heptacom\HeptaConnect\Storage\Base\Bridge\Support\AbstractSingletonStorageFacade
 * @covers \Heptacom\HeptaConnect\Storage\Base\Bridge\Support\Psr11StorageFacade
 */
class Psr11StorageFacadeTest extends TestCase
{
    public function testMissingServiceException(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')->willReturn(false);
        $container->method('get')->willThrowException(new \Exception('Action not found'));
        $facade = new Psr11StorageFacade($container);

        try {
            $facade->getIdentityMapAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getIdentityDirectionCreateAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getIdentityDirectionDeleteAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getIdentityOverviewAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getIdentityPersistAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getIdentityReflectAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobCreateAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobDeleteAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobFailAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobFinishAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobGetAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobListFinishedAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobScheduleAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobStartAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalExtensionActivateAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalExtensionDeactivateAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalExtensionFindAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getRouteCreateAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getRouteDeleteAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getRouteFindAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getRouteGetAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getRouteOverviewAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getReceptionRouteListAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeCreateAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeDeleteAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeGetAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeListAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeOverviewAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeConfigurationGetAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeConfigurationSetAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeStorageClearAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeStorageDeleteAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeStorageGetAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeStorageListAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeStorageSetAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getStorageKeyGenerator();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getRouteCapabilityOverviewAction();
            static::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        static::assertTrue(true, 'We just do not expect an exception');
    }

    public function testServiceNotFail(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')->willReturn(true);
        $container->method('get')->willReturnCallback(fn (string $className) => $this->createMock($className));
        $facade = new Psr11StorageFacade($container);

        $facade->getIdentityDirectionCreateAction();
        $facade->getIdentityDirectionDeleteAction();
        $facade->getIdentityMapAction();
        $facade->getIdentityOverviewAction();
        $facade->getIdentityPersistAction();
        $facade->getIdentityReflectAction();
        $facade->getJobCreateAction();
        $facade->getJobDeleteAction();
        $facade->getJobFailAction();
        $facade->getJobFinishAction();
        $facade->getJobGetAction();
        $facade->getJobListFinishedAction();
        $facade->getJobScheduleAction();
        $facade->getJobStartAction();
        $facade->getPortalExtensionActivateAction();
        $facade->getPortalExtensionDeactivateAction();
        $facade->getPortalExtensionFindAction();
        $facade->getRouteCreateAction();
        $facade->getRouteDeleteAction();
        $facade->getRouteFindAction();
        $facade->getRouteGetAction();
        $facade->getRouteOverviewAction();
        $facade->getReceptionRouteListAction();
        $facade->getPortalNodeCreateAction();
        $facade->getPortalNodeDeleteAction();
        $facade->getPortalNodeGetAction();
        $facade->getPortalNodeListAction();
        $facade->getPortalNodeOverviewAction();
        $facade->getPortalNodeConfigurationGetAction();
        $facade->getPortalNodeConfigurationSetAction();
        $facade->getPortalNodeStorageClearAction();
        $facade->getPortalNodeStorageDeleteAction();
        $facade->getPortalNodeStorageGetAction();
        $facade->getPortalNodeStorageListAction();
        $facade->getPortalNodeStorageSetAction();
        $facade->getStorageKeyGenerator();
        $facade->getRouteCapabilityOverviewAction();

        static::assertTrue(true, 'We just do not expect an exception');
    }
}
