<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test\Fixture;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;
use Psr\Log\NullLogger;

class AttachmentA extends NullLogger implements AttachableInterface
{
}
