# Laravel package for displaying breadcrumbs in views

Breadcrumbs are saving in the DB for each page by its URI parts.

Example:

**1\.** In the View for the page with URL `http://site.com/catalog`

    {{ breadcrumbs('Catalog') }}
    
Will render only one breadcrumb:

    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">
            Catalog
        </li>
    </ol>

**2\.** In the page with URL `http://site.com/catalog/cars`

    {{ breadcrumbs('Cars') }}
        
Will render:
    
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="http://site.com/catalog">Catalog</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Cars
        </li>
    </ol>

**3\.** In the page with URL `http://site.com/catalog/cars/ford`

    {{ breadcrumbs('Ford') }}
        
Will render:
    
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="http://site.com/catalog">Catalog</a>
        </li>
        <li class="breadcrumb-item">
            <a href="http://site.com/catalog/cars">Cars</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Ford
        </li>
    </ol>