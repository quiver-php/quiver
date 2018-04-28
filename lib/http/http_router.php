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
	private $routes = array();
	
	public function add_route(http_route $http_route)
	{
		if ( !$this->route_exists($http_route) )
		{
			array_push($this->routes, $http_route);
		}
		else
		{
			throw new \Exception('Duplicate route exists');
		}
	}
	
	private function route_exists(http_route $http_route)
	{
		$exists = false;
		
		foreach ($this->routes as $route)
		{
			// Check if there is a method match
			if ( $route->get_method() == $http_route->get_method() )
			{				
				// Check if there is a URI match
				if ( $route->get_uri_canonical() == $http_route->get_uri_canonical() )
				{
					$exists = true;
					
					break;
				}
			}
		}
		
		return $exists;
	}
	
	public function service(http_request $http_request, $root_directory)
	{
		$http_response = null;
	
		$route_selected = null;
		$parameters_path = array();
		$parameters_query = array();
		
		$controller_name = null;
		$controller_method = null;
		$controller_params = null;
	
		foreach ($this->routes as $route)
		{
			// Check if there is a method match
			if ( $route->get_method() == $http_request->get_method() )
			{
				if ($root_directory)
				{
					// Remove the root directory from HTTP request URI
					$http_request->set_uri( str_replace( $root_directory, '', urldecode( $http_request->get_uri_canonical() ) ) );
				}
				
				// Check if there is a URI match, and store all the path parameters
				$route_request_match = preg_match( $route->get_uri_pattern(), $http_request->get_uri_canonical(), $parameters_path );

				// If the HTTP request matches an existing HTTP route...
				if ($route_request_match)
				{
					// Store the matched route
					$route_selected = $route;
	
					// Filter the path parameters array. Remove clutter from the array and only leave the clean, named key value pairs
					foreach ($parameters_path as $parameters_path_key => $parameters_path_value)
					{
						if ( is_numeric($parameters_path_key) )
						{
							unset( $parameters_path[$parameters_path_key] );
						}
					}
					
					// Store the query parameters
					$parameters_query_string = parse_url($http_request->get_uri(), PHP_URL_QUERY);
					parse_str($parameters_query_string, $parameters_query);

					// Get the controller name and the controller method from the selected route
					$controller_name = $route_selected->get_controller();
					$controller_method = $route_selected->get_controller_method();
					
					// Initialize the controller
					$controller = new $controller_name($http_request, $parameters_path, $parameters_query);
					
					// Run the controller method and get the HTTP response, or null in case of failure
					$http_response = $controller->$controller_method();
					
					if ( is_null($http_response) )
					{
						$http_response = new http_response(500);
					}
		
					break;
				}
			}
		}
		
		if ( is_null($http_response) )
		{
			$http_response = new http_response(404);
		}
		
		return $http_response;
	}
}
