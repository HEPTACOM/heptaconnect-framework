<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

interface WebhookInterface
{
    public function getKey(): WebhookKeyInterface;

    public function getUrl(): string;

    /**
     * @return class-string<\Psr\Http\Client\ClientInterface>
     */
    public function getHandler();
}
