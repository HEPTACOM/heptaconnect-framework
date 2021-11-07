<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

class RouteOverviewCriteria
{
    public const SORT_ASC = 'asc';

    public const SORT_DESC = 'desc';

    public const FIELD_TARGET = 'target';

    public const FIELD_SOURCE = 'source';

    public const FIELD_ENTITY_TYPE = 'entityType';

    public const FIELD_CREATED = 'created';

    protected int $page = 0;

    protected ?int $pageSize = null;

    protected array $sort = [
        self::FIELD_CREATED => self::SORT_ASC,
    ];

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

    public function getSort(): array
    {
        return $this->sort;
    }

    public function setSort(array $sort): void
    {
        $this->sort = $sort;
    }
}
