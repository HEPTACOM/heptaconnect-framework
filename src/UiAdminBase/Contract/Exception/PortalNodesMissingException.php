<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;

final class PortalNodesMissingException extends \InvalidArgumentException implements InvalidArgumentThrowableInterface
{
    public function __construct(private PortalNodeKeyCollection $portalNodeKeys, int $code, ?\Throwable $previous = null)
    {
        parent::__construct('The given portal node keys do not exist', $code, $previous);
    }

    public function getPortalNodeKeys(): PortalNodeKeyCollection
    {
        return $this->portalNodeKeys;
    }
}
