<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Webhook\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\WebhookKeyInterface;

interface WebhookInterface
{
    public function getKey(): WebhookKeyInterface;

    public function getUrl(): string;

    /**
     * @return class-string<\Psr\Http\Client\ClientInterface>
     */
    public function getHandler();
}
