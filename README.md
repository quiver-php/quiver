# Quiver
The Quiver PHP framework.

## Installation

Install Quiver with [Composer](https://getcomposer.org/). Quiver requires **PHP 7.0** or higher. 

```
$ composer require quiver/quiver
```

## Web server configuration

Whatever web server you use, make sure you route all requests to your project's front controller, which will initialize Quiver.

Here's a basic example to get you going using Apache. Create an `.htaccess` file in the root directory of your project with the following contents.

```
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [L]
```

Enable the rewrite module, and set `AllowOverride All` so that your `.htaccess` file is not ignored.

## Creating your first project

### Set up the autoloader

In this example, our project-specific code is going to sit in an "app" folder, and your `composer.json` file should look something like this.

```json
{
    "require": {
        "quiver/quiver": "^0.1.2"
    },
    "autoload": {
        "psr-4": {
            "app\\": "app/"
        }
    }
}
```

### Initialize Quiver

Create an `index.php` file in the root directory of your project, which will be your front controller that initializes Quiver. In this example, we are adding a route `/hello/` that will execute the `hello_world` function in the example controller class we are going to create.

```php
<?php

// Include the composer autoloader
require 'vendor/autoload.php';

use quiver\http\http_route;
use quiver\app;

// Define your routes
$routes = array(

	new http_route('GET', '/hello/', 'app\example', 'hello_world')

);

// Initialize your app
$app = new app('', $routes);
```

### Create the controller

Lastly, we need to create a controller to handle the request as defined by the route we set up in our front controller. Create an `example.php` file in your app directory. In it, we'll have our example class which extends the Quiver controller, that simply returns the response, "Hello World!".

```php
<?php

namespace app;

use quiver\controller;

class example extends controller
{
	public function hello_world()
	{
		$this->http_response->set_body('Hello World!');

		return $this->http_response;
	}
}
```

Congratulations! You can now visit `http://localhost:8080/hello/` (depending on your web server configuration).
