<?php


interface ITableSource
{
    public function getCount(?string $filter = null): int;

    public function getAll(?string $sortedBy = "", ?string $sortDirection = "", int $page = 0, int $pageSize = 10, ?string $filter = ""): array;
}