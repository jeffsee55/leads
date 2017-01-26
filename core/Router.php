<?php

namespace Heidi\Core;

/**
 * The Router class
 *
 * @since  0.1
 */
class Router
{
    /**
	 * Property blade.
	 *
	 * @var Array
	 */
    protected $routes = [];

    /**
	 * Load file with route list and provide instance
	 *
	 * @param string $file
	 *
	 * @return object Router
	 */
    public static function load($file)
    {
        $router = new static;

        require HEIDI_PLUGIN_PATH . $file;

        return $router;
    }

    public function group($namespace, Array $controllers)
    {
        foreach($controllers as $controller => $routes)
        {
            $this->register($namespace, $controller, $routes);
        }
    }

    /**
	 * Delegate each route to be registered as an action
	 *
	 * @param array $routes
	 *
	 * @return null
	 */
    public function register($namespace, $controller, Array $routes)
    {
        $controller = $this->namespaceController($namespace, $controller);

        $controller = new $controller;

        foreach($routes as $action => $route)
        {

            if(is_array($route)) {

                array_map(function($route) use ($controller, $action) {

                    $this->registerRoute($action, $controller, $route);

                }, $route);

                continue;

            }

            $this->registerRoute($action, $controller, $route);
        }
    }

    public function registerRoute($action, Controller $controller, $route)
    {
        add_action($action, [$controller, $route]);
    }

    /**
	 * Prefix the route with appropriate controller namespace
	 *
	 * @param string $route
	 *
	 * @return string
	 */
    protected function namespaceController($namespace, $controller)
    {
        return '\Heidi\Plugin\Controllers\\' . $namespace . '\\' . $controller;
    }
}
