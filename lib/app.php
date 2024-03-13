<?php

// ============================================================
// App
// Designed and developed by Daniel Carvalho
// Copyright (c) Daniel Carvalho - https://danielcarvalho.com/
// ============================================================

namespace quiver;

use quiver\http\http_request;
use quiver\http\http_response;
use quiver\http\http_router;

class app
{
	protected http_router $http_router;
	protected array $trusted_hosts = [];

	public function __construct(array $routes, string $root_directory = '')
	{
		// Set the default timezone
		$this->set_timezone();

		// Initialize the router
		$this->http_router = new http_router($this);
		$this->http_router->set_root_directory($root_directory);

		// Add all the routes to the router
		foreach ($routes as $route)
			$this->http_router->add_route($route);
	}

	public function serve()
	{
		// Get the HTTP request
		$http_request = new http_request();
		$http_request->fetch();

		// Service the HTTP request and get the HTTP response
		$http_response = $this->http_router->service($http_request);

		if ( $http_response->is_error() )
			$http_response = $this->error_handler($http_response);

		// Send the HTTP response
		$http_response->send();
	}

	// Stub to be overridden for custom error handling
	protected function error_handler(http_response $http_response): http_response
	{
		return $http_response;
	}

	public function get_timezone(): string
	{
		return date_default_timezone_get();
	}

	public function set_timezone(string $timezone_id = 'UTC')
	{
		$valid = date_default_timezone_set($timezone_id);

		if ($valid === false)
			throw new \Exception("Invalid timezone identifier $timezone_id");
	}

	public function get_preferred_host(): string
	{
		return reset($this->trusted_hosts);
	}

	public function get_trusted_hosts(): array
	{
		return $this->trusted_hosts;
	}

	public function set_trusted_hosts(array $hosts)
	{
		// Remove potential duplicates
		$trusted_hosts = array_unique($hosts);

		// Validate hosts
		foreach ($trusted_hosts as $trusted_host)
		{
			$valid = filter_var($trusted_host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);

			if ($valid === false)
				return new \Exception("Invalid hostname $trusted_host");
		}

		$this->trusted_hosts = $trusted_hosts;
	}
}
