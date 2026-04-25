<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SchoolUpdate extends Model
{
	/** @use HasFactory<\Database\Factories\SchoolUpdateFactory> */
	use HasFactory;

	public const TYPES = ['news', 'announcement', 'blog', 'event'];
	public const STATUSES = ['draft', 'scheduled', 'published', 'archived'];

	protected $fillable = [
		'author_id',
		'title',
		'slug',
		'summary',
		'content',
		'type',
		'category',
		'tags',
		'status',
		'published_at',
		'event_start_at',
		'event_end_at',
		'featured_image',
	];

	protected $casts = [
		'tags' => 'array',
		'published_at' => 'datetime',
		'event_start_at' => 'datetime',
		'event_end_at' => 'datetime',
	];

	public function author(): BelongsTo
	{
		return $this->belongsTo(User::class, 'author_id');
	}

	public function getRouteKeyName(): string
	{
		return 'slug';
	}

	public static function uniqueSlug(string $title, ?int $ignoreId = null): string
	{
		$baseSlug = Str::slug($title) ?: Str::lower(Str::random(8));
		$slug = $baseSlug;
		$index = 2;

		while (
			static::query()
				->where('slug', $slug)
				->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
				->exists()
		) {
			$slug = "{$baseSlug}-{$index}";
			$index++;
		}

		return $slug;
	}
}
