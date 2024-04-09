<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find;

use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;

final class WebHttpHandlerConfigurationFindResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private ?array $value
    ) {
    }

    public function getValue(): ?array
    {
        return $this->value;
    }
}
