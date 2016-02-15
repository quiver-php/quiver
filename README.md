# Quiver
The Quiver PHP framework.

## Getting Started

### 01. Installation

Include Quiver as a dependancy for your project using [Composer](https://getcomposer.org/). In this example setup, our project specific code is going to sit in an "app" folder, and the composer setup file should look something like this.

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

### 02. Configuration

Create an **.htaccess** file in the root directory of your project to ensure that all requests go through Quiver.

```
RewriteEngine On

RewriteRule ^ index.php [QSA]
```

### 03. Initialize Quiver

Create an **index.php** file in the root directory of your project, and initialize your project using Quiver. In this example, we are adding a route that will execute the hello_world function in the example class.

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

### 04. Example

Lastly, create an **index.php** file in your app directory. Our example project simply returns "Hello World!".

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
