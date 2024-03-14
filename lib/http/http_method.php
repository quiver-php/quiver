<?php

// ============================================================
// HTTP Method
// Designed and developed by Daniel Carvalho
// Copyright (c) Daniel Carvalho - https://danielcarvalho.com/
// ============================================================

namespace quiver\http;

enum http_method: string
{
	case OPTIONS = 'OPTIONS';
	case GET     = 'GET';
	case HEAD    = 'HEAD';
	case POST    = 'POST';
	case PUT     = 'PUT';
	case DELETE  = 'DELETE';
	case TRACE   = 'TRACE';
	case CONNECT = 'CONNECT';
}
