<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Route;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayloadCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddResultCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\InvalidArgumentThrowableInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PersistException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeMissingException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\RouteAlreadyExistsException;

interface RouteAddUiActionInterface
{
    /**
     * Creates new routes between portal nodes by given entities and capabilities.
     *
     * @throws InvalidArgumentThrowableInterface
     * @throws PersistException
     * @throws PortalNodeMissingException
     * @throws RouteAlreadyExistsException
     */
    public function add(RouteAddPayloadCollection $payloads): RouteAddResultCollection;
}
