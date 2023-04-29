<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\UploadedFileInterface;

interface Psr7MessageMultiPartFormDataBuilderInterface
{
    /**
     * Builds a message body for `Content-Type: multipart/form-data`.
     *
     * If the message already has a header `Content-Type` that indicates `multipart/form-data` with a boundary, this
     * boundary will be used for the generated message body. Otherwise, a new boundary is generated.
     *
     * Each item in `$parameters` MUST be one of the following:
     *  - a scalar value
     *  - Psr\Http\Message\UploadedFileInterface
     *  - an array with the same constraints as `$parameters` (for nesting)
     *
     * The returned message is a modified version of the input message. It has the header `Content-Type` set to indicate
     * `multipart/form-data` with the selected or generated boundary. It also has a message body that represents the
     * provided `$parameters` separated by the boundary.
     *
     * @param array<scalar|UploadedFileInterface|array> $parameters
     */
    public function build(MessageInterface $message, array $parameters): MessageInterface;
}
