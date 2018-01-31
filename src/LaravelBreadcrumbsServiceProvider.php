<?php
/**
 * Copyright 2017 Sinkevich Alexey
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Seka19\LaravelBreadcrumbs;

use Illuminate\Support\ServiceProvider;

/**
 * Class LaravelBreadcrumbsServiceProvider
 * @package Seka19\LaravelBreadcrumbs
 * @author Alexey Sinkevich
 */
class LaravelBreadcrumbsServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function boot(): void
    {
        $this->publishes(
            [__DIR__ . '/../config/breadcrumbs.php' => config_path('breadcrumbs.php')],
            'config'
        );
        $this->publishes(
            [__DIR__ . '/../views' => $this->app->resourcePath('views/vendor/breadcrumbs')],
            'views'
        );

        $this->loadViewsFrom(__DIR__ . '/../views', 'breadcrumbs');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\BreadcrumbsClear::class
            ]);
        }
    }

    /**
     *
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/breadcrumbs.php', 'breadcrumbs');
    }
}