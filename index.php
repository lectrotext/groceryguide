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
        $hal = new Hal($app->request->getURL() . $app->request->getPath());
        $hal->addLink('rfp:produce', $app->request->getURL() . $app->request->getPath() . '/produce');
        $hal->addLink('rfp:stores', $app->request->getURL() . $app->request->getPath() . '/stores');	
        $hal->addLink('rfp:items', $app->request->getURL() . $app->request->getPath() . '/items');	
	    echo $hal->asJson();
    });

    $app->get('/produce(/:id)', function ($id = null) use ($app) {
        if ($app->request->isGet() && $id != null) {
            $result = $app->dep{'db'}->query("SELECT * FROM produce WHERE id = $id");
            while ($row = $result->fetch()) {
                var_dump($row);
            }
        } elseif ($app->request->isGet() && $id == null) {
            $result = $app->dep{'db'}->query("SELECT * FROM produce");
            while ($row = $result->fetch()) {
                var_dump($row);
            }
        }
    });

    $app->get('/stores(/:id)', function($id = null) use ($app) {
        if ($app->request->isGet() && $id != null) {
            $result = $app->dep{'db'}->query("SELECT * FROM stores WHERE id = $id");
            while ($row = $result->fetch()) {
                var_dump($row);
            }
        } elseif ($app->request->isGet() && $id == null) {
            $result = $app->dep{'db'}->query("SELECT * FROM stores");
            while ($row = $result->fetch()) {
                var_dump($row);
            }
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

