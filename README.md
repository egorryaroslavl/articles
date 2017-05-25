# Articles form

Installation
------------

```
composer require "egorryaroslavl/articles":"1.*"
```

Then add ServiceProviders

``` 
  'providers' => [
    // ...
    Egorryaroslavl\Articles\ArticlesServiceProvider::class,
    Collective\Html\HtmlServiceProvider::class,
    Intervention\Image\ImageServiceProvider::class,
    Barryvdh\Elfinder\ElfinderServiceProvider::class,
    Arrilot\Widgets\ServiceProvider::class,
    // ...
  ],
```
and aliases 

``` 
  'aliases' => [
    // ...
      'Form' => Collective\Html\FormFacade::class,
      'Html' => Collective\Html\HtmlFacade::class,
      'Image' => Intervention\Image\Facades\Image::class,
      'Widget' => Arrilot\Widgets\Facade::class,
      'AsyncWidget' => Arrilot\Widgets\AsyncFacade::class,
    // ...
  ],
``` 
and run
``` 
php artisan vendor:publish 
```


And after all, run this...

```
php artisan migrate
```


To create a Widget class we must use artisan command
```
php artisan make:widget WidgetClassName
```
# articles
