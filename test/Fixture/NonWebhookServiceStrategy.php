<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookServiceInterface;
use Http\Discovery\Strategy\DiscoveryStrategy;

class NonWebhookServiceStrategy implements DiscoveryStrategy
{
    public static function getCandidates($type)
    {
        return [
            [
                'condition' => fn () => \is_a($type, WebhookServiceInterface::class, true),
                'class' => fn () => null,
            ],
        ];
    }
}
