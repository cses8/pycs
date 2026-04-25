<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('login accepts legacy bcrypt passwords and rehashes them to the configured driver', function () {
	config(['hashing.driver' => 'argon2id']);

	$user = User::factory()->create([
		'email' => 'legacy@example.com',
	]);
	DB::table('users')->where('id', $user->id)->update([
		'password' => password_hash('correct-password', PASSWORD_BCRYPT),
	]);

	expect(password_get_info($user->fresh()->password)['algoName'])->toBe('bcrypt');

	$this->postJson('/login', [
		'email' => 'legacy@example.com',
		'password' => 'correct-password',
	])->assertSuccessful();

	expect(password_get_info($user->fresh()->password)['algoName'])->toBe('argon2id');
});

test('login rejects invalid legacy bcrypt passwords without rehashing', function () {
	config(['hashing.driver' => 'argon2id']);

	$user = User::factory()->create([
		'email' => 'legacy@example.com',
	]);
	DB::table('users')->where('id', $user->id)->update([
		'password' => password_hash('correct-password', PASSWORD_BCRYPT),
	]);

	$this->postJson('/login', [
		'email' => 'legacy@example.com',
		'password' => 'wrong-password',
	])->assertUnprocessable();

	expect(password_get_info($user->fresh()->password)['algoName'])->toBe('bcrypt');
});
