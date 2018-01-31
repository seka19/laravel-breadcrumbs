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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;

/**
 * Class Breadcrumbs
 * @package Seka19\LaravelBreadcrumbs
 * @author Alexey Sinkevich
 *
 * @property int $id
 * @property int $parent_id
 * @property string $slug
 * @property string $value
 * @property string $url
 */
class Breadcrumbs extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @var string
     */
    private $url = '';

    /**
     * Breadcrumbs constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        app();
        $this->request = app(Request::class);
        $this->viewFactory = app(ViewFactory::class);
        $this->urlGenerator = app(UrlGenerator::class);
    }

    /**
     * @param string $key
     * @return mixed|string
     */
    public function __get($key)
    {
        if ($key === 'url') {
            return $this->buildUrl();
        }
        return parent::__get($key);
    }

    /**
     * @param string $value
     * @param string $view
     * @return HtmlString
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function render(string $value = '', string $view = ''): HtmlString
    {
        $slugs = $this->request->segments();
        if (empty($slugs)) {
            return new HtmlString('');
        }
        $this->clearExpired();

        // Get breadcrumbs for current url
        $breadcrumbs = $this->getBySlugs($slugs, $value)->filter(function ($bc) {
            return $bc->value !== '';
        });

        // Prepend breadcrumbs list with items from config
        foreach (array_reverse(config('breadcrumbs.prepend')) as $prepend) {
            $bc = new self(['value' => $prepend['value']]);
            if (isset($prepend['url'])) {
                $bc->url = $this->urlGenerator->to($prepend['url']);
            } elseif (isset($prepend['route'])) {
                $bc->url = $this->urlGenerator->route($prepend['route']);
            }
            $breadcrumbs->prepend($bc);
        }

        // Render template
        return new HtmlString(
            $this->viewFactory->make(
                $view ?: config('breadcrumbs.default-view'),
                ['breadcrumbs' => $breadcrumbs]
            )->render()
        );
    }

    /**
     *
     */
    public function clear(): void
    {
        $this->truncate();
    }

    /**
     * @param array $slugs
     * @param string $value
     * @return Collection
     * @throws \RuntimeException
     */
    private function getBySlugs(array $slugs, string $value = ''): Collection
    {
        if (!count($slugs)) {
            return collect();
        }
        $slugsOrig = $slugs;
        $slug = array_pop($slugs);
        if (empty($slugs)) {
            $breadcrumbs = collect();
            $parentId = 0;
        } else {
            $breadcrumbs = $this->getBySlugs($slugs);
            if ($breadcrumbs->isEmpty()) {
                throw new \RuntimeException('Can\'t save breadcrumbs to DB');
            }
            $parentId = $breadcrumbs->last()->id;
        }
        $bc = $this->firstOrCreate(
            ['parent_id' => $parentId, 'slug' => $slug],
            ['value' => $value]
        );
        if ($value && $bc->value !== $value) {
            $bc->value = $value;
            $bc->save();
        }
        $bc->url = $this->urlGenerator->to(implode('/', $slugsOrig));
        $breadcrumbs->push($bc);
        return $breadcrumbs;
    }

    /**
     * @return string
     */
    private function buildUrl(): string
    {
        if ($this->url) {
            return $this->url;
        }
        if (!$this->id) {
            return '';
        }
        $this->url = $this->slug;
        if ($this->parent_id && $parent = $this->where('id', '=', $this->parent_id)->first()) {
            $this->url = rtrim($parent->url, '/') . '/' . $this->slug;
        }
        return $this->url;
    }

    /**
     *
     */
    private function clearExpired(): void
    {
        $time = Carbon::now()->subSeconds(config('breadcrumbs.lifetime'));
        $this->where('updated_at', '<', $time)->delete();
    }
}