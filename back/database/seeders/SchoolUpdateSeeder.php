<?php

namespace Database\Seeders;

use App\Models\SchoolUpdate;
use App\Models\User;
use Illuminate\Database\Seeder;

class SchoolUpdateSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$user = User::query()->first();

		collect([
			[
				'title' => 'PYCS Opens Campus Innovation Week',
				'type' => 'news',
				'category' => 'Campus Life',
				'tags' => ['campus', 'students'],
			],
			[
				'title' => 'Parent Orientation Schedule Released',
				'type' => 'announcement',
				'category' => 'Parents',
				'tags' => ['parents', 'orientation'],
			],
			[
				'title' => 'How Students Build Strong Study Habits',
				'type' => 'blog',
				'category' => 'Academics',
				'tags' => ['academics', 'study'],
			],
			[
				'title' => 'Foundation Day Program',
				'type' => 'event',
				'category' => 'Events',
				'tags' => ['events', 'community'],
			],
		])->each(function (array $item, int $index) use ($user) {
			SchoolUpdate::query()->firstOrCreate(
				['title' => $item['title']],
				[
					'author_id' => $user?->id,
					'slug' => SchoolUpdate::uniqueSlug($item['title']),
					'summary' => 'Read the latest update from Philippine Yuh Chiau School.',
					'content' => '<p>Philippine Yuh Chiau School shares timely information for learners, parents, and the wider school community.</p>',
					'type' => $item['type'],
					'category' => $item['category'],
					'tags' => $item['tags'],
					'status' => 'published',
					'published_at' => now()->subDays(8 - $index),
					'event_start_at' => $item['type'] === 'event' ? now()->addWeeks(2) : null,
					'event_end_at' => $item['type'] === 'event' ? now()->addWeeks(2)->addHours(3) : null,
				]
			);
		});
	}
}
