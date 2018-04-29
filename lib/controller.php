<?php

// ============================================================
// Controller
// Designed and developed by Daniel Carvalho
// Copyright (c) Daniel Carvalho - http://danielcarvalho.com/
// ============================================================

namespace quiver;

use quiver\http\http_request;
use quiver\http\http_response;

class controller
{
	protected $http_request;
	protected $http_response;
	protected $parameters_path;
	protected $parameters_query;
	
	public function __construct(http_request $http_request, array $parameters_path, array $parameters_query)
	{
		$this->http_request = $http_request;
		$this->http_response = new http_response();
		$this->parameters_path = $parameters_path;
		$this->parameters_query = $parameters_query;
	}
}
