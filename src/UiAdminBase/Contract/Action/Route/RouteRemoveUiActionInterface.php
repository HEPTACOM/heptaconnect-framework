<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Route;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteRemove\RouteRemoveCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionContextInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\InvalidArgumentThrowableInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PersistException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\RoutesMissingException;

interface RouteRemoveUiActionInterface extends UiActionInterface
{
    /**
     * Remove routes by their keys.
     *
     * @throws InvalidArgumentThrowableInterface
     * @throws PersistException
     * @throws RoutesMissingException
     */
    public function remove(RouteRemoveCriteria $criteria, UiActionContextInterface $context): void;
}
