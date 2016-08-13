Laravel 5.2 Menu
==

## Installation

1. Run
```php   
composer require sciarcinski/laravel-menu 0.1
```     
in console to install this module

2. Open `config/app.php` and:

#### Service Provider
```php
Sciarcinski\LaravelMenu\MenusServiceProvider::class,
```

#### Facade
```php
'Menu' => Sciarcinski\LaravelMenu\Facades\Menu::class,
```

## Getting started

To get started run:

```php
php artisan menu:make TestMenu
```
This will create a file: App\Menus\TestMenu

**To render the menu in your view:**
```html
<ul>
  {!! Menu::get('test_menu')->render() !!}
</ul>
```
