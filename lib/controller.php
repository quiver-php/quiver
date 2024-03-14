<?php

// ============================================================
// Controller
// Designed and developed by Daniel Carvalho
// Copyright (c) Daniel Carvalho - https://danielcarvalho.com/
// ============================================================

namespace quiver;

use quiver\http\http_request;
use quiver\http\http_response;
use quiver\http\http_router;

class controller
{
	protected app $app;
	protected array $parameters_path;

	public function __construct(app $app, array $parameters_path = [])
	{
		$this->app = $app;
		$this->parameters_path = $parameters_path;
	}

	public function parameter_path(string $key): string
	{
		if ( array_key_exists($key, $this->parameters_path) )
			return $this->parameters_path[$key];

		return '';
	}
}
