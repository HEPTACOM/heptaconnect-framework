<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Exception;

use Throwable;

class NotFoundException extends \Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct('The requested element could not be found', 0, $previous);
    }
}
