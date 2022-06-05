<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;

final class PortalExtensionMissingException extends \InvalidArgumentException implements InvalidArgumentThrowableInterface
{
    /**
     * @var class-string<PortalExtensionContract>
     */
    private string $portalExtensionClass;

    /**
     * @param class-string<PortalExtensionContract> $portalExtensionClass
     */
    public function __construct(string $portalExtensionClass, int $code, ?\Throwable $previous = null)
    {
        parent::__construct('The given portal extension does not exist', $code, $previous);
        $this->portalExtensionClass = $portalExtensionClass;
    }

    /**
     * @return class-string<PortalExtensionContract>
     */
    public function getPortalExtensionClass(): string
    {
        return $this->portalExtensionClass;
    }
}
