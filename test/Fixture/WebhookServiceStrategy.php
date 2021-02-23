<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookInterface;
use Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookServiceInterface;
use Http\Discovery\Strategy\DiscoveryStrategy;

class WebhookServiceStrategy implements DiscoveryStrategy
{
    public static function getCandidates($type)
    {
        return [
            [
                'condition' => fn () => \is_a($type, WebhookServiceInterface::class, true),
                'class' => fn () => new class() implements WebhookServiceInterface {
                    public function register(string $webhookHandler, ?array $payload = null): WebhookInterface
                    {
                        throw new \RuntimeException();
                    }

                    public function scheduleRefresh(WebhookInterface $webhook, \DateTimeInterface $dateTime): void
                    {
                    }
                },
            ],
        ];
    }
}
