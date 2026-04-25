<?php

namespace Database\Factories;

use App\Models\SchoolUpdate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolUpdate>
 */
class SchoolUpdateFactory extends Factory
{
	protected $model = SchoolUpdate::class;

	public function definition(): array
	{
		$title = fake()->sentence(5);
		$type = fake()->randomElement(SchoolUpdate::TYPES);
		$publishedAt = fake()->dateTimeBetween('-45 days', '+15 days');

		return [
			'author_id' => User::factory(),
			'title' => $title,
			'slug' => SchoolUpdate::uniqueSlug($title),
			'summary' => fake()->paragraph(),
			'content' => '<p>' . fake()->paragraphs(3, true) . '</p>',
			'type' => $type,
			'category' => fake()->randomElement(['Campus Life', 'Academics', 'Admissions', 'Events']),
			'tags' => fake()->randomElements(['students', 'parents', 'events', 'academics', 'community'], 2),
			'status' => 'published',
			'published_at' => $publishedAt,
			'event_start_at' => $type === 'event' ? fake()->dateTimeBetween('now', '+30 days') : null,
			'event_end_at' => null,
			'featured_image' => null,
		];
	}
}
