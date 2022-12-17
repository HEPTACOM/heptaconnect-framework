<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action;

/**
 * Base class for any browse UI action.
 * It defines common table-like request states.
 */
abstract class BrowseCriteriaContract
{
    public const SORT_ASC = 'asc';

    public const SORT_DESC = 'desc';

    private ?int $page = null;

    private int $pageSize = 10;

    /**
     * Get the page to query.
     * First page is 1.
     * Any value below 1 disables pagination.
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * Set the page to query.
     * First page is 1.
     * Any value below 1 disables pagination.
     */
    public function setPage(?int $page): void
    {
        $this->page = $page;
    }

    /**
     * Get the number of items to display per page.
     * Any value that is below 1 disables pagination.
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * Set the number of items to display per page.
     * Any value that is below 1 disables pagination.
     */
    public function setPageSize(int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }
}
