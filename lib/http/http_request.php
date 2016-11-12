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
	
	public function __construct(string $method = 'GET', string $uri = '')
	{
		$this->set_method($method);
		$this->set_uri($uri);
	}
	
	public function get_method()
	{
		return $this->method;
	}
	
	public function set_method(string $method = 'GET')
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
	
	public function set_uri(string $uri)
	{
		$this->uri = $uri;
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
	
	public function fetch()
	{	
		// Get and set the headers
		foreach ( $this->get_request_headers() as $name => $value )
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
	
	private function get_request_headers()
	{
		$headers = array();

		$headers_to_include = array(
			'CONTENT_TYPE',
			'CONTENT_LENGTH'
		);
		
		$headers_to_uppercase = array(
			'TE',
			'DNT'
		);
		
		foreach ($_SERVER as $name => $value)
		{
			$http_prefix = strpos($name, 'HTTP_');
			
			// Example: HTTP_ACCEPT_ENCODING -> Accept-Encoding
			if ( $http_prefix !== false || in_array($name, $headers_to_include) )
			{
				$name = str_replace('_', '-', $name);
				
				// Remove HTTP prefix, if there is one
				if ($http_prefix !== false)
				{
					$name = substr($name, 5);
				}
				
				// Apply appropriate casing
				if ( !in_array($name, $headers_to_uppercase) )
				{
					$name = ucwords( strtolower($name), '-' );
				}
				
				$headers[$name] = $value;
			}
		}
		
		return $headers;
	}
}

?>