# GeoIP2 for Laravel 5.4+

## Installation

1) In order to install run the following composer command:

``` bash
composer require nbz4live/laravel-geoip2
```
##### Laravel 5.5+ Install skip to step 4.

2) Open your `config/app.php` and add the following to the `providers` array:

``` php
Nbz4live\LaravelGeoIP2\GeoIP2ServiceProvider::class,
```

3) In the same config/app.php and add the following to the aliases array:

``` php
'GeoIP2' => Nbz4live\LaravelGeoIP2\GeoIP2Facade::class,
```

4) You can publish the config file by running (optional):

``` php
$ php artisan vendor:publish --provider="Nbz4live\LaravelGeoIP2\GeoIP2ServiceProvider"
```

5) Run the update command to download the latest required databases

``` php
$ php artisan geoip:update
```
