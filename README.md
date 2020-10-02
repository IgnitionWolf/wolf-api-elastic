# Elastic Cache Bridge

This is a bridge between the [Elastic Cache Migrations package built by babenkoivan](https://github.com/babenkoivan/elastic-scout-driver) and [WolfAPI](https://github.com/IgnitionWolf/wolf-api). It was necessary to build this because WolfAPI relies on Laravel Modules, and we needed to add support to create elastic migrations following the modular structure.

This is a fully optional package, only use this if you're using [babenkoivan's Elastic Cache Driver package](https://github.com/babenkoivan/elastic-scout-driver) with WolfAPI. 

## Installation

```
composer require ignitionwolf/wolf-api-elastic
```

## Usage

This package adds a new command:

```
php artisan module:elastic:make-migration [name] [module] 
```

You can use ``php artisan elastic:migrate`` to migrate as per usual.
