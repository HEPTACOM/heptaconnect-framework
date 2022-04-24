<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeRemove\PortalNodeRemoveCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\InvalidArgumentThrowableInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PersistException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeMissingException;

interface PortalNodeRemoveUiActionInterface
{
    /**
     * Remove portal nodes by their keys.
     *
     * @throws InvalidArgumentThrowableInterface
     * @throws PersistException
     * @throws PortalNodeMissingException
     */
    public function remove(PortalNodeRemoveCriteria $criteria): void;
}
