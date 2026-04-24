<?php


$frontend_http = env("FRONTEND_HTTP", "http");
$frontend_domain = env("FRONTEND_DOMAIN", "localhost");
$frontend_port = env("FRONTEND_PORT");
$frontend_url = $frontend_port
	? "$frontend_http://$frontend_domain:$frontend_port"
	: "$frontend_http://$frontend_domain";
$allowed_origins = array_values(array_unique([
	$frontend_url,
	'http://localhost:3000',
	'http://localhost:4000',
	'http://127.0.0.1:3000',
	'http://127.0.0.1:4000',
]));

return [

	/*
			 |--------------------------------------------------------------------------
			 | Cross-Origin Resource Sharing (CORS) Configuration
			 |--------------------------------------------------------------------------
			 |
			 | Here you may configure your settings for cross-origin resource sharing
			 | or "CORS". This determines what cross-origin operations may execute
			 | in web browsers. You are free to adjust these settings as needed.
			 |
			 | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
			 |
			 */

	'paths' => ['api/*', 'sanctum/csrf-cookie', '/login', '/logout'],

	'allowed_methods' => ['*'],

	'allowed_origins' => [
		...$allowed_origins,
	],

	'allowed_origins_patterns' => [],

	'allowed_headers' => ['*'],

	'exposed_headers' => [],

	'max_age' => 0,

	'supports_credentials' => true,

];
