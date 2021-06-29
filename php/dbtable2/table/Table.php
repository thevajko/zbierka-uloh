<?php

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
    private array $columns;

    public function __construct(ITableSource $dataSource, array $columns)
    {
        $this->dataSource = $dataSource;
        $this->columns = $columns;
        $this->orderBy = ($this->isColumnNameValid(@$_GET['order']) ? $_GET['order'] : "");
        $this->direction = $_GET['direction'] ?? "";
        $this->filter =  str_replace( "'", "",$_GET['filter'] ?? "");

        $this->page = $this->getPageNumber();

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

    private function isColumnNameValid($name) : bool {
        return in_array($name, $this->columns);
    }

    private function prepareUrl($params = []): string
    {
        return "?".http_build_query(array_merge($_GET, $params));
    }

    private function renderHead() : string {
        $header = "";
        foreach ($this->columns as $columnName => $value) {

            if ($value instanceof Closure) {
                $header .= "<th>{$columnName}</th>";
            }
            else {

                $hrefParams = [
                    'order' => $value,
                    'page' => 0
                ];

                if ($this->orderBy == $value && $this->direction == "") {
                    $hrefParams['direction'] = "DESC";
                } else {
                    $hrefParams['direction'] = "";
                }

                $header .= "<th><a href=\"{$this->prepareUrl($hrefParams)}\">{$columnName}</a></th>";
            }
        }
        return "<tr>{$header}</tr>";
    }

    private function renderBody() : string
    {
        $body = "";
        $rows = $this->dataSource->getAll($this->orderBy, $this->direction, $this->page, $this->pageSize, $this->filter);

        foreach ($rows as $row) {
            $tr = "";
            foreach ($this->columns as $columnName => $value) {
                if ($value instanceof Closure) {
                    $tr .= "<td>{$value($row)}</td>";
                }
                else {
                    $tr .= "<td>{$row->$value}</td>";
                }
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