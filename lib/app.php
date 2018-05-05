<?php

// ============================================================
// App
// Designed and developed by Daniel Carvalho
// Copyright (c) Daniel Carvalho - http://danielcarvalho.com/
// ============================================================

namespace quiver;

use quiver\http\http_request;
use quiver\http\http_router;

class app
{
	public function __construct(array $routes, string $root_directory = '')
	{
		// Set the default timezone
		$this->set_timezone();
		
		// Initialize the router
		$http_router = new http_router();
		$http_router->set_root_directory($root_directory);

		// Add all the routes to the router
		foreach ($routes as $route)
		{
			$http_router->add_route($route);
		}

		// Get the HTTP request
		$http_request = new http_request();
		$http_request->fetch();

		// Service the HTTP request and get the HTTP response
		$http_response = $http_router->service($http_request);

		// Send the HTTP response
		$http_response->send();
	}
	
	public function get_timezone()
	{
		return date_default_timezone_get();
	}
	
	public function set_timezone(string $timezone_identifier = 'UTC')
	{
		// Check if the timezone identifier supplied is valid
		if ( in_array( $timezone_identifier, timezone_identifiers_list() ) )
		{
			date_default_timezone_set($timezone_identifier);
		}
		else
		{
			throw new \Exception('Invalid timezone identifier');
		}
	}
}
