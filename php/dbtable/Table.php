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

    public function __construct()
    {
        $this->orderBy = ($this->IsColumnNameValid(@$_GET['order']) ? $_GET['order'] : "");
        $this->direction = $_GET['direction'] ?? "";
        $this->filter =  str_replace( "'", "",$_GET['filter'] ?? "");

        $this->page = $this->GetPageNumber();

    }

    private function GetPageNumber(): int
    {
        $this->itemsCount = DB::i()->UsersCount($this->filter);
        $page =  intval($_GET['page'] ?? 0);
        $this->totalPages = ceil($this->itemsCount / $this->pageSize);
        if (($page < 0) || $page > $this->totalPages){
            return 0;
        }
        return $page;
    }

    private function IsColumnNameValid($name) : bool {
        return array_key_exists($name, $this->GetColumnAttributes());
    }

    public function Render() : string
    {
        return $this->RenderFilter()."<table border=\"1\">{$this->RenderHead()}{$this->RenderBody()}</table>". $this->RenderPaginator();
    }

    private ?array $columnAttribs = null;
    private function GetColumnAttributes() :  array
    {
        if ($this->columnAttribs == null) {
            $this->columnAttribs = get_object_vars(new User());
        }
        return $this->columnAttribs;
    }
    private function RenderHead() : string {
        $header = "";
        foreach ($this->GetColumnAttributes() as $attribName => $value) {

            $hrefParams = [
                'order' => $attribName,
                'page' => 0
            ];

            if ($this->orderBy == $attribName && $this->direction == ""){
                $hrefParams['direction'] = "DESC";
            } else {
                $hrefParams['direction'] = "";
            }

            $header .= "<th><a href=\"{$this->GetHREF($hrefParams)}\">{$attribName}</a></th>";
        }
        return "<tr>{$header}</tr>";
    }
    private function RenderBody() : string
    {
        $body = "";
        $users = DB::i()->getAllUsers($this->orderBy, $this->direction, $this->page, $this->pageSize, $this->filter);

        foreach ($users as $user) {
            $tr = "";
            foreach ($this->GetColumnAttributes() as $attribName => $value) {
                $tr .= "<td>{$user->$attribName}</td>";
            }
            $body .= "<tr>$tr</tr>";
        }
        return $body;
    }

    private function GetHREF($params = []): string
    {
        $a = $_GET;
        if ($params){
            foreach ($params as $paramName => $paramValue){
                $a[$paramName] = $paramValue;
            }
        }
        return "?".http_build_query($a);
    }

    private function RenderPaginator() : string {

        $r = "";
        for ($i = 0; $i < $this->totalPages; $i++){
            $href = $this->GetHREF(['page' => $i]);
            $active = $this->page == $i ? "active" : "";
            $r .= "<a href=\"{$href}\" class=\"{$active}\">{$i}</a>";
        }

        return "<div>$r</div>";
    }

    private function RenderFilter() : string{
        return '<form><input name="filter" type="text" value="'.$this->filter.'"><button type="submit">Filtrovať</button></form>';
    }
}