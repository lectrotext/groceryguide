<?php
namespace GroceryGuide\Controllers;

use Doctrine\DBAL\Connection;

class Search
{
    private $db;
    
    public function __construct(Connection $db, $table, array $args)
    {
        $this->db = $db;
        $this->table = (string) $table;
        $this->args = $this->setCriteria($args);
    }

    public function getData()
    {
        $result = $this->db->query("SELECT * FROM ".  $this->table ." WHERE state like '". $this->args['state'] . "' LIMIT 0, 100");
        return $result->fetchAll();
    }   

    public function getPaginatedData($page, $view)
    {   
        $page = $page * $view;
        $result = $this->db->query("SELECT * FROM $this->table LIMIT $page, $view");
        return $result->fetchAll();
    }  

    private function setCriteria(array $args)
    {
        $return = array();
        foreach ($args as $arg) {
            $params = explode(':',$arg);
            $return[$params[0]] = $params[1]; 
        }

        return $return;
    }
}
