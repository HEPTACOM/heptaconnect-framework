<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Find;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class PortalNodeAliasFindCriteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @param string[] $alias
     */
    public function __construct(
        private array $alias
    ) {
    }

    /**
     * @return string[]
     */
    public function getAlias(): array
    {
        return $this->alias;
    }

    /**
     * @param string[] $alias
     */
    public function setAlias(array $alias): void
    {
        $this->alias = $alias;
    }
}
