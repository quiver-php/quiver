<?php

// ============================================================
// HTTP Route
// Designed and developed by Daniel Carvalho
// Copyright (c) Daniel Carvalho - http://danielcarvalho.com/
// ============================================================

namespace quiver\http;

use quiver\http\http_request;

class http_route extends http_request
{
	private $controller;
	private $controller_method;
	
	public function __construct($method, $uri, $controller, $controller_method)
	{
		parent::__construct($method, $uri);
		
		// Check if the URI is a syntactically correct route URI
		if( $this->uri_validate() != 1 ) // 1 for match, 0 for no match, false on error
		{
			throw new \Exception('Invalid route URI');
		}
		
		$this->set_controller($controller);
		$this->set_controller_method($controller_method);
	}
	
	public function get_controller()
	{
		return $this->controller;
	}
	
	public function set_controller($controller)
	{
		$this->controller = $controller;
	}
	
	public function get_controller_method()
	{
		return $this->controller_method;
	}
	
	public function set_controller_method($controller_method)
	{
		$this->controller_method = $controller_method;
	}
	
	public function get_uri_pattern()
	{		
		$uri_regex = '#^' . preg_replace( '#[:]([\w]+)#', '(?<\1>[\w\d]+)', $this->get_uri_canonical() ) . '$#';
		
		return $uri_regex;
	}
	
	//
	// Need to fix regex to allow just '/' and '' path. Otherwise perfect. 
	// ':test' should not work? NEED to write a test for this.
	//
	private function uri_validate()
	{
		// Check if the URI is a syntactically correct route URI
		// $uri_match = preg_match( '#^((^|\/)[:]?[\w]+)+$#', $this->get_uri_canonical() );
		$uri_match = preg_match( '#^((^|\/)[:]?[\w]+|\z)+$#', $this->get_uri_canonical() ); // \z allows for nothing so we don't have to do a string empty or worry about the special case
		
		return $uri_match;
	}
}

?>