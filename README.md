# Quiver

A lite extension of a thin HTTP library that gives you the freedom to write and organize PHP applications however you like.

## Getting started

### Installation

Install Quiver with [Composer](https://getcomposer.org/). Quiver requires **PHP 8.1** or higher. 

```
$ composer require quiver/quiver
```

### Web server configuration

Make sure the web server you use (Nginx, Apache, etc...) is configured to route all relevant requests to your project's front controller, which will initialize Quiver and process those requests.

Here's a basic server block example to get you started using Nginx.

```nginx
server {
	listen 80 default_server;
	listen [::]:80 default_server;

	root /var/www/html;
	index index.htm index.php;

	server_name example.com;

	location / {
		try_files $uri $uri/ /index.php;
	}

	location ~ \.php$ {
		include snippets/fastcgi-php.conf;
		fastcgi_pass unix:/run/php/php8.1-fpm.sock;
	}
}

```

### Set up the autoloader

In this example our project-specific code will be placed in an "app" folder. Given that, your `composer.json` file should look something like this.

```json
{
    "require": {
        "quiver/quiver": "^0.3.0"
    },
    "autoload": {
        "psr-4": {
            "app\\": "app/"
        }
    }
}
```

Then update the autoloader with the following command.

```
$ composer dump-autoload
```

### Create the front controller to initialize Quiver

Create an `index.php` file in the root directory of your project. This will be the front controller that will initialize Quiver. 

In it, we will add a route `/hello/` that will execute the `hello_world` function in a controller class that we will create in the next step.

```php
<?php

// Include the composer autoloader
require 'vendor/autoload.php';

use quiver\http\http_method;
use quiver\http\http_route;
use quiver\app;

// Define your routes
$routes = [
	new http_route(http_method::GET, '/hello/', 'app\example', 'hello_world')
];

// Initialize your app
$app = new app($routes);

// Serve all incoming requests
$app->serve();
```

### Create the controller

Lastly, create an `example.php` file in the app directory of your project. This will be the controller that handles the request as defined by the route we set up in our front controller.

In it, we'll have a class named example which extends the Quiver controller with a function that simply returns the response, "Hello World!".

```php
<?php

namespace app;

use quiver\controller;
use quiver\http\http_status;
use quiver\http\http_request;
use quiver\http\http_response;

class example extends controller
{
	public function hello_world(http_request $http_request): http_response
	{
		return new http_response(http_status::OK, 'Hello World!');
	}
}
```

Congratulations! You can now visit `http://localhost:8080/hello/` (depending on your web server configuration).

## About

Quiver was made by [Daniel Carvalho](https://danielcarvalho.com) primarily for his own personal use.

## License

Quiver is released under the [MIT](https://opensource.org/license/MIT) license.
