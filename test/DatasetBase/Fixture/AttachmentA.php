<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test\Fixture;

use Heptacom\HeptaConnect\Utility\Contract\AttachableInterface;
use Psr\Log\NullLogger;

class AttachmentA extends NullLogger implements AttachableInterface
{
}
