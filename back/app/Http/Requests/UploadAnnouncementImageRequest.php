<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule; // Import ValidationRule interface

class UploadAnnouncementImageRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * You should implement proper authorization logic here,
	 * e.g., checking if the authenticated user owns the gallery
	 * or has permission to upload to it.
	 *
	 * For now, returning true allows any authenticated user (if middleware is applied)
	 * or any user (if no auth middleware is applied).
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return $this->user()?->isAdmin() === true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * Validates a single image file uploaded under the 'image' key.
	 *
	 * @return array<string, ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			// Ensure 'files' input exists and is an array (even if only one file is uploaded)
			'files' => 'required|array',

			// Validate each item within the 'files' array
			'files.*' => [
				'required', // Each item in the array must be present
				'file',     // Each item must be a successfully uploaded file
				'image',    // Each item must be an image (jpeg, png, bmp, gif, svg, or webp)
				'mimes:jpeg,png,gif,webp', // Restrict to specific image MIME types GD can handle
				'max:5120',
				'dimensions:min_width=1,min_height=1,max_width=4000,max_height=4000',
			],
		];
	}

	/**
	 * Get custom messages for validator errors.
	 *
	 * @return array<string, string>
	 */
	public function messages(): array
	{
		return [
			'files.required' => 'Please select at least one image file to upload.',
			'files.array' => 'The files must be submitted in the correct format.',
			'files.*.required' => 'An error occurred with one of the files. Please try again.',
			'files.*.file' => 'One of the uploads was not a valid file.',
			'files.*.image' => 'One of the files is not a valid image.',
			'files.*.mimes' => 'Only JPEG, PNG, GIF, and WEBP images are allowed.',
			'files.*.max' => 'One of the images exceeds the maximum file size of 5MB.',
			'files.*.dimensions' => 'One of the images exceeds the maximum dimensions of 4000 by 4000 pixels.',
		];
	}
}
