<?php

// ============================================================
// HTTP Response
// Designed and developed by Daniel Carvalho
// Copyright (c) Daniel Carvalho - https://danielcarvalho.com/
// ============================================================

namespace quiver\http;

use quiver\http\http_message;

class http_response extends http_message
{
	private array $status_code_definitions = [

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

	];

	private http_status $status_code = http_status::OK;

	public function __construct(http_status $status_code = http_status::OK, string $body = '')
	{
		$this->set_status_code($status_code);
		$this->set_body($body);
	}

	public function get_status_code(): http_status
	{
		return $this->status_code;
	}

	public function set_status_code(http_status $status_code = http_status::OK)
	{
		$this->status_code = $status_code;
	}

	public function get_status_code_definition(): string
	{
		return $this->status_code_definitions[ $this->get_status_code()->value ];
	}

	public function is_error(): bool
	{
		$status_code = $this->get_status_code();

		$first_digit = substr($status_code->value, 0, 1);

		return $first_digit === '4' || $first_digit === '5';
	}

	public function send()
	{
		header( self::HTTP_VERSION . ' ' . $this->get_status_code()->value . ' ' . $this->get_status_code_definition() );

		$body = $this->get_body();

		if ( !empty($body) )
			$this->add_header( 'Content-Length', (string)strlen($body) );

		foreach ( $this->get_headers() as $header_name => $header_value )
			header("$header_name : $header_value");

		echo $body;
	}
}
