<?php

// ============================================================
// HTTP Request
// Designed and developed by Daniel Carvalho
// Copyright (c) Daniel Carvalho - https://danielcarvalho.com/
// ============================================================

namespace quiver\http;

use quiver\http\http_message;

class http_request extends http_message
{
	private http_method $method = http_method::GET;
	private string $uri = '';
	private array $parameters_query = [];
	
	public function __construct(http_method $method = http_method::GET, string $uri = '')
	{
		$this->set_method($method);
		$this->set_uri($uri);
	}
	
	public function get_method(): http_method
	{
		return $this->method;
	}
	
	public function set_method(http_method $method = http_method::GET)
	{
		$this->method = $method;
	}
	
	public function get_uri(): string
	{
		return $this->uri;
	}
	
	public function set_uri(string $uri)
	{
		$this->uri = $uri;
	}
	
	public function get_uri_canonical(): string
	{
		// Get the URI and remove the query string
		$uri = parse_url( $this->get_uri(), PHP_URL_PATH );
		
		// Remove leading and trailing slashes
		$uri_canonical = trim($uri, '/');
		
		// Convert the URI to lowercase
		$uri_canonical = strtolower($uri_canonical);
		
		return $uri_canonical;
	}

	public function get_uri_absolute(string $host = '', ?string $uri = null): string
	{
		// Is an HTTPS server?
		$is_https = !empty( $_SERVER['HTTPS'] );

		// Is an HTTPS server behind a reverse proxy?
		$is_https_forwarded = !empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https';

		$protocol = $is_https || $is_https_forwarded ? 'https' : 'http';

		// HTTP_HOST is spoofable and therefore unreliable
		if ( empty($host) )
			$host = $_SERVER['HTTP_HOST'];

		if ($uri === null)
			$uri = $this->get_uri_canonical();

		return "$protocol://$host/$uri";
	}

	public function fetch()
	{
		// Get and set the headers
		foreach ( $this->get_request_headers() as $name => $value )
			$this->add_header($name, $value);
		
		// Get and set the body
		$this->set_body( file_get_contents('php://input') );
		
		// Get and set the request method
		$this->set_method( http_method::from( $_SERVER['REQUEST_METHOD'] ) );
		
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
