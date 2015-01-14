<?php 

require 'vendor/autoload.php';
date_default_timezone_set('America/Detroit');

use Nocarrier\Hal;
use Dibi\DibiConnection;
$options = array(
    'driver'   => 'mysql',
    'host'     => '127.0.0.1',
    'username' => 'apiuser',
    'password' => 'oggapiuser',
    'database' => 'ogg',
);

$connection = new DibiConnection($options);
$result = $connection->query('Select * from types');

//foreach ($result as $n => $row) {
//    var_dump($row);
//}

$app = new \Slim\Slim();
$app->setName('Real Food Patrol API');

$app->get('/', function() use ($app) {
    echo "Home";
});

$app->group('/api', function() use ($app) {
	$app->get('/', function() use ($app) {
        $hal = new Hal($app->request->getURL() . $app->request->getPath());
        $hal->addLink('rfp:produce', $app->request->getURL() . $app->request->getPath() . '/produce');
        $hal->addLink('rfp:stores', $app->request->getURL() . $app->request->getPath() . '/stores');	
        $hal->addLink('rfp:items', $app->request->getURL() . $app->request->getPath() . '/items');	
	    echo $hal->asJson();
    });

    $app->get('/produce(/:id)', function ($id = null) use ($app) {
        if ($app->request->isGet() && $id != null) {
            echo "I will fetch produce item # $id.";
        } elseif ($app->request->isGet() && $id == null) {
            echo "I am going to fetch all the produce.";
        }
    });

    $app->get('/stores(/:id)', function($id = null) use ($app) {
        if ($app->request->isGet() && $id != null) {
            echo "I will fetch store # $id.";
        } elseif ($app->request->isGet() && $id == null) {
            echo "I am going to fetch all the stores.";
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

