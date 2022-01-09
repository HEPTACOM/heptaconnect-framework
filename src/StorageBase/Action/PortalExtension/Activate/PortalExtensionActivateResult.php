<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Activate;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;

class PortalExtensionActivateResult
{
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
