<?php
namespace GroceryGuide;

use Pimple\Container;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class DependencyProvider
{
    /**
    * Dependency Injector Container
    * @var Pimple/Pimple
    */
    public $di;

    /**
    * @param Pimple/Pimple
    * @return void
    */
    public function __construct(Container $di)
    {
        $this->di = $di;
    }

    public function addDB()
    {
        $this->di['db'] = function ($c) {
        $config = new Configuration();
        $options = array(
            'driver'    => 'pdo_mysql',
            'host'      => '127.0.0.1',
            'user'      => 'apiuser',
            'password'  => 'oggapiuser',
            'dbname'    => 'ogg',
        );  
        return DriverManager::getConnection($options, $config);
        };
    }

}
