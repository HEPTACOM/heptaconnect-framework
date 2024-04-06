<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Activate;

use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionTypeCollection;
use Heptacom\HeptaConnect\Utility\Contract\AttachmentAwareInterface;

final class PortalExtensionActivateResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private PortalExtensionTypeCollection $passedActivations,
        private PortalExtensionTypeCollection $failedActivations
    ) {
    }

    public function getPassedActivations(): PortalExtensionTypeCollection
    {
        return $this->passedActivations;
    }

    public function getFailedActivations(): PortalExtensionTypeCollection
    {
        return $this->failedActivations;
    }

    public function isSuccess(): bool
    {
        return $this->failedActivations->count() < 1;
    }
}
