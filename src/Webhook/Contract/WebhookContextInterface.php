<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Webhook\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface;

interface WebhookContextInterface
{
    public function getPortal(): PortalContract;

    public function getConfig(): ?array;

    public function getWebhook(): WebhookInterface;

    public function getStorage(): PortalStorageInterface;
}
