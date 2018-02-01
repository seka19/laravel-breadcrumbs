# Laravel package for displaying breadcrumbs in views

Breadcrumbs are saving in the DB for each page by its URI parts.

Example:

    # In the View for the page with URL `http://site.com/catalog`
    {{ breadcrumbs('Catalog') }}

    # Will render only one breadcrumb:
    Catalog
---
    #In the page with URL `http://site.com/catalog/cars`
    {{ breadcrumbs('Cars') }}
            
    # Will render:    
    Catalog / Cars
---
    # In the page with URL `http://site.com/catalog/cars/ford`
    {{ breadcrumbs('Ford') }}
            
    # Will render:    
    Catalog / Cars / Ford

## Installation

    composer require seka19/laravel-breadcrumbs
    
## Customize View

By default breadcrumbs are rendering with Bootstrap-4 view.

You can publish it and customize or create your own template:

    php artisan vendor:publish --provider='Seka19\LaravelBreadcrumbs\LaravelBreadcrumbsServiceProvider' --tag='views'

Then you can put new View name as second argument:

    {{ breadcrumbs('Catalog', 'vendor.breadcrumbs.your-breadcrubms') }}
    
## Publish config

There are only three parameters that should be obvious:

    php artisan vendor:publish --provider='Seka19\LaravelBreadcrumbs\LaravelBreadcrumbsServiceProvider' --tag='config'