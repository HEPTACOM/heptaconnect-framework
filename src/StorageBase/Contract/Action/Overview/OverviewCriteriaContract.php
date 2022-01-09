<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Overview;

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

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getPageSize(): ?int
    {
        return $this->pageSize;
    }

    public function setPageSize(?int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @return array<string, string>
     */
    public function getSort(): array
    {
        return $this->sort;
    }

    /**
     * @param array<string, string> $sort
     */
    public function setSort(array $sort): void
    {
        $this->sort = $sort;
    }
}
