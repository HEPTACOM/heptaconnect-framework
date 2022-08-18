<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayloadCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddResultCollection;

final class RouteAddFailedException extends \InvalidArgumentException implements InvalidArgumentThrowableInterface
{
    private RouteAddPayloadCollection $failedToCreate;

    private RouteAddResultCollection $failedToRevert;

    private RouteAddResultCollection $createdAndReverted;

    public function __construct(
        RouteAddPayloadCollection $failedToCreate,
        RouteAddResultCollection $failedToRevert,
        RouteAddResultCollection $createdAndReverted,
        int $code,
        ?\Throwable $previous = null
    ) {
        parent::__construct('Some route scenarios could not be created. Any just created scenarios were tried to delete', $code, $previous);
        $this->failedToCreate = $failedToCreate;
        $this->failedToRevert = $failedToRevert;
        $this->createdAndReverted = $createdAndReverted;
    }

    public function getFailedToCreate(): RouteAddPayloadCollection
    {
        return $this->failedToCreate;
    }

    public function getFailedToRevert(): RouteAddResultCollection
    {
        return $this->failedToRevert;
    }

    public function getCreatedAndReverted(): RouteAddResultCollection
    {
        return $this->createdAndReverted;
    }
}
