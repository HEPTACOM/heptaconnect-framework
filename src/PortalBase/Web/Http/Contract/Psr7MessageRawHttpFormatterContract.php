<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

/**
 * Describes PSR-7 message formatter to generate files containing raw HTTP protocol text,
 * that SHOULD be usable with TCP tools like netcat and telnet.
 */
abstract class Psr7MessageRawHttpFormatterContract extends Psr7MessageFormatterContract
{
}
