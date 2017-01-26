# Heidi-Plugin
___
Heidi-Plugin is a Wordpress plugin framework with the idea that every piece of code should have a home. Heidi takes an opinionated approach about how your hooks should be organized & delegated. Heidi relies extensively on the Wordpress hooks API, mimicking an MVC framework to compute, delegate, and render plugin functionality.
>Please note: This is not an MVC framework. There is no concept of REST, it is merely a structure that flows in a similar fashion.

### Routing
Every action hook is registered in the `plugin/routes.php` file

In `plugin/routes.php`
```php
$router->register([
    'admin_menu' => [
        'AdminController@registerSettingsPage'
    ]
]);
```

### Controllers
Wordpress will now look for a class called `AdminController` which should provide the `registerSettingsPage()` function.

In `plugin/Contollers/AdminController.php`
```php
namespace Heidi\Plugin\Controllers;

use Heidi\Plugin\Callbacks\AdminView;

class AdminController
{
    function registerSettingsPage()
    {
        add_menu_page(
            'Special Settings',
            'Special Settings',
            'manage_options',
            'special-settings',
            [new AdminView, 'render']
        );
    }
}
```

### Callback handlers
After defining the callback in the controller, Wordpress will now look for an `AdminView` class with the `render` function.

In `plugin/Callbacks/AdminView.php`
```php
namespace Heidi\Plugin\Callbacks;

class AdminView
{
    public function render()
    {
        $welcomeMessage = 'Hello, world!';
        view('admin.admin_settings', compact('welcomeMessage'));
    }
}
```

### Blade view templating
Finally, the view can be rendered with the Blade templating engine

In `plugin/view/admin/admin_settings.php`
```blade
@extends('admin.admin_layout')
@section('content')
    <h1>{{ $welcomeMessage }}</h1>
@endsection
```

## Contributing

Thank you for considering contributing to the Heidi framework! The contribution guide can be found in the [Heidi documentation](http://q4vr.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Heidi, please send an e-mail to Jeff See at jeff@q4launch.com. All security vulnerabilities will be promptly addressed.

## License

The Heidi framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
