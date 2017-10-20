docker build -t dev_laravel:5.5 .

docker run --rm -it --name laravel_5.5 -v "$PWD":/project -w /project -p 8000:8000 dev_laravel:5.5 /bin/bash

### Starting a new project
* docker-compose exec app laravel new project-name --dev
* docker-compose exec app php artisan key:generate
* docker-compose exec app php artisan optimize

### Serving website
* docker-compose exec app php artisan serve --host=0.0.0.0 --port=8000

### Fixes
Once the new laravel app is built, add the following line to yourappname/app/Providers/AppServiceProvider.php
```
    public function boot()
    {
        \Illuminate\Database\Schema\Builder::defaultStringLength(191);
    }
```

