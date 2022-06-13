<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class PortalNodeAliasIsAlreadyAssignedException extends \Exception implements InvalidArgumentThrowableInterface
{
    private string $alias;

    private PortalNodeKeyInterface $conflicting;

    public function __construct(string $alias, PortalNodeKeyInterface $conflicting, int $code, ?\Throwable $previous = null)
    {
        parent::__construct('The given alias ' . $alias . ' is already assigned', $code, $previous);
        $this->alias = $alias;
        $this->conflicting = $conflicting;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getConflicting(): PortalNodeKeyInterface
    {
        return $this->conflicting;
    }
}
