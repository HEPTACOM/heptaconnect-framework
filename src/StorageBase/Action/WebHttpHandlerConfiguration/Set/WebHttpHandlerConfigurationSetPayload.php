<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Set;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStackIdentifier;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

final class WebHttpHandlerConfigurationSetPayload implements CreatePayloadInterface, AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private HttpHandlerStackIdentifier $stackIdentifier,
        private string $configurationKey,
        private ?array $configurationValue = null
    ) {
    }

    public function getStackIdentifier(): HttpHandlerStackIdentifier
    {
        return $this->stackIdentifier;
    }

    public function setStackIdentifier(HttpHandlerStackIdentifier $stackIdentifier): void
    {
        $this->stackIdentifier = $stackIdentifier;
    }

    public function getConfigurationKey(): string
    {
        return $this->configurationKey;
    }

    public function setConfigurationKey(string $configurationKey): void
    {
        $this->configurationKey = $configurationKey;
    }

    public function getConfigurationValue(): ?array
    {
        return $this->configurationValue;
    }

    public function setConfigurationValue(?array $configurationValue): void
    {
        $this->configurationValue = $configurationValue;
    }
}
