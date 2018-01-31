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

namespace Seka19\LaravelBreadcrumbs\Console\Commands;

use Illuminate\Console\Command;
use Seka19\LaravelBreadcrumbs\Breadcrumbs;

/**
 * Class BreadcrumbsClear
 * @package Seka19\LaravelBreadcrumbs\Console\Commands
 * @author Alexey Sinkevich
 */
class BreadcrumbsClear extends Command
{
    /**
     * @var string
     */
    protected $signature = 'breadcrumbs:clear';

    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;

    /**
     * BreadcrumbsClear constructor.
     * @param Breadcrumbs $breadcrumbs
     */
    public function __construct(Breadcrumbs $breadcrumbs)
    {
        parent::__construct();
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     *
     */
    public function handle(): void
    {
        if ($this->confirm('Are you sure you want to remove all the breadcrumbs?')) {
            $this->breadcrumbs->clear();
            $this->info('All breadcrumbs were removed');
        }
    }
}