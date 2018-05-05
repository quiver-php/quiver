<?php

// ============================================================
// HTTP Router
// Designed and developed by Daniel Carvalho
// Copyright (c) Daniel Carvalho - http://danielcarvalho.com/
// ============================================================

namespace quiver\http;

use quiver\http\http_request;
use quiver\http\http_response;
use quiver\http\http_route;

class http_router
{
	private $root_directory = '';
	private $routes = array();

	public function get_root_directory()
	{
		return $this->root_directory;
	}

	public function set_root_directory(string $root_directory = '')
	{
		$this->root_directory = trim($root_directory, '/');
	}

	private function get_route(http_request $http_request)
	{
		$http_route = null;

		$is_http_route = ($http_request instanceof http_route);

		foreach ($this->routes as $route)
		{
			// Check if there is a method match
			if ( $route->get_method() === $http_request->get_method() )
			{
				$uri_match = false;

				/* 
				 * Check if there is a URI match
				 *
				 * It's necessary to have two different URI checking mechanisms like this because when the 
				 * router is getting setup and its routes are being added, you want to do string literal comparison
				 * and not pattern matching, and the opposite is true for when the router is servicing an actual request
				 */
				if ($is_http_route === true)
				{
					// For comparing one route URI versus another, we can do a straight canonical URI comparison
					// Example: /home/ == /home/
					$uri_match = ( $route->get_uri_canonical() === $http_request->get_uri_canonical() );
				}
				else
				{
					// For comparing an actual request URI versus a route URI, we need to do pattern matching
					// Example: /user/:user_id/ == /user/3/
					$uri_match = preg_match( $route->get_uri_pattern(), $http_request->get_uri_canonical() );
				}

				if ($uri_match)
				{
					$http_route = $route;

					break;
				}
			}
		}
		
		return $http_route;
	}
	
	public function add_route(http_route $http_route)
	{
		if ( $this->get_route($http_route) === null )
		{
			array_push($this->routes, $http_route);
		}
		else
		{
			throw new \Exception('Duplicate route exists');
		}
	}
	
	public function service(http_request $http_request)
	{
		$http_response = null;
		
		if ($this->root_directory)
		{
			// Remove the root directory from HTTP request URI
			$http_request->set_uri( str_replace( $this->root_directory, '', urldecode( $http_request->get_uri_canonical() ) ) );
		}

		// Get the matching route, if there is one
		$route_selected = $this->get_route($http_request);

		if ($route_selected !== null)
		{
			$parameters_path = array();
			$parameters_query = array();

			// Get all the path parameters
			preg_match( $route_selected->get_uri_pattern(), $http_request->get_uri_canonical(), $parameters_path );
			
			// Filter the path parameters array. Remove clutter from the array and only leave the clean, named key value pairs
			foreach ($parameters_path as $parameters_path_key => $parameters_path_value)
			{
				if ( is_numeric($parameters_path_key) )
				{
					unset( $parameters_path[$parameters_path_key] );
				}
			}
			
			// Get all the query parameters
			$parameters_query_string = parse_url( $http_request->get_uri(), PHP_URL_QUERY );
			parse_str($parameters_query_string, $parameters_query);

			// Get the controller name and the controller method from the selected route
			$controller_name = $route_selected->get_controller();
			$controller_method = $route_selected->get_controller_method();
			
			// Initialize the controller
			$controller = new $controller_name($http_request, $parameters_path, $parameters_query);
			
			// Run the controller method and get the HTTP response, or null in case of failure
			$http_response = $controller->$controller_method();
			
			if ($http_response === null)
			{
				$http_response = new http_response(500);
			}
		}
		else
		{
			$http_response = new http_response(404);
		}

		return $http_response;
	}
}
