<?php

// ============================================================
// HTTP Message
// Designed and developed by Daniel Carvalho
// Copyright (c) Daniel Carvalho - http://danielcarvalho.com/
// ============================================================

namespace quiver\http;

class http_message
{
	const HTTP_VERSION = 'HTTP/1.1';
	
	private $headers = array();
	private $body = '';
	
	public function get_headers()
	{
		return $this->headers;
	}
	
	public function add_header($name, $value)
	{
		if ( 
			is_string($name) && !empty($name) &&
			is_string($value) && !empty($value)
		)
		{
			// Store the HTTP header in the list, existing headers of the same field name are overwritten
			$this->headers[$name] = $value;
		}
		else
		{
			throw new \Exception('Arguments passed to http_message::add_header() must be a string and cannot be empty');
		}
	}
	
	public function get_body()
	{
		return $this->body;
	}
	
	public function set_body($body)
	{
		if ( is_string($body) )
		{
			$this->body = $body;
		}
		else
		{
			throw new \Exception('Argument passed to http_message::set_body() must be a string');			 
		}		
	}
}

?>