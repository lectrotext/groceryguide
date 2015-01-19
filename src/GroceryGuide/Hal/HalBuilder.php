<?php

use Nocarrier\Hal;

class HalBuilder
{
    private $resource;

    private $links;

    private $embed;

    public static function __construct (array $resource, array $links, array $embed = [])
    {
        $this->resource = $resource;
        $this->links = $links;
        $this->embed = $embed;
    }
}
