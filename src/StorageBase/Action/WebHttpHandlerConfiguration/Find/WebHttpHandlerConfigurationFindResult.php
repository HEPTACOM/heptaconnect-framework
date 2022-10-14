<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class WebHttpHandlerConfigurationFindResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private ?array $value;

    public function __construct(?array $value)
    {
        $this->attachments = new AttachmentCollection();
        $this->value = $value;
    }

    public function getValue(): ?array
    {
        return $this->value;
    }
}
