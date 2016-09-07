<?php

namespace Riari\Forum\Frontend;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Riari\Forum\Frontend\Events\UserViewingThread;
use Riari\Forum\Frontend\Listeners\MarkThreadAsRead;
use Riari\Forum\Frontend\Support\Forum;

class ForumFrontendServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserViewingThread::class => [
            MarkThreadAsRead::class,
        ],
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @param  Router  $router
     * @param  DispatcherContract  $events
     * @return void
     */
    public function boot(Router $router, DispatcherContract $events)
    {
        $this->setPublishables();
        $this->loadStaticFiles();
        $this->registerAliases();

        $this->registerListeners($events);

        if (config('forum.frontend.routes')) {
            $this->loadRoutes($router);
        }
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register() {}

    /**
     * Define files published by this package.
     *
     * @return void
     */
    protected function setPublishables()
    {
        $baseDir = $this->getBaseDir();

        $this->publishes([
            "{$baseDir}config/frontend.php" => config_path('forum.frontend.php')
        ], 'config');

        $this->publishes([
            "{$baseDir}views/" => base_path('resources/views/vendor/forum')
        ], 'views');
    }

    /**
     * Load config and views.
     *
     * @return void
     */
    protected function loadStaticFiles()
    {
        $baseDir = $this->getBaseDir();
        $this->mergeConfigFrom("{$baseDir}config/frontend.php", "forum.frontend");
        $this->loadViewsFrom("{$baseDir}views", 'forum');
    }

    /**
     * Register event listeners.
     *
     * @param  DispatcherContract  $events
     * @return void
     */
    public function registerListeners(DispatcherContract $events)
    {
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }

    /**
     * Register aliases.
     *
     * @return void
     */
    public function registerAliases()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Forum', config('forum.frontend.utility_class'));
    }

    /**
     * Load routes.
     *
     * @param  Router  $router
     * @return void
     */
    protected function loadRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->getFrontendNamespace(),
            'middleware' => config('forum.frontend.middleware'),
            'as' => config('forum.routing.as'),
            'prefix' => config('forum.routing.prefix')
        ], function ($router) {
            Forum::routes($router);
        });
    }

    /**
     * The namespace for the package controllers.
     *
     * @return string
     */
    protected function getFrontendNamespace()
    {
        return config('forum.frontend.controllers.namespace');
    }

    /**
     * The base directory for the package.
     *
     * @return string
     */
    protected function getBaseDir()
    {
        return __DIR__.'/../';
    }
}
