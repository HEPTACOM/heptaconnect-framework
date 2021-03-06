<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Activate;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;

final class PortalExtensionActivateResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @var array<class-string<PortalExtensionContract>>
     */
    private array $passedActivations;

    /**
     * @var array<class-string<PortalExtensionContract>>
     */
    private array $failedActivations;

    /**
     * @param array<class-string<PortalExtensionContract>> $passedActivations
     * @param array<class-string<PortalExtensionContract>> $failedActivations
     */
    public function __construct(array $passedActivations, array $failedActivations)
    {
        $this->attachments = new AttachmentCollection();
        $this->passedActivations = $passedActivations;
        $this->failedActivations = $failedActivations;
    }

    /**
     * @return array<class-string<PortalExtensionContract>>
     */
    public function getPassedActivations(): array
    {
        return $this->passedActivations;
    }

    /**
     * @return array<class-string<PortalExtensionContract>>
     */
    public function getFailedActivations(): array
    {
        return $this->failedActivations;
    }

    public function isSuccess(): bool
    {
        return $this->failedActivations === [];
    }
}
