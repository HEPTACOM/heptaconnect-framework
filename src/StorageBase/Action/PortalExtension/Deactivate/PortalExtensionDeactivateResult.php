<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Deactivate;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionTypeCollection;

final class PortalExtensionDeactivateResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalExtensionTypeCollection $passedDeactivations;

    private PortalExtensionTypeCollection $failedDeactivations;

    public function __construct(
        PortalExtensionTypeCollection $passedDeactivations,
        PortalExtensionTypeCollection $failedDeactivations
    ) {
        $this->attachments = new AttachmentCollection();
        $this->passedDeactivations = $passedDeactivations;
        $this->failedDeactivations = $failedDeactivations;
    }

    public function getPassedDeactivations(): PortalExtensionTypeCollection
    {
        return $this->passedDeactivations;
    }

    public function getFailedDeactivations(): PortalExtensionTypeCollection
    {
        return $this->failedDeactivations;
    }

    public function isSuccess(): bool
    {
        return $this->failedDeactivations->count() < 1;
    }
}
