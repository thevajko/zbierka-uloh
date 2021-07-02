<?php

use JetBrains\PhpStorm\Pure;

require_once "ITableSource.php";
require_once "Column.php";

class Table
{
    private string $orderBy = "";
    private string $direction = "";

    private int $pageSize = 10;
    private int $page = 0;
    private int $itemsCount = 0;
    private int $totalPages = 0;

    private string $filter = "";

    private ITableSource $dataSource;
    /** @var Column[] */
    private array $columns = [];

    public function __construct(ITableSource $dataSource)
    {
        $this->dataSource = $dataSource;
        $this->orderBy = $_GET['order'] ?? "";
        $this->direction = $_GET['direction'] ?? "";
        $this->filter =  str_replace( "'", "",$_GET['filter'] ?? "");

        $this->page = $this->getPageNumber();

    }

    public function addColumn(string $field, string $title, ?Closure $renderer = null): self {
        if ($renderer == null) {
            $renderer = fn($row) => htmlentities($row->$field);
        }
        $this->columns[] = new Column($field, $title, $renderer);
        return $this;
    }

    public function render() : string
    {
        return $this->renderFilter()."<table border=\"1\">{$this->renderHead()}{$this->renderBody()}</table>". $this->renderPaginator();
    }

    private function getPageNumber(): int
    {
        $this->itemsCount = $this->dataSource->getCount($this->filter);
        $page =  intval($_GET['page'] ?? 0);
        $this->totalPages = ceil($this->itemsCount / $this->pageSize);
        if (($page < 0) || $page > $this->totalPages){
            return 0;
        }
        return $page;
    }

    private function filterColumn($name): string {
        if (!empty($name) && in_array($name, array_map(fn(Column $c) => $c->getField(), $this->columns))) {
            return $name;
        }
        return "";
    }

    private function prepareUrl($params = []): string
    {
        return "?".http_build_query(array_merge($_GET, $params));
    }

    private function renderHead() : string {
        $header = "";
        foreach ($this->columns as $column) {
            if (empty($column->getField())) {
                $header .= "<th>{$column->getTitle()}</th>";
            }
            else {
                $hrefParams = [
                    'order' => $column->getField(),
                    'page' => 0
                ];

                if ($this->orderBy == $column->getField() && $this->direction == "") {
                    $hrefParams['direction'] = "DESC";
                } else {
                    $hrefParams['direction'] = "";
                }

                $header .= "<th><a href=\"{$this->prepareUrl($hrefParams)}\">{$column->getTitle()}</a></th>";
            }
        }
        return "<tr>{$header}</tr>";
    }

    private function renderBody() : string
    {
        $body = "";
        $rows = $this->dataSource->getAll($this->filterColumn($this->orderBy), $this->direction, $this->page, $this->pageSize, $this->filter);

        foreach ($rows as $row) {
            $tr = "";
            foreach ($this->columns as $column) {
                $tr .= "<td>{$column->render($row)}</td>";
            }
            $body .= "<tr>$tr</tr>";
        }
        return $body;
    }

    private function renderPaginator() : string {

        $r = "";
        for ($i = 0; $i < $this->totalPages; $i++){
            $href = $this->prepareUrl(['page' => $i]);
            $active = $this->page == $i ? "active" : "";
            $r .= "<a href=\"{$href}\" class=\"{$active}\">{$i}</a>";
        }

        return "<div>$r</div>";
    }

    private function renderFilter() : string{
        return '<form><input name="filter" type="text" value="'.$this->filter.'"><button type="submit">Filtrovať</button></form>';
    }
}