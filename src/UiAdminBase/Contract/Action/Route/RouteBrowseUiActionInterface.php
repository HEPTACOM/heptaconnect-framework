<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Route;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteBrowse\RouteBrowseCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteBrowse\RouteBrowseResult;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionContextInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\UnsupportedSortingException;

interface RouteBrowseUiActionInterface extends UiActionInterface
{
    /**
     * Lists all routes by the given criteria.
     *
     * @throws UnsupportedSortingException
     *
     * @return iterable<RouteBrowseResult>
     */
    public function browse(RouteBrowseCriteria $criteria, UiActionContextInterface $context): iterable;
}
