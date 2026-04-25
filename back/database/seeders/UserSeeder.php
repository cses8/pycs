<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$users = [
			[
				"name" => 'Moises Kevin Magno',
				"email" => "moiseskevin.dev@gmail.com",
				"password" => "mkmagno"
			],
			[
				"name" => 'PYCS Administration',
				"email" => "pycs.65@gmail.com",
				"password" => "theorientdepths"
			]
		];

		foreach ($users as $key => $user) {
			User::updateOrCreate(
				[
					'email' => $user['email'],
				],
				[
					'name' => $user['name'],
					'role' => User::ROLE_ADMIN,
					'password' => Hash::make($user['password'])
				]
			);
		}
	}
}
