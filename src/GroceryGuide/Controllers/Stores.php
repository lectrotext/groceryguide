<?php
namespace GroceryGuide\Controllers;

use Doctrine\DBAL\Connection;

class Stores
{
    private $id;

    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function getStores($id = null) 
    {
        if (!empty($id) && is_numeric($id)) {
            $result = $this->db->query("SELECT * FROM stores WHERE id = $id");

            $row = $result->fetch();

            if (!empty($row)) {
                return $row;
            } else {
                return false;
            }
        } elseif ($id == null) {
            $result = $this->db->query("SELECT * FROM stores LIMIT 0, 100");
            return $result->fetchAll();
        }
    }

    public function getPaginatedStores($page, $view)
    {
        $page = $page * $view;
        $result = $this->db->query("SELECT * FROM stores LIMIT $page, $view");
        return $result->fetchAll();
    }

}
