<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportPayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionContextInterface;

interface PortalNodeStatusReportUiActionInterface
{
    /**
     * Perform status report on a portal node.
     *
     * @return iterable<string, PortalNodeStatusReportResult>
     */
    public function report(PortalNodeStatusReportPayload $payloads, UiActionContextInterface $context): iterable;
}
