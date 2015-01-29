<?php
namespace GroceryGuide\Controllers;

use Doctrine\DBAL\Connection;
use GroceryGuide\QueryFactory;

class Search
{
    private $db;
    
    public function __construct(Connection $db, QueryFactory $qf, array $args = [])
    {
        $this->db = $db;
        $this->qf = $qf;
        $this->args = $args;
    }

    public function getData()
    {
        if (empty($this->args)) {
            $result = $this->db->query($this->qf->selectFromTable());
            $result = $result->fetchAll();
        } else {
            $stmt = $this->db->prepare($this->qf->selectFromTableWhereLike(key($this->args)));
            foreach ($this->args as $column => $value) {
                $stmt->bindValue("value", '%' . $value . '%');
            }
            $stmt->execute();
            $result = $stmt->fetchAll();

        }
        return $result;
    }   

    public function getPaginatedData($page, $view)
    {   
        $page = $page * $view;
        $result = $this->db->query("SELECT * FROM $this->table LIMIT $page, $view");
        return $result->fetchAll();
    }  


    /**
    *
    */
    public function serializeCriteria ()
    {
        $string = "";

    }
}
