<?php

class Table
{

    private string $orderBy = "";

    public function __construct()
    {
        $this->orderBy = ($_GET['order'] ?? "");
    }

    public function Render() : string
    {
        return "<table border=\"1\">{$this->RenderHead()}{$this->RenderBody()}</table>";
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
            $header .= "<th><a href=\"?order={$attribName}\">{$attribName}</a></th>";
        }
        return "<tr>{$header}</tr>";
    }

    private function RenderBody() : string
    {
        $body = "";
        $users = DB::i()->getAllUsers($this->orderBy);

        foreach ($users as $user) {
            $tr = "";
            foreach ($this->GetColumnAttributes() as $attribName => $value) {
                $tr .= "<td>{$user->$attribName}</td>";
            }
            $body .= "<tr>$tr</tr>";
        }
        return $body;
    }
}