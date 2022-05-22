<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportPayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStatusReport\PortalNodeStatusReportResult;

interface PortalNodeStatusReportUiActionInterface
{
    /**
     * Perform status report on a portal node.
     *
     * @return iterable<PortalNodeStatusReportResult>
     */
    public function report(PortalNodeStatusReportPayload $payloads): iterable;
}
