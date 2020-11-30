<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Webhook\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeContextInterface;

interface WebhookContextInterface extends PortalNodeContextInterface
{
    public function getWebhook(): WebhookInterface;
}
