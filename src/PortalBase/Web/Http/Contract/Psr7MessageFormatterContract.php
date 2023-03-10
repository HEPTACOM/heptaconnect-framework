<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

use Psr\Http\Message\MessageInterface;

/**
 * Describes PSR-7 message formatter, that is useful for dumping requests and responses into files.
 */
abstract class Psr7MessageFormatterContract
{
    /**
     * Formats the given message into a string in the implementation format.
     *
     * @throws \InvalidArgumentException when the given message type is not supported
     */
    public function formatMessage(MessageInterface $message): string
    {
        throw new \InvalidArgumentException();
    }

    /**
     * Returns a suggested file extension for the format, when stored in a file.
     *
     * @throws \InvalidArgumentException when the given message type is not supported
     */
    public function getFileExtension(MessageInterface $message): string
    {
        throw new \InvalidArgumentException();
    }
}
