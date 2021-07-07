<?php

class UserStorage implements ITableSource
{
    public function getCount($filter = "") : int
    {
        return Db::conn()->query("SELECT count(*) FROM users" . $this->getFilter($filter))->fetchColumn();
    }

    /**
     * @return User[]
     */
    public function getAll($sortedBy = "", $sortDirection = "", $page = 0, $pageSize = 10, $filter = "") : array
    {
        $sql = "SELECT * FROM users";

        $sql .= $this->getFilter($filter);

        if ($sortedBy) {
            $direc = $sortDirection == "DESC" ? "DESC" : "ASC";
            $sql = $sql . " ORDER BY {$sortedBy} {$direc}" ;
        }

        $page *= $pageSize;
        $sql .= " LIMIT {$pageSize} OFFSET {$page}";

        try {
            return Db::conn()
                ->query($sql)
                ->fetchAll(PDO::FETCH_CLASS, User::class);
        }  catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    private function getFilter($filter = ""){

        if ($filter){
            $filter = str_replace("*","%", $filter);
            $searchableColumns = ["name", "surname", "mail"];
            $search = [];
            foreach ($searchableColumns as $columnName) {
                $search[] = " {$columnName} LIKE '%{$filter}%' ";
            }
            return " WHERE ". implode(" OR ", $search). " ";
        }
        return "";
    }
}