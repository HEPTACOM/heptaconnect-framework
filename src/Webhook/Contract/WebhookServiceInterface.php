<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Webhook\Contract;

interface WebhookServiceInterface
{
    /**
     * @param class-string<\Psr\Http\Client\ClientInterface> $webhookHandler
     */
    public function register(string $webhookHandler): WebhookInterface;

    public function scheduleRefresh(WebhookInterface $webhook, \DateTimeInterface $dateTime): void;
}
