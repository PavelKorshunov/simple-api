<?php

namespace App;

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
}