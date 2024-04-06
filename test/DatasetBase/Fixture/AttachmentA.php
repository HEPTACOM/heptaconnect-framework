<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test\Fixture;

use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachableInterface;
use Psr\Log\NullLogger;

class AttachmentA extends NullLogger implements AttachableInterface
{
}
