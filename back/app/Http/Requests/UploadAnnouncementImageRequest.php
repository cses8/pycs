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
		// Example: Check if the user can update the specific gallery
		// $gallery = $this->route('gallery'); // Get the gallery from the route model binding
		// return $this->user()->can('update', $gallery); // Assumes you have a AnnouncementPolicy

		return true; // Placeholder: Allow for now, implement real authorization
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
				'max:10240', // Maximum file size: 10240 KB = 10 MB (adjust as needed)
				// You could add dimension rules here too if needed:
				// 'dimensions:min_width=100,min_height=100,max_width=5000,max_height=5000',
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
			'files.*.max' => 'One of the images exceeds the maximum file size of 10MB.',
			// Add messages for dimension rules if you use them
		];
	}
}
