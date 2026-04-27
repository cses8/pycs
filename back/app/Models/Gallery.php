<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
	/** @use HasFactory<\Database\Factories\GalleryFactory> */
	use HasFactory;


	/**
	 * The attributes that are mass assignable.
	 *
	 * @var list<string>
	 */
	protected $fillable = [
		'school_year_id',
		'title',
		'description',
		'start',
		'end',
	];

	protected function casts(): array
	{
		return [
			'school_year_id' => 'integer',
		];
	}

	public function schoolYear(): BelongsTo
	{
		return $this->belongsTo(SchoolYear::class);
	}
}
