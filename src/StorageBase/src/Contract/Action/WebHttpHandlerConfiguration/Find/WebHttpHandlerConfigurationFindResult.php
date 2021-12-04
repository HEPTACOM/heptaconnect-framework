<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\Find;

class WebHttpHandlerConfigurationFindResult
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