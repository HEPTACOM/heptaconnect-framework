<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStorageGet\PortalNodeStorageGetCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStorageGet\PortalNodeStorageGetResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionContextInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\InvalidPortalNodeStorageValueException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\ReadException;

interface PortalNodeStorageGetUiActionInterface extends UiActionInterface
{
    /**
     * Gets the storage values for the given portal node.
     *
     * @throws InvalidPortalNodeStorageValueException
     * @throws PortalNodesMissingException
     * @throws ReadException
     *
     * @return iterable<PortalNodeStorageGetResult>
     */
    public function get(PortalNodeStorageGetCriteria $criteria, UiActionContextInterface $context): iterable;
}
