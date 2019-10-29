<?php

namespace App;

use App\Helpers\ControllerResolver;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\{RouteCollection, RequestContext};

class Kernel
{
    /**
     * @var string
     */
    protected $configFolder;

    /**
     * @var FileLocator
     */
    protected $fileLocator;

    /**
     * @var YamlFileLoader
     */
    protected $loader;

    /**
     * @var RequestContext
     */
    protected $context;

    /**
     * @var RouteCollection
     */
    protected $routes;

    /**
     * @var ControllerResolver
     */
    protected $controllerResolver;

    /**
     * @var UrlMatcher
     */
    protected $matcher;

    /**
     * Sets the application configuration
     *
     * @return void
     */
    public function __construct()
    {
        $this->configFolder = dirname(__DIR__) . "/config";
        $this->fileLocator = new FileLocator([$this->configFolder]);
        $this->loader = new YamlFileLoader($this->fileLocator);
    }

    /**
     * Start application
     *
     * @return $this
     */
    public function run(): Kernel
    {
        $this->routes = $this->loader->load('routes.yaml');
        $this->createRequest();

        return $this;
    }

    /**
     * Create request
     *
     * @return void
     */
    protected function createRequest()
    {
        $this->context = new RequestContext();
        $this->context->fromRequest(Request::createFromGlobals());
    }

    /**
     * Looking for a match to the route
     *
     * @param string $path
     * @return array
     */
    public function matchRoute(string $path): array
    {
        $matcher = new UrlMatcher($this->routes, $this->context);
        $parameters = $matcher->match($path);

        return $parameters;
    }


    /**
     * Создает объект контроллера и вызывает его метод если он есть
     *
     * @param string $controller
     * @return void
     */
    public function createController(string $controller)
    {
        $this->controllerResolver = new ControllerResolver();
        $callController = $this->controllerResolver->createController($controller);

        if(is_array($callController) && is_object($callController[0]) && isset($callController[1])) {
            try {
                $reflection = new \ReflectionClass($callController[0]);
                if($reflection->hasMethod($callController[1])) {
                    $method = new \ReflectionMethod($callController[0], $callController[1]);
                    $method->invoke($callController[0], $callController[1]);

                    // TODO Необходимо вызывать метод с параметрами, если они есть ReflectionMethod::invokeArgs
                } else {
                    throw new \BadMethodCallException("Method " . $callController[1] . " not allowed");
                }
            } catch (\ReflectionException $e) {
                echo $e->getMessage();
            }
        }
    }
}