<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find;

final class WebHttpHandlerConfigurationFindResult
{
    protected ?array $value;

    public function __construct(?array $value)
    {
        $this->value = $value;
    }

    public function getValue(): ?array
    {
        return $this->value;
    }
}
