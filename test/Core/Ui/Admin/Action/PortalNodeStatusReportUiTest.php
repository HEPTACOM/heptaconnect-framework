<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\StatusReporting\Contract\StatusReportingServiceInterface;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeStatusReportUi;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportPayloads;
use Heptacom\HeptaConnect\Ui\Admin\Symfony\Test\Fixture\Portal\UiAdminPortal;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeStatusReportUi
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportPayloads
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportResult
 */
final class PortalNodeStatusReportUiTest extends TestCase
{
    public function testPayloads(): void
    {
        $statusReporterService = $this->createMock(StatusReportingServiceInterface::class);
        $action = new PortalNodeStatusReportUi($statusReporterService);
        $portalNodeKey = new PreviewPortalNodeKey(UiAdminPortal::class);

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

        $criteria = new PortalNodeStatusReportPayloads($portalNodeKey, [StatusReporterContract::TOPIC_HEALTH]);
        $reportResult = \iterable_to_array($action->report($criteria));
        static::assertCount(1, $reportResult);
        static::assertSame(StatusReporterContract::TOPIC_HEALTH, $reportResult[0]->getTopic());
        static::assertTrue($reportResult[0]->getSuccess());

        $reportResult = \iterable_to_array($action->report($criteria));
        static::assertCount(1, $reportResult);
        static::assertSame(StatusReporterContract::TOPIC_HEALTH, $reportResult[0]->getTopic());
        static::assertFalse($reportResult[0]->getSuccess());
    }
}
