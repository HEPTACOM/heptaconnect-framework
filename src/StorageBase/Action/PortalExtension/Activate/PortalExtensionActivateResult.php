<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Activate;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionTypeCollection;

final class PortalExtensionActivateResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalExtensionTypeCollection $passedActivations;

    private PortalExtensionTypeCollection $failedActivations;

    public function __construct(
        PortalExtensionTypeCollection $passedActivations,
        PortalExtensionTypeCollection $failedActivations
    ) {
        $this->attachments = new AttachmentCollection();
        $this->passedActivations = $passedActivations;
        $this->failedActivations = $failedActivations;
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
