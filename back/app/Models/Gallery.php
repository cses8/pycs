<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
		'title',
		'description',
		'start',
		'end',
	];
}
