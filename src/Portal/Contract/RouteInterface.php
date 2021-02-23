<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\RouteKeyInterface;

interface RouteInterface
{
    public function getKey(): RouteKeyInterface;

    public function getTargetKey(): PortalNodeKeyInterface;

    public function getSourceKey(): PortalNodeKeyInterface;

    /**
     * @psalm-return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    public function getEntityClassName(): string;
}
