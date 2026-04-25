<?php

namespace App\Http\Requests;

use App\Support\HtmlSanitizer;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnouncementRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return $this->user()?->isAdmin() === true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
			'description' => 'required|string|max:20000',
			'start' => 'required|date|before_or_equal:end',
			'end' => 'required|date|after_or_equal:start',
		];
	}

	protected function prepareForValidation(): void
	{
		if ($this->has('description')) {
			$this->merge([
				'description' => app(HtmlSanitizer::class)->clean($this->input('description')),
			]);
		}
	}
}
