<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Webhook\Contract;

interface WebhookServiceInterface
{
    /**
     * @param class-string<\Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookHandlerContract> $webhookHandler
     */
    public function register(string $webhookHandler, ?array $payload = null): WebhookInterface;

    public function scheduleRefresh(WebhookInterface $webhook, \DateTimeInterface $dateTime): void;
}
