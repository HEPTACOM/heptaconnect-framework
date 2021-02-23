<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Support;

use Heptacom\HeptaConnect\Portal\Base\Parallelization\Contract\ResourceLockingContract;
use Heptacom\HeptaConnect\Portal\Base\Publication\Contract\PublisherInterface;
use Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookServiceInterface;
use Http\Discovery\ClassDiscovery;
use Http\Discovery\Exception\ClassInstantiationFailedException;

class ServiceDiscovery extends ClassDiscovery
{
    public static function findWebhookService(): WebhookServiceInterface
    {
        $service = static::instantiateClass(static::findOneByType(WebhookServiceInterface::class));

        if (!$service instanceof WebhookServiceInterface) {
            throw new ClassInstantiationFailedException();
        }

        return $service;
    }

    public static function findResourceLockingService(): ResourceLockingContract
    {
        $service = static::instantiateClass(static::findOneByType(ResourceLockingContract::class));

        if (!$service instanceof ResourceLockingContract) {
            throw new ClassInstantiationFailedException();
        }

        return $service;
    }

    public static function findPublisher(): PublisherInterface
    {
        $service = static::instantiateClass(static::findOneByType(PublisherInterface::class));

        if (!$service instanceof PublisherInterface) {
            throw new ClassInstantiationFailedException();
        }

        return $service;
    }
}
