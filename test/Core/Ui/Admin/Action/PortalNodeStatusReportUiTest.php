<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\StatusReporting\Contract\StatusReportingServiceInterface;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeStatusReportUi;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportPayload;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeStatusReportUi
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportPayload
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportResult
 */
final class PortalNodeStatusReportUiTest extends TestCase
{
    public function testPayloads(): void
    {
        $statusReporterService = $this->createMock(StatusReportingServiceInterface::class);
        $action = new PortalNodeStatusReportUi($statusReporterService);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());

        $statusReporterService->method('report')->willReturnOnConsecutiveCalls(
            [
                StatusReporterContract::TOPIC_HEALTH => [
                    StatusReporterContract::TOPIC_HEALTH => true,
                    'key' => 'value',
                ],
            ],
            [
                StatusReporterContract::TOPIC_HEALTH => [
                    StatusReporterContract::TOPIC_HEALTH => false,
                    'key' => 'value',
                ],
            ],
        );

        $criteria = new PortalNodeStatusReportPayload($portalNodeKey, [StatusReporterContract::TOPIC_HEALTH]);
        $reportResult = \iterable_to_array($action->report($criteria));
        static::assertCount(1, $reportResult);
        static::assertSame(StatusReporterContract::TOPIC_HEALTH, $reportResult[StatusReporterContract::TOPIC_HEALTH]->getTopic());
        static::assertTrue($reportResult[StatusReporterContract::TOPIC_HEALTH]->getSuccess());

        $reportResult = \iterable_to_array($action->report($criteria));
        static::assertCount(1, $reportResult);
        static::assertSame(StatusReporterContract::TOPIC_HEALTH, $reportResult[StatusReporterContract::TOPIC_HEALTH]->getTopic());
        static::assertFalse($reportResult[StatusReporterContract::TOPIC_HEALTH]->getSuccess());
    }
}
