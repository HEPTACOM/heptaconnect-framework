<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview;

/**
 * Base class for any overview storage action.
 * It is expected to be used in a human interaction and pre-defines common table-like request states.
 */
abstract class OverviewCriteriaContract
{
    public const SORT_ASC = 'asc';

    public const SORT_DESC = 'desc';

    protected int $page = 0;

    protected ?int $pageSize = null;

    /**
     * @var array<string, string>
     */
    protected array $sort = [];

    /**
     * Get the page to query.
     * First page is 1.
     * Any value below 1 disables pagination.
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * Set the page to query.
     * First page is 1.
     * Any value below 1 disables pagination.
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * Get the number of items to display per page.
     * Any value that is null or below 1 disables pagination.
     */
    public function getPageSize(): ?int
    {
        return $this->pageSize;
    }

    /**
     * Set the number of items to display per page.
     * Any value that is null or below 1 disables pagination.
     */
    public function setPageSize(?int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }

    /**
     * Get all sorting instructions.
     * As key you have to set the overview specific column key and as value the sorting direction.
     * Sorting directions are either @see \Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract::SORT_ASC or @see \Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract::SORT_DESC
     *
     * @return array<string, string>
     */
    public function getSort(): array
    {
        return $this->sort;
    }

    /**
     * Set all sorting instructions.
     * As key you have to set the overview specific column key and as value the sorting direction.
     * Sorting directions are either @see \Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract::SORT_ASC or @see \Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview\OverviewCriteriaContract::SORT_DESC
     *
     * @param array<string, string> $sort
     */
    public function setSort(array $sort): void
    {
        $this->sort = $sort;
    }
}
