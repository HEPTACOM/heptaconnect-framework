<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayloadCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddResultCollection;

final class RouteAddFailedException extends \InvalidArgumentException implements InvalidArgumentThrowableInterface
{
    public function __construct(
        private RouteAddPayloadCollection $failedToCreate,
        private RouteAddResultCollection $failedToRevert,
        private RouteAddResultCollection $createdAndReverted,
        int $code,
        ?\Throwable $previous = null
    ) {
        $allRevertedMessage = 'Some route scenarios could not be created. All just created scenarios were deleted';
        $notAllRevertedMessage = 'Some route scenarios could not be created. Some just created scenarios could not be deleted';

        parent::__construct($this->failedToRevert->isEmpty() ? $allRevertedMessage : $notAllRevertedMessage, $code, $previous);
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
