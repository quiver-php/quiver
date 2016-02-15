<?php

// ============================================================
// HTTP Request
// Designed and developed by Daniel Carvalho
// Copyright (c) Daniel Carvalho - http://danielcarvalho.com/
// ============================================================

namespace quiver\http;

use quiver\http\http_message;

class http_request extends http_message
{
	private $methods = array(
	
		'OPTIONS' => 'OPTIONS',
		'GET' => 'GET',
		'HEAD' => 'HEAD',
		'POST' => 'POST',
		'PUT' => 'PUT',
		'DELETE' => 'DELETE',
		'TRACE' => 'TRACE',
		'CONNECT' => 'CONNECT'
		
	);	
	
	private $method = 'GET';
	private $uri = '';
	
	public function __construct($method = 'GET', $uri = '')
	{
		$this->set_method($method);
		$this->set_uri($uri);
	}
	
	public function get_method()
	{
		return $this->method;
	}
	
	public function set_method($method = 'GET')
	{
		if ( array_key_exists($method, $this->methods) )
		{
			$this->method = $method;
		}
		else
		{
			throw new \Exception('Invalid HTTP method');
		}
	}
	
	public function get_uri()
	{
		return $this->uri;
	}
	
	public function set_uri($uri)
	{
		if ( is_string($uri) )
		{
			$this->uri = $uri;
		}
		else
		{
			throw new \Exception('Argument passed to http_request::set_uri() must be a string');			 
		}
	}
	
	public function get_uri_canonical()
	{
		// Get the URI and remove the query string
		$uri = parse_url( $this->get_uri(), PHP_URL_PATH );
		
		// Remove leading and trailing slashes
		$uri_canonical = trim($uri, '/');
		
		// Convert the URI to lowercase
		$uri_canonical = strtolower($uri_canonical);
		
		return $uri_canonical;
	}
	
	// public function fetch($uri_root = '')
	public function fetch()
	{	
		// Get and set the headers
		foreach ( apache_request_headers() as $name => $value )
		{
			$this->add_header($name, $value);
		}
		
		// Get and set the body
		$this->set_body( file_get_contents('php://input') );
		
		// Get and set the request method
		$this->set_method( $_SERVER['REQUEST_METHOD'] );
		
		// Get and set the request URI
		$this->set_uri( $_SERVER['REQUEST_URI'] );
	}
}

?>