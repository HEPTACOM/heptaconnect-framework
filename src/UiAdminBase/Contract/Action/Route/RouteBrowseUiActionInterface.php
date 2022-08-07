<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Route;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteBrowse\RouteBrowseCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteBrowse\RouteBrowseResult;

interface RouteBrowseUiActionInterface
{
    /**
     * Lists all routes by the given criteria.
     *
     * @return iterable<RouteBrowseResult>
     */
    public function browse(RouteBrowseCriteria $criteria): iterable;
}
