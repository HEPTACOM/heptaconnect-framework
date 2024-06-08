<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Web\Http\Dump;

use Heptacom\HeptaConnect\Core\Web\Http\Dump\SampleRateServerRequestCycleDumpChecker;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStackIdentifier;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\ServerRequestCycle;
use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\WebHttpHandlerConfigurationFindActionInterface;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\Dump\SampleRateServerRequestCycleDumpChecker
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStackIdentifier
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\ServerRequestCycle
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindResult
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 */
class SampleRateServerRequestDumpCheckerTest extends TestCase
{
    public function testRequestShallBeDumped(): void
    {
        $configurationFindAction = $this->createMock(WebHttpHandlerConfigurationFindActionInterface::class);

        $configurationFindAction->method('find')
            ->willReturnCallback(static function (WebHttpHandlerConfigurationFindCriteria $criteria): WebHttpHandlerConfigurationFindResult {
                static::assertSame($criteria->getStackIdentifier()->getPath(), 'foo-bar');
                static::assertSame($criteria->getConfigurationKey(), 'dump-sample-rate');

                return new WebHttpHandlerConfigurationFindResult([
                    'value' => 100,
                ]);
            });

        $service = new SampleRateServerRequestCycleDumpChecker($configurationFindAction);

        $requestFactory = Psr17FactoryDiscovery::findServerRequestFactory();
        $request = $requestFactory->createServerRequest('GET', 'http://127.0.0.1/path');

        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $portalNodeKey->method('withoutAlias')->willReturnSelf();
        $portalNodeKey->method('withAlias')->willReturnSelf();

        static::assertTrue($service->shallDump(
            new HttpHandlerStackIdentifier($portalNodeKey, 'foo-bar'),
            new ServerRequestCycle($request, Psr17FactoryDiscovery::findResponseFactory()->createResponse())
        ));
    }

    public function testRequestShallNotBeDumped(): void
    {
        $configurationFindAction = $this->createMock(WebHttpHandlerConfigurationFindActionInterface::class);

        $configurationFindAction->method('find')
            ->willReturnCallback(static function (WebHttpHandlerConfigurationFindCriteria $criteria): WebHttpHandlerConfigurationFindResult {
                static::assertSame($criteria->getStackIdentifier()->getPath(), 'foo-bar');
                static::assertSame($criteria->getConfigurationKey(), 'dump-sample-rate');

                return new WebHttpHandlerConfigurationFindResult([
                    'value' => 0,
                ]);
            });

        $service = new SampleRateServerRequestCycleDumpChecker($configurationFindAction);

        $requestFactory = Psr17FactoryDiscovery::findServerRequestFactory();
        $request = $requestFactory->createServerRequest('GET', 'http://127.0.0.1/path');

        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $portalNodeKey->method('withoutAlias')->willReturnSelf();
        $portalNodeKey->method('withAlias')->willReturnSelf();

        static::assertFalse($service->shallDump(
            new HttpHandlerStackIdentifier($portalNodeKey, 'foo-bar'),
            new ServerRequestCycle($request, Psr17FactoryDiscovery::findResponseFactory()->createResponse())
        ));
    }
}
