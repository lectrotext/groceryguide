<?php
namespace GroceryGuide;

class QueryStore
{

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
