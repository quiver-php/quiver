# Quiver
The Quiver PHP framework.

## Getting Started

### 01. Installation

Include Quiver as a dependancy for your project using [Composer](https://getcomposer.org/). In this example, our project specific code is going to sit in an "app" folder, and the composer setup file should look something like this.

```json
{
	"require":
	{
		"quiver/quiver": "0.1.0"
	},
	"autoload":
	{
		"psr-4":
		{
			"app\\": "app/"
		}
	}
}
```

### 02. Pretty URL Configuration

Create an **.htaccess** file in the root directory of your project, and enable the rewrite module in Apache to ensure that all requests go through Quiver, and that you'll have pretty URL's. _However_, Quiver can be used without .htaccess and mod_rewrite.

```
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [L]
```

### 03. Initialize Quiver

Create an **index.php** file in the root directory of your project, and initialize your project using Quiver. In this example, we are adding a route that will execute the hello_world function in the example class.

#### With .htaccess

Handles requests like: http://example.com/

```php
<?php

// Include the composer autoloader
require '/vendor/autoload.php';

// Define your routes
$routes = array(

	new quiver\http\http_route('GET', '', 'app\\example', 'hello_world')

);

// Initialize your app
$app = new quiver\app('', $routes);
```

#### Without .htaccess

Handles requests like: http://example.com/

```php
<?php

// Include the composer autoloader
require '/vendor/autoload.php';

// Define your routes
$routes = array(

	new quiver\http\http_route('GET', '', 'app\\example', 'hello_world')

);

// Initialize your app with index.php defined as your base URL, which will remove it from all incoming requests
$app = new quiver\app('index.php', $routes);
```

### 04. Example

Lastly, we need to create a controller to handle the potential request as defined by the route we set up. Create an **example.php** file in your app directory. In it, we'll have our example class which extends the Quiver controller, and simply returns the response, "Hello World!".

```php
<?php

namespace app;

class example extends \quiver\controller
{
	public function hello_world()
	{
		$this->http_response->set_body('Hello World!');

		return $this->http_response;
	}
}
```
