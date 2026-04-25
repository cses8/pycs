<?php

namespace App\Http\Requests;

use App\Models\SchoolUpdate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSchoolUpdateRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return $this->user() !== null;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'title' => ['required', 'string', 'max:255'],
			'summary' => ['nullable', 'string', 'max:1000'],
			'content' => ['required', 'string'],
			'type' => ['required', 'string', Rule::in(SchoolUpdate::TYPES)],
			'category' => ['nullable', 'string', 'max:100'],
			'tags' => ['nullable', 'array'],
			'tags.*' => ['string', 'max:40'],
			'status' => ['required', 'string', Rule::in(SchoolUpdate::STATUSES)],
			'published_at' => ['nullable', 'date'],
			'event_start_at' => ['nullable', 'date'],
			'event_end_at' => ['nullable', 'date', 'after_or_equal:event_start_at'],
		];
	}
}
