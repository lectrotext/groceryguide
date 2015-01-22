<?php
namespace GroceryGuide\Services;

class Csa
{

    public static function csaById($conn, $id) {
        $stmt = $conn->executeQuery('Select * FROM csa WHERE id = (?)',
            array(array($id)),
            array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
        );
        return $stmt->fetch();
    }


    public static function csaDelivery($conn, $deliveries)
    {
        $deliveries = str_split($deliveries);
        $stmt = $conn->executeQuery('SELECT * FROM csa_delivery WHERE id IN (?)',
                array($deliveries),
                array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
        );
        return $stmt->fetchAll();
    }

}
