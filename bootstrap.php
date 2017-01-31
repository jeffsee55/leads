<?php

require_once( __DIR__ . '/vendor/autoload.php' );

function getPlugin()
{
    return Heidi\Plugin\Plugin::getInstance();
}

function dump()
{
	array_map(function($x) { var_dump($x); }, func_get_args());
}

function ddd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

function displayDate($dateString, $withYear = false)
{
    if($withYear)
        return date( "D, M jS,  Y", strtotime($dateString) );

    return date( "D, F jS", strtotime($dateString) );
}

function displayPrice($priceString, $withoutDecimal = false)
{
    if($withoutDecimal)
        return number_format($priceString, 0);

    return number_format($priceString, 2);
}

function capture($function, $args)
{
    ob_start();
    call_user_func_array($function, $args);
    return ob_get_clean();
}

use Windwalker\Renderer\BladeRenderer;

function view($name, $data = [])
{
    $paths = [HEIDI_RESOURCE_PATH . 'views/'];

    $renderer = new BladeRenderer($paths, array('cache_path' => __DIR__ . '/cache'));

    echo $renderer->render($name, $data);
}
