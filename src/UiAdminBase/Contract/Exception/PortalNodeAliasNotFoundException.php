<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

final class PortalNodeAliasNotFoundException extends \RuntimeException implements InvalidArgumentThrowableInterface
{
    private string $portalNodeAlias;

    public function __construct(string $portalNodeAlias, int $code, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('No portal node by alias "%s" found', $portalNodeAlias), $code, $previous);
        $this->portalNodeAlias = $portalNodeAlias;
    }

    public function getPortalNodeAlias(): string
    {
        return $this->portalNodeAlias;
    }
}
