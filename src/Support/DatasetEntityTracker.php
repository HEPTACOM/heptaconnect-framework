<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityTrackerContract;

class DatasetEntityTracker extends DatasetEntityTrackerContract
{
    public static function instance(): DatasetEntityTrackerContract
    {
        /** @var DatasetEntityTracker|null $instance */
        static $instance = null;

        if (!$instance instanceof DatasetEntityTrackerContract) {
            $instance = new DatasetEntityTracker();
        }

        return $instance;
    }
}
