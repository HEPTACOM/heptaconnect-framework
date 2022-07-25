<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test;

use Heptacom\HeptaConnect\Core\Portal\Exception\InaccessableConstructorOnInstantionException;
use Heptacom\HeptaConnect\Core\Portal\Exception\UnexpectedRequiredParameterInConstructorOnInstantionException;
use Heptacom\HeptaConnect\Core\Portal\PortalFactory;
use Heptacom\HeptaConnect\Core\Test\Fixture\DependentPortal;
use Heptacom\HeptaConnect\Core\Test\Fixture\DependentPortalExtension;
use Heptacom\HeptaConnect\Core\Test\Fixture\UninstantiablePortal;
use Heptacom\HeptaConnect\Core\Test\Fixture\UninstantiablePortalExtension;
use HeptacomFixture\Portal\A\Portal;
use HeptacomFixture\Portal\Extension\PortalExtension;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Portal\Contract\PortalFactoryContract
 * @covers \Heptacom\HeptaConnect\Core\Portal\Exception\AbstractInstantiationException
 * @covers \Heptacom\HeptaConnect\Core\Portal\Exception\InaccessableConstructorOnInstantionException
 * @covers \Heptacom\HeptaConnect\Core\Portal\Exception\UnexpectedRequiredParameterInConstructorOnInstantionException
 * @covers \Heptacom\HeptaConnect\Core\Portal\PortalFactory
 */
final class PortalFactoryTest extends TestCase
{
    public function testPortal(): void
    {
        $portalFactory = new PortalFactory();
        require_once __DIR__ . '/../../test-composer-integration/portal-package/src/Portal.php';

        static::assertInstanceOf(Portal::class, $portalFactory->instantiatePortal(Portal::class()));
    }

    public function testPortalExtension(): void
    {
        $portalFactory = new PortalFactory();
        require_once __DIR__ . '/../../test-composer-integration/portal-package-extension/src/PortalExtension.php';

        static::assertInstanceOf(PortalExtension::class, $portalFactory->instantiatePortalExtension(PortalExtension::class()));
    }

    public function testFailingAtNonInstantiablePortalClasses(): void
    {
        $portalFactory = new PortalFactory();

        try {
            $portalFactory->instantiatePortal(UninstantiablePortal::class());
        } catch (InaccessableConstructorOnInstantionException $exception) {
            static::assertEquals(UninstantiablePortal::class, $exception->getClass());

            static::assertEquals(0, $exception->getCode());
            static::assertNull($exception->getPrevious());
            static::assertStringContainsString('Could not instantiate object', $exception->getMessage());
        }
    }

    public function testFailingAtNonInstantiablePortalExtensionClasses(): void
    {
        $portalFactory = new PortalFactory();

        try {
            $portalFactory->instantiatePortalExtension(UninstantiablePortalExtension::class());
        } catch (InaccessableConstructorOnInstantionException $exception) {
            static::assertEquals(UninstantiablePortalExtension::class, $exception->getClass());

            static::assertEquals(0, $exception->getCode());
            static::assertNull($exception->getPrevious());
            static::assertStringContainsString('Could not instantiate object', $exception->getMessage());
        }
    }

    public function testFailingAtDependentPortalClasses(): void
    {
        $portalFactory = new PortalFactory();

        try {
            $portalFactory->instantiatePortal(DependentPortal::class());
            static::fail();
        } catch (UnexpectedRequiredParameterInConstructorOnInstantionException $exception) {
            static::assertEquals(DependentPortal::class, $exception->getClass());

            static::assertEquals(0, $exception->getCode());
            static::assertNull($exception->getPrevious());
            static::assertStringContainsString('Could not instantiate object', $exception->getMessage());
        }
    }

    public function testFailingAtDependentPortalExtensionClasses(): void
    {
        $portalFactory = new PortalFactory();

        try {
            $portalFactory->instantiatePortalExtension(DependentPortalExtension::class());
            static::fail();
        } catch (UnexpectedRequiredParameterInConstructorOnInstantionException $exception) {
            static::assertEquals(DependentPortalExtension::class, $exception->getClass());

            static::assertEquals(0, $exception->getCode());
            static::assertNull($exception->getPrevious());
            static::assertStringContainsString('Could not instantiate object', $exception->getMessage());
        }
    }
}
