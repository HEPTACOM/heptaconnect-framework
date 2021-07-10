<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Webhook\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\WebhookKeyInterface;

/**
 * @internal
 */
interface WebhookInterface
{
    public function getPortalNodeKey(): PortalNodeKeyInterface;

    public function getKey(): WebhookKeyInterface;

    public function getUrl(): string;

    /**
     * @return class-string<\Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookHandlerContract>
     */
    public function getHandler(): string;

    public function getPayload(): ?array;
}
