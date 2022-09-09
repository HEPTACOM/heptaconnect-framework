<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;

final class PortalNodesMissingException extends \InvalidArgumentException implements InvalidArgumentThrowableInterface
{
    private PortalNodeKeyCollection $portalNodeKeys;

    public function __construct(PortalNodeKeyCollection $portalNodeKeys, int $code, ?\Throwable $previous = null)
    {
        parent::__construct('The given portal node keys do not exist', $code, $previous);
        $this->portalNodeKeys = $portalNodeKeys;
    }

    public function getPortalNodeKeys(): PortalNodeKeyCollection
    {
        return $this->portalNodeKeys;
    }
}
