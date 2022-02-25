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
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getIdentityOverviewAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getIdentityPersistAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getIdentityReflectAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobCreateAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobDeleteAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobFailAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobFinishAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobGetAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobListFinishedAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobScheduleAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getJobStartAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalExtensionActivateAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalExtensionDeactivateAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalExtensionFindAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getRouteCreateAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getRouteDeleteAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getRouteFindAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getRouteGetAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getRouteOverviewAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getReceptionRouteListAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeCreateAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeDeleteAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeGetAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeListAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeOverviewAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeConfigurationGetAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getPortalNodeConfigurationSetAction();
            self::fail();
        } catch (StorageFacadeServiceExceptionInterface $throwable) {
            static::assertSame('Action not found', $throwable->getPrevious()->getMessage());
        }

        try {
            $facade->getRouteCapabilityOverviewAction();
            self::fail();
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
        $facade->getRouteCapabilityOverviewAction();

        static::assertTrue(true, 'We just do not expect an exception');
    }
}
