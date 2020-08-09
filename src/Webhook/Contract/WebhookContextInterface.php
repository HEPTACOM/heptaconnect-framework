<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Webhook\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;

interface WebhookContextInterface
{
    public function getPortal(): PortalContract;

    public function getConfig(): ?array;

    public function getWebhook(): WebhookInterface;
}
