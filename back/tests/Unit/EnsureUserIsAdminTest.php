<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

uses(Tests\TestCase::class);

function adminMiddlewareRequest(?User $user): Request
{
	$request = Request::create('/admin-only');
	$request->setUserResolver(fn () => $user);

	return $request;
}

test('admin middleware rejects unauthenticated requests', function () {
	try {
		(new EnsureUserIsAdmin())->handle(
			adminMiddlewareRequest(null),
			fn () => new Response('ok'),
		);

		$this->fail('Unauthenticated users should not pass the admin middleware.');
	} catch (HttpException $exception) {
		expect($exception->getStatusCode())->toBe(Response::HTTP_FORBIDDEN)
			->and($exception->getMessage())->toBe('Administrator access is required.');
	}
});

test('admin middleware rejects non admin users', function () {
	try {
		(new EnsureUserIsAdmin())->handle(
			adminMiddlewareRequest(new User(['role' => User::ROLE_STAFF])),
			fn () => new Response('ok'),
		);

		$this->fail('Non-admin users should not pass the admin middleware.');
	} catch (HttpException $exception) {
		expect($exception->getStatusCode())->toBe(Response::HTTP_FORBIDDEN)
			->and($exception->getMessage())->toBe('Administrator access is required.');
	}
});

test('admin middleware allows admin users', function () {
	$response = (new EnsureUserIsAdmin())->handle(
		adminMiddlewareRequest(new User(['role' => User::ROLE_ADMIN])),
		fn () => new Response('ok'),
	);

	expect($response->getContent())->toBe('ok');
});
