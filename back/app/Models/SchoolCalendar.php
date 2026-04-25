<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolCalendar extends Model
{
    /** @use HasFactory<\Database\Factories\SchoolCalendarFactory> */
    use HasFactory;

    protected $fillable = [
        'school_year_id',
        'start',
        'end',
        'image',
        'title',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'school_year_id' => 'integer',
            'start' => 'date:Y-m-d',
            'end' => 'date:Y-m-d',
        ];
    }
}
