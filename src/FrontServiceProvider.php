<?php

namespace WeblaborMx\Front;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use WeblaborMx\Front\Http\Controllers\PageController;
use Opis\Closure\SerializableClosure;
use WeblaborMx\Front\Console\Commands\CreateResource;
use WeblaborMx\Front\Console\Commands\CreatePage;
use WeblaborMx\Front\Console\Commands\Install;
use WeblaborMx\Front\Console\Commands\CreateFilter;
use WeblaborMx\Front\Http\Controllers\FrontController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;

class FrontServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/../config/front.php' => config_path('front.php')], 'config');
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/front'),
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/front.php', 'front');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'front');
        $this->registerRoutes();
        SerializableClosure::addSecurityProvider(new SecurityProvider);
        $this->loadInputs();
        
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::macro('front', function ($model, $plural = null) {
            $model = config('front.resources_folder').'\\'.$model;
            $front = new $model;
            $prefix = class_basename($front->base_url);

            Route::group(['prefix' => $prefix, 'namespace' => '\WeblaborMx\Front\Http\Controllers'], function () use ($front) 
            {
                $controller = new FrontController($front);

                Route::get('/', function(Request $request) use ($controller) {
                    return $controller->index();
                });
                Route::get('create', function() use ($controller) {
                    return $controller->create();
                });
                Route::post('/', function(Request $request) use ($controller) {
                    return $controller->store($request);
                });
                Route::get('search', function(Request $request) use ($controller) {
                    return $controller->search($request);
                });
                Route::get('action/{front_action}', function($front_action) use ($controller) {
                    return $controller->indexActionShow($front_action);
                });
                Route::post('action/{front_action}', function($front_action, Request $request) use ($controller) {
                    return $controller->indexActionStore($front_action, $request);
                });
                Route::get('lenses/{front_lense}', function($front_lense) use ($controller) {
                    return $controller->lenses($front_lense);
                });
                Route::get('{front_object}', function($front_object) use ($controller) {
                    return $controller->show($front_object);
                });
                Route::get('{front_object}/edit', function($front_object) use ($controller) {
                    return $controller->edit($front_object);
                });
                Route::put('{front_object}', function($front_object, Request $request) use ($controller) {
                    return $controller->update($front_object, $request);
                });
                Route::delete('{front_object}', function($front_object) use ($controller) {
                    return $controller->destroy($front_object);
                });
                Route::get('{front_object}/action/{front_action}', function($front_object, $front_action) use ($controller) {
                    return $controller->actionShow($front_object, $front_action);
                });
                Route::post('{front_object}/action/{front_action}', function($front_object, $front_action, Request $request) use ($controller) {
                    return $controller->actionStore($front_object, $front_action, $request);
                });
                Route::get('{front_object}/masive_edit/{front_key}', function($front_object, $front_key) use ($controller) {
                    return $controller->massiveEditShow($front_object, $front_key);
                });
                Route::post('{front_object}/masive_edit/{front_key}', function($front_object, $front_key, Request $request) use ($controller) {
                    return $controller->massiveEditStore($front_object, $front_key, $request);
                });
            });
        });

        Route::macro('page', function ($model, $route = null) {
            $singular = strtolower(Str::snake($model));
            $route = $route ?? $singular;
            Route::get($route, function() use ($model) {
                return (new PageController)->page($model);
            });
            Route::post($route, function() use ($model) {
                return (new PageController)->page($model, 'post');
            });
            Route::put($route, function() use ($model) {
                return (new PageController)->page($model, 'put');
            });
            Route::delete($route, function() use ($model) {
                return (new PageController)->page($model, 'delete');
            });
        });

        Route::post('laravel-front/upload-image', '\WeblaborMx\Front\Http\Controllers\ToolsController@uploadImage');

        Blade::directive('active', function ($route) {
            return "<?php if(request()->is('$route/*') || request()->is('$route')) echo 'active';?>";
        });

        Blade::directive('active_exact', function ($route) {
            return "<?php if(request()->is('$route')) echo 'active';?>";
        });

        $this->app->make('form')->considerRequest(true);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if (! defined('WLFRONT_PATH')) {
            define('WLFRONT_PATH', realpath(__DIR__.'/../'));
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateResource::class,
                CreatePage::class,
                Install::class,
                CreateFilter::class
            ]);
        }
    }

    /**
     * Register the laravel collective created inputs
     *
     * @return void
     */
    public function loadInputs()
    {
        \Form::macro('frontDatetime', function($name, $value = null, $options = [])
        {
            $value = \Form::getValueAttribute($name, $value);
            if(!is_null($value) && !$value instanceof DateTime) {
                $value = Carbon::parse($value);
            }
            return \Form::datetimeLocal($name, $value, $options);;
        });
    }
}
