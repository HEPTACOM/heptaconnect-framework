<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test;

use Heptacom\HeptaConnect\Core\Portal\Contract\PortalFactoryContract;
use Heptacom\HeptaConnect\Core\Portal\Exception\AbstractInstantiationException;
use Heptacom\HeptaConnect\Core\Portal\Exception\InaccessableConstructorOnInstantionException;
use Heptacom\HeptaConnect\Core\Portal\PortalFactory;
use Heptacom\HeptaConnect\Core\Test\Fixture\UninstantiablePortal;
use Heptacom\HeptaConnect\Core\Test\Fixture\UninstantiablePortalExtension;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionType;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalType;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use HeptacomFixture\Portal\A\Portal;
use HeptacomFixture\Portal\Extension\PortalExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PortalFactoryContract::class)]
#[CoversClass(AbstractInstantiationException::class)]
#[CoversClass(InaccessableConstructorOnInstantionException::class)]
#[CoversClass(PortalFactory::class)]
#[CoversClass(PackageContract::class)]
#[CoversClass(PortalContract::class)]
#[CoversClass(PortalExtensionContract::class)]
#[CoversClass(PortalExtensionType::class)]
#[CoversClass(PortalType::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
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
}
