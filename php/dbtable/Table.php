<?php


class Table
{

    public function Render() : string
    {
        return "<table border=\"1\">{$this->RenderHead()}{$this->RenderBody()}</table>";
    }

    private function RenderHead() : string {

        $attribs = get_object_vars(new User());

        $header = "";

        foreach ($attribs as $attribName => $value) {
            $header .= "<th>{$attribName}</th>";
        }

        return "<tr>{$header}</tr>";
    }

    private function RenderBody()
    {

        $attribs = get_object_vars(new User());

        $body = "";

        $users = DB::i()->getAllUsers();

        foreach ($users as $user) {
            $tr = "";
            foreach ($attribs as $attribName => $value) {
                $tr .= "<td>{$user->$attribName}</td>";
            }
            $body .= "<tr>$tr</tr>";
        }

        return $body;
    }
}