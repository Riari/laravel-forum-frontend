<?php

namespace Riari\Forum\Frontend;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Riari\Forum\Frontend\Events\UserViewingThread;
use Riari\Forum\Frontend\Forum;
use Riari\Forum\Frontend\Listeners\MarkThreadAsRead;
use Riari\Forum\Models\Post;
use Riari\Forum\Models\Thread;
use Riari\Forum\Models\Observers\PostObserver;
use Riari\Forum\Models\Observers\ThreadObserver;

class ForumFrontendServiceProvider extends ServiceProvider
{
    /**
     * The namespace for the package controllers.
     *
     * @var string
     */
    protected $namespace;

    /**
     * The base directory for the package.
     *
     * @var string
     */
    protected $baseDir;

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
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFacades();
    }

    /**
     * Bootstrap the application events.
     *
     * @param  Router  $router
     * @param  DispatcherContract  $events
     * @return void
     */
    public function boot(Router $router, DispatcherContract $events)
    {
        $this->baseDir = __DIR__.'/../';

        $this->setPublishables();
        $this->loadStaticFiles();

        $this->namespace = config('forum.frontend.controllers.namespace');

        $this->registerListeners($events);

        if (config('forum.routing.enabled')) {
            $this->loadRoutes($router);
        }
    }

    /**
     * Define files published by this package.
     *
     * @return void
     */
    protected function setPublishables()
    {
        $this->publishes([
            "{$this->baseDir}config/frontend.php" => config_path('forum.frontend.php')
        ], 'config');

        $this->publishes([
            "{$this->baseDir}views/" => base_path('resources/views/vendor/forum')
        ], 'views');
    }

    /**
     * Load config and views.
     *
     * @return void
     */
    protected function loadStaticFiles()
    {
        $this->mergeConfigFrom("{$this->baseDir}config/frontend.php", "forum.frontend");
        $this->loadViewsFrom("{$this->baseDir}views", 'forum');
    }

    /**
     * Register the package listeners.
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
     * Register the package facades.
     *
     * @return void
     */
    public function registerFacades()
    {
        // Bind the forum facade
        $this->app->bind('forum', function()
        {
            return new Forum;
        });

        // Create facade alias
        $loader = AliasLoader::getInstance();
        $loader->alias('Forum', 'Riari\Forum\Frontend\Support\Facades\Forum');
    }

    /**
     * Load routes.
     *
     * @param  Router  $router
     * @return void
     */
    protected function loadRoutes(Router $router)
    {
        $dir = $this->baseDir;
        $router->group(['namespace' => $this->namespace, 'as' => 'forum.', 'prefix' => config('forum.routing.root')], function ($r) use ($dir)
        {
            $controllers = config('forum.frontend.controllers');
            require "{$dir}routes.php";
        });
    }
}
