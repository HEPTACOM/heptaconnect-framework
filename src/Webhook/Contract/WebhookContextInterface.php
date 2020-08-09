<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Webhook\Contract;

interface WebhookContextInterface
{
    public function getWebhook(): WebhookInterface;
}
