<?php

// ============================================================
// HTTP Response
// Designed and developed by Daniel Carvalho
// Copyright (c) Daniel Carvalho - http://danielcarvalho.com/
// ============================================================

namespace quiver\http;

use quiver\http\http_message;

class http_response extends http_message
{	
	private $status_code_definitions = array(
		
		// Informational 1xx
		100 => 'Continue',
		101 => 'Switching Protocols',

		// Successful 2xx
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',

		// Redirection 3xx
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => null, // Unused
		307 => 'Temporary Redirect',

		// Client Error 4xx
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',

		// Server Error 5xx
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported'

	);
	
	private $status_code = 200;
	
	public function __construct($status_code = 200, $body = '')
	{
		$this->set_status_code($status_code);
		$this->set_body($body);
	}

	public function get_status_code()
	{
		return $this->status_code;
	}
	
	public function set_status_code($status_code = 200)
	{
		if ( array_key_exists($status_code, $this->status_code_definitions) )
		{
			$this->status_code = $status_code;
		}
		else
		{
			throw new \Exception('Invalid HTTP status code');
		}
	}
	
	public function get_status_code_definition()
	{
		return $this->status_code_definitions[ $this->get_status_code() ];
	}
	
	public function send()
	{
		// $this->add_header( self::HTTP_VERSION . ' ' . $this->get_status_code() . ' ' . $this->get_status_code_definition() );		
		header( self::HTTP_VERSION . ' ' . $this->get_status_code() . ' ' . $this->get_status_code_definition() );
		
		if ( !empty( $this->get_body() ) )
		{
			$this->add_header( 'Content-Length', (string)strlen( $this->get_body() ) );
		}
		
		foreach ( $this->get_headers() as $header_name => $header_value )
		{
			header($header_name . ': ' . $header_value);
		}
		
		echo $this->get_body();
	}
}

?>