<?php 

require 'vendor/autoload.php';
date_default_timezone_set('America/Detroit');

use Nocarrier\Hal;
use Pimple\Container;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

$dep = new Container();

$dep['db'] = function ($c) {
    $config = new Configuration();
    $options = array(
        'driver'   => 'pdo_mysql',
        'host'     => '127.0.0.1',
        'user' => 'apiuser',
        'password' => 'oggapiuser',
        'dbname' => 'ogg',
    );
    return DriverManager::getConnection($options, $config);
}; 


$app = new \Slim\Slim();

$app->dep = $dep;

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
            'rfp:markets'   =>  ["href" => $app->request->getURL() . $app->request->getPath() . '/markets']
        ]];

//        $hal = new Hal($app->request->getURL() . $app->request->getPath());
//        $hal->addLink('rfp:produce', $app->request->getURL() . $app->request->getPath() . '/produce');
//        $hal->addLink('rfp:stores', ["href => "$app->request->getURL() . $app->request->getPath() . '/stores', "templated"=> true];	
 //       $hal->addLink('rfp:items', $app->request->getURL() . $app->request->getPath() . '/items');	

        $hal = Hal::fromJson(json_encode($links));
	    echo $hal->asJson();
    });

    $app->get('/produce(/:id)', function ($id = null) use ($app) {
        if ($app->request->isGet() && $id != null) {
            $result = $app->dep{'db'}->query("SELECT * FROM produce WHERE id = $id");
            while ($row = $result->fetch()) {
                var_dump($row);
            }
        } elseif ($app->request->isGet() && $id == null) {
            $result = $app->dep{'db'}->query("SELECT * FROM produce LIMIT 100");

            while ($row = $result->fetch()) {
                $collection['produce'][] = $row;
            }

            $links = ["_links" => [
                'self'      =>  ["href" => $app->request->getURL() . $app->request->getPath()],
                'index'     =>  ["href" => dirname($app->request->getURL() . $app->request->getPath())],
                'search'    =>  ["href" =>  $app->request->getURL() . $app->request->getPath() . "/{id}", "templated" => true]
            ]];

            $resource = array_merge($collection, $links);

            $hal = Hal::fromJson(json_encode($resource));
	        echo $hal->asJson();
        }
    });

    $app->get('/stores(/:id)', function($id = null) use ($app) {
        if ($app->request->isGet() && $id != null) {
            $result = $app->dep{'db'}->query("SELECT * FROM stores WHERE id = $id");

            $row = $result->fetch();

            $resource = '';
            if (!empty($row)) {
              $resource = $row;
            }

            $links = ["_links" => [
                'self'      => ["href" => $app->request->getURL() . $app->request->getPath()],
                'index'   =>  ["href" => dirname($app->request->getURL() . $app->request->getPath())],
                'search'    =>  ["href" =>  $app->request->getURL() . $app->request->getPath() . "/{id}", "templated" => true]
            ]];
            
            $resource = array_merge($resource, $links);

            $hal = Hal::fromJson(json_encode($resource));
	        echo $hal->asJson();
        
            
        } elseif ($app->request->isGet() && $id == null) {
            $result = $app->dep{'db'}->query("SELECT * FROM stores");

            $collection = null;
            while ($row = $result->fetch()) {
                $collection['stores'][] = $row;
            }

            $links = ["_links" => [
                'self'      =>  ["href" => $app->request->getURL() . $app->request->getPath()],
                'index'     =>  ["href" => dirname($app->request->getURL() . $app->request->getPath())],
                'search'    =>  ["href" =>  $app->request->getURL() . $app->request->getPath() . "/{id}", "templated" => true]
            ]];
            
            $resource = array_merge($collection, $links);

            $hal = Hal::fromJson(json_encode($resource));
	        echo $hal->asJson();
        }
    });

    $app->get('/markets(/:id)', function($id = null) use ($app) {
        if ($app->request->isGet() && $id != null) {
            $result = $app->dep{'db'}->query("SELECT * FROM markets WHERE id = $id");

            $row = $result->fetch();

            $resource = '';
            if (!empty($row)) {
              $resource = $row;
            }

            $links = ["_links" => [
                'self'      => ["href" => $app->request->getURL() . $app->request->getPath()],
                'index'   =>  ["href" => dirname($app->request->getURL() . $app->request->getPath())]
            ]];
            
            $resource = array_merge($resource, $links);

            $hal = Hal::fromJson(json_encode($resource));
	        echo $hal->asJson();
        
            
        } elseif ($app->request->isGet() && $id == null) {
            $result = $app->dep{'db'}->query("SELECT * FROM markets LIMIT 100");

            $collection = null;
            while ($row = $result->fetch()) {
                $collection['markets'][] = $row;
            }

            $links = ["_links" => [
                'self'      =>  ["href" => $app->request->getURL() . $app->request->getPath()],
                'index'     =>  ["href" => dirname($app->request->getURL() . $app->request->getPath())],
                'search'    =>  ["href" =>  $app->request->getURL() . $app->request->getPath() . "/{id}", "templated" => true]
            ]];
            
            $resource = array_merge($collection, $links);

            $hal = Hal::fromJson(json_encode($resource));
	        echo $hal->asJson();
        }
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

