<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Route;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayloadCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddResultCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionContextInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\InvalidArgumentThrowableInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PersistException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\RouteAddFailedException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\RouteAlreadyExistsException;

interface RouteAddUiActionInterface
{
    /**
     * Creates new routes between portal nodes by given entities and capabilities.
     *
     * @throws InvalidArgumentThrowableInterface
     * @throws PersistException
     * @throws PortalNodesMissingException
     * @throws RouteAddFailedException
     * @throws RouteAlreadyExistsException
     */
    public function add(RouteAddPayloadCollection $payloads, UiActionContextInterface $context): RouteAddResultCollection;
}
