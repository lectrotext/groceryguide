<?php 

require 'vendor/autoload.php';
date_default_timezone_set('America/Detroit');

use Nocarrier\Hal;
use Pimple\Container;
use GroceryGuide\DependencyProvider;
use GroceryGuide\Utils\Bitmasks;
use GroceryGuide\QueryStore;

$app = new \Slim\Slim();
$app->dep =  new DependencyProvider(new Container());
$app->setName('Real Food Patrol API');

$app->get('/', function() use ($app) {
    echo "Home";
});

$app->group('/api', function() use ($app) {
	$app->get('/', function() use ($app) {
        $links = ["_links" => [
            'self'      => $app->request->getURL() . $app->request->getPath(),
            'rfp:items'   =>  ["href" => $app->request->getURL() . $app->request->getPath() . '/items'],
            'rfp:produce'   =>  ["href" => $app->request->getURL() . $app->request->getPath() . '/produce'],
            'rfp:stores'   =>  ["href" => $app->request->getURL() . $app->request->getPath() . '/stores'],
            'rfp:markets'   =>  ["href" => $app->request->getURL() . $app->request->getPath() . '/markets'],
            'rfp:csa_farms'   =>  ["href" => $app->request->getURL() . $app->request->getPath() . '/csa_farms']
        ]];

        $hal = Hal::fromJson(json_encode($links));
	    echo $hal->asJson();
    });

    $app->get('/produce(/:id)', function ($id = null) use ($app) {
        $app->dep->addDB();
        $resource = [];
        $links = ["_links" => [
            'self'      =>  ["href" => $app->request->getURL() . $app->request->getPath()],
            'index'     =>  ["href" => dirname($app->request->getURL() . $app->request->getPath())],
            'search'    =>  ["href" =>  $app->request->getURL() . $app->request->getPath() . "/{id}", "templated" => true]
        ]];
        
        if ($app->request->isGet() && $id != null) {
            $result = $app->dep->di{'db'}->query("SELECT * FROM produce WHERE plu = $id");

            while ($row = $result->fetch()) {
                $resource = $row;
            }

        } elseif ($app->request->isGet() && $id == null) {
            $result = $app->dep->di{'db'}->query("SELECT * FROM produce LIMIT 100");

            while ($row = $result->fetch()) {
                $resource['produce'][] = $row;
            }
        }
        
        $resource = array_merge($resource, $links);

        $hal = Hal::fromJson(json_encode($resource));
	    echo $hal->asJson();
    });

    $app->get('/stores(/:id)', function($id = null) use ($app) {
        $app->dep->addDB();
        $resource = [];
        $links = ["_links" => [
            'self'      =>  ["href" => $app->request->getURL() . $app->request->getPath()],
            'index'     =>  ["href" => dirname($app->request->getURL() . $app->request->getPath())],
            'search'    =>  ["href" =>  $app->request->getURL() . $app->request->getPath() . "/{id}", "templated" => true]
        ]];

        if ($app->request->isGet() && $id != null) {
            $result = $app->dep->di{'db'}->query("SELECT * FROM stores WHERE id = $id");

            $row = $result->fetch();

            if (!empty($row)) {
              $resource = $row;
            }
        } elseif ($app->request->isGet() && $id == null) {
            $result = $app->dep->di{'db'}->query("SELECT * FROM stores");

            while ($row = $result->fetch()) {
                $resource['stores'][] = $row;
            }
        }
            
        $resource = array_merge($resource, $links);

        $hal = Hal::fromJson(json_encode($resource));
	    echo $hal->asJson();
    });

    $app->get('/markets(/:id)', function($id = null) use ($app) {
        $app->dep->addDB();
        $resource = [];
        $links = ["_links" => [
            'self'      =>  ["href" => $app->request->getURL() . $app->request->getPath()],
            'index'     =>  ["href" => dirname($app->request->getURL() . $app->request->getPath())],
            'search'    =>  ["href" =>  $app->request->getURL() . $app->request->getPath() . "/{id}", "templated" => true]
        ]];

        if ($app->request->isGet() && $id != null) {
            $result = $app->dep->di{'db'}->query("SELECT * FROM markets WHERE id = $id");

            $row = $result->fetch();

            if (!empty($row)) {
              $resource = $row;
            }
            
        } elseif ($app->request->isGet() && $id == null) {
            $result = $app->dep->di{'db'}->query("SELECT * FROM markets LIMIT 100");

            while ($row = $result->fetch()) {
                $resource['markets'][] = $row;
            }
        }
        $resource = array_merge($resource, $links);

        $hal = Hal::fromJson(json_encode($resource));
        echo $hal->asJson();
    });

    $app->get('/csa_farms(/:id)', function($id = null) use ($app) {
        $app->dep->addDB();
        $resource = [];
        $links = ["_links" => [
            'self'      =>  ["href" => $app->request->getURL() . $app->request->getPath()],
            'index'     =>  ["href" => dirname($app->request->getURL() . $app->request->getPath())],
            'search'    =>  ["href" =>  $app->request->getURL() . $app->request->getPath() . "/{id}", "templated" => true]
        ]];

        if ($app->request->isGet() && $id != null) {
            $result = $app->dep->di{'db'}->query("SELECT * FROM csa WHERE id = $id");

            $row = $result->fetch();

            if (!empty($row)) {
                $resource = $row;

                if (!empty($resource['months'])) {
                    $resource['months'] = Bitmasks::months($resource['months']);
                }
 
                if (!empty($resource['delivery'])) {
                    $deliveries = QueryStore::csaDelivery($app->dep->di{'db'}, (string) $resource['delivery']); 
                    $resource['delivery'] = array();
                    foreach ($deliveries as $delivery) {
                        $resource['delivery'][] = $delivery['location'];
                    }
                }
            }
            
        } elseif ($app->request->isGet() && $id == null) {
            $result = $app->dep->di{'db'}->query("SELECT * FROM csa LIMIT 100");

            while ($row = $result->fetch()) {
                $resource['csa_farms'][] = $row;
            }
            foreach ($resource['csa_farms'] as &$r) {
                if (!empty($r['months'])) {
                    $r['months'] = Bitmasks::months($r['months']);
                }
 
                if (!empty($r['delivery'])) {
                    $deliveries = QueryStore::csaDelivery($app->dep->di{'db'}, (string) $r['delivery']); 
                    $r['delivery'] = array();
                    foreach ($deliveries as $delivery) {
                        $r['delivery'][] = $delivery['location'];
                    }
                }
            }
        }
        $resource = array_merge($resource, $links);

        $hal = Hal::fromJson(json_encode($resource));
        echo $hal->asJson();
    });

    $app->map('/items(/:id)', function($id = null) use ($app) {
        if ($app->request->isGet() && $id != null) {
            echo "I will fetch item # $id.";
        } elseif ($app->request->isGet() && $id == null) {
            echo "I am going to fetch all items.";
        } elseif ($app->request->isPost()) {
            echo "Let me try to make a new item with your data.";
        }
    })->via('GET', 'POST');
});
$app->run();

