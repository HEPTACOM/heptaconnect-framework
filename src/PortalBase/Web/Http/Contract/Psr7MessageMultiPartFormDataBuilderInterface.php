<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

use Psr\Http\Message\MessageInterface;

interface Psr7MessageMultiPartFormDataBuilderInterface
{
    public function build(MessageInterface $message, array $parameters): MessageInterface;
}
