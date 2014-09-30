<?php 
phpinfo();

require 'vendor/autoload.php';
date_default_timezone_set('America/Detroit');

$options = array(
    'driver'   => 'mysql',
    'host'     => '127.0.0.1',
    'username' => 'apiuser',
    'password' => 'oggapiuser',
    'database' => 'ogg',
);

$connection = new DibiConnection($options);
$result = $connection->query('Select * from types');

foreach ($result as $n => $row) {
    print_r($row);
}

/*$app = new \Slim\Slim();

$app->group('/api', function() use ($app) {

    $app->get('/produce/:type', function ($type = null) {
    // all produce
    });

    $app->get('/stores', function() {
    //all stores
    });

    $app->get(''

    });

});
$app->run();
*/
