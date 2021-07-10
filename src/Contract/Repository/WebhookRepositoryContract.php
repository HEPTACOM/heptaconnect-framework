<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Repository;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\WebhookKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

/**
 * @internal
 */
abstract class WebhookRepositoryContract
{
    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookHandlerContract> $handler
     *
     * @throws UnsupportedStorageKeyException
     */
    abstract public function create(
        PortalNodeKeyInterface $portalNodeKey,
        string $url,
        string $handler,
        ?array $payload = null
    ): WebhookKeyInterface;

    /**
     * @throws UnsupportedStorageKeyException
     * @throws NotFoundException
     */
    abstract public function read(WebhookKeyInterface $key): WebhookInterface;

    /**
     * @psalm-return iterable<\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\WebhookKeyInterface>
     *
     * @throws UnsupportedStorageKeyException
     */
    abstract public function listByUrl(string $url): iterable;
}
