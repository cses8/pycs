<?php

$frontendHttp = env('FRONTEND_HTTP', 'http');
$frontendDomain = env('FRONTEND_DOMAIN', 'localhost');
$frontendPort = env('FRONTEND_PORT', '4000');
$frontendUrl = $frontendPort
	? "{$frontendHttp}://{$frontendDomain}:{$frontendPort}"
	: "{$frontendHttp}://{$frontendDomain}";

$envOrigins = array_filter(array_map('trim', explode(',', (string) env('CORS_ALLOWED_ORIGINS', ''))));

$appEnv = env('APP_ENV', 'production');
$localOrigins = in_array($appEnv, ['local', 'testing'], true)
	? ['http://localhost:3000', 'http://localhost:4000', 'http://127.0.0.1:3000', 'http://127.0.0.1:4000']
	: [];

return [
	'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],
	'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
	'allowed_origins' => array_values(array_unique(array_filter([
		$frontendUrl,
		...$envOrigins,
		...$localOrigins,
	]))),
	'allowed_origins_patterns' => [],
	'allowed_headers' => ['Accept', 'Authorization', 'Content-Type', 'X-Requested-With', 'X-XSRF-TOKEN'],
	'exposed_headers' => [],
	'max_age' => 600,
	'supports_credentials' => true,
];
