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
	
	public function add_header(string $name, string $value)
	{
		// Store the HTTP header in the list, existing headers of the same field name are overwritten
		$this->headers[$name] = $value;
	}
	
	public function get_body()
	{
		return $this->body;
	}
	
	public function set_body(string $body)
	{
		$this->body = $body;
	}
}

?>