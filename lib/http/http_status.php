<?php

// ============================================================
// HTTP Status
// Designed and developed by Daniel Carvalho
// Copyright (c) Daniel Carvalho - https://danielcarvalho.com/
// ============================================================

namespace quiver\http;

enum http_status: int
{
	// Informational 1xx
	case CONTINUE                        = 100;
	case SWITCHING_PROTOCOLS             = 101;

	// Successful 2xx
	case OK                              = 200;
	case CREATED                         = 201;
	case ACCEPTED                        = 202;
	case NON_AUTHORITATIVE_INFORMATION   = 203;
	case NO_CONTENT                      = 204;
	case RESET_CONTENT                   = 205;
	case PARTIAL_CONTENT                 = 206;

	// Redirection 3xx
	case MULTIPLE_CHOICES                = 300;
	case MOVED_PERMANENTLY               = 301;
	case FOUND                           = 302;
	case SEE_OTHER                       = 303;
	case NOT_MODIFIED                    = 304;
	case USE_PROXY                       = 305;
	case NULL                            = 306; // Unused
	case TEMPORARY_REDIRECT              = 307;

	// Client Error 4xx
	case BAD_REQUEST                     = 400;
	case UNAUTHORIZED                    = 401;
	case PAYMENT_REQUIRED                = 402;
	case FORBIDDEN                       = 403;
	case NOT_FOUND                       = 404;
	case METHOD_NOT_ALLOWED              = 405;
	case NOT_ACCEPTABLE                  = 406;
	case PROXY_AUTHENTICATION_REQUIRED   = 407;
	case REQUEST_TIMEOUT                 = 408;
	case CONFLICT                        = 409;
	case GONE                            = 410;
	case LENGTH_REQUIRED                 = 411;
	case PRECONDITION_FAILED             = 412;
	case REQUEST_ENTITY_TOO_LARGE        = 413;
	case REQUEST_URI_TOO_LONG            = 414;
	case UNSUPPORTED_MEDIA_TYPE          = 415;
	case REQUESTED_RANGE_NOT_SATISFIABLE = 416;
	case EXPECTATION_FAILED              = 417;

	// Server Error 5xx
	case INTERNAL_SERVER_ERROR           = 500;
	case NOT_IMPLEMENTED                 = 501;
	case BAD_GATEWAY                     = 502;
	case SERVICE_UNAVAILABLE             = 503;
	case GATEWAY_TIMEOUT                 = 504;
	case HTTP_VERSION_NOT_SUPPORTED      = 505;
}
