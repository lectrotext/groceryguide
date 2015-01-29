<?php
namespace GroceryGuide;

class QueryFactory
{
    private $table;

    public function __construct($table)
    {
        $this->table = (string) $table;
    }
    
    public function selectFromTable()
    {
        return "SELECT * FROM " . $this->table . " LIMIT 1, 100";
    }

    public function selectFromTableWhereLike ($column)
    {
        return "SELECT * FROM " . $this->table . " WHERE " . $column . " LIKE :value";
    }
}
