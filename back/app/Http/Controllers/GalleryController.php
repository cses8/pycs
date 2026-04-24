<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadGalleryImageRequest;
use App\Models\Gallery;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use Carbon\Carbon;
use Exception;
use Log;
use Request;
use Response;
use Storage;
use Str;

class GalleryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		return Gallery::all()->sortByDesc('start')->values();
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreGalleryRequest $request)
	{
		// 1. Authorization is typically handled by StoreGalleryRequest's authorize() method
		//    or by middleware.

		// 2. Get validated data for the specified fields
		//    Ensure StoreGalleryRequest only validates title, description, start, end
		$validatedData = $request->validated();

		// --- Optional: Date Formatting ---
		// If your 'start' and 'end' dates might come in ISO format
		if (isset($validatedData['start'])) {
			$validatedData['start'] = Carbon::parse($validatedData['start'])->format('Y-m-d H:i:s');
		}
		if (isset($validatedData['end'])) {
			$validatedData['end'] = Carbon::parse($validatedData['end'])->format('Y-m-d H:i:s');
		}
		// --- End Date Formatting ---

		// 3. Prepare data for gallery creation
		$galleryData = $validatedData; // Contains only the validated fields

		// Optionally associate with the authenticated user
		// $galleryData['user_id'] = Auth::id(); // Or $request->user()->id;

		// 4. Create the Gallery model instance
		// Ensure 'title', 'description', 'start', 'end', 'user_id' (if used) are in $fillable
		$gallery = Gallery::create($galleryData);

		// 5. File Upload Logic Removed For Now

		// 6. Return a success response
		return response()->json([
			'message' => 'Gallery created successfully.',
			'gallery' => $gallery // Return the newly created gallery data
		], 201); // HTTP 201 Created status code
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Gallery $gallery)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Gallery $gallery)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateGalleryRequest $request, Gallery $gallery)
	{
		$validatedData = $request->validated(); // Get only validated fields

		// --- Date Formatting ---
		// Check if 'start' date is present in the validated data and format it
		if (isset($validatedData['start'])) {
			// Parse the incoming date string (Carbon handles ISO 8601)
			// and format it to MySQL's expected DATETIME format.
			$validatedData['start'] = Carbon::parse($validatedData['start'])->format('Y-m-d H:i:s');
		}

		// Check if 'end' date is present and format it
		if (isset($validatedData['end'])) {
			$validatedData['end'] = Carbon::parse($validatedData['end'])->format('Y-m-d H:i:s');
		}
		// --- End Date Formatting ---

		$gallery->update($validatedData); // Update the model in the database

		return response()->json([ // Send success response
			'message' => 'Gallery updated successfully.',
			'gallery' => $gallery
		], 200);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Gallery $gallery)
	{
		// Delete the gallery record
		try {
			$gallery->delete();
		} catch (Exception $e) {
			Log::error("Failed to delete gallery (ID: {$gallery->id}): " . $e->getMessage(), ['exception' => $e]);
			return response()->json(['message' => 'Failed to delete gallery.'], 500); // Internal Server Error
		}

		// And delete the associated files in the storage at storage/public/galleries/{gallery_id}
		$directoryPath = $gallery->file_path ?? 'galleries/' . $gallery->id;
		$disk = Storage::disk('public');
		if ($disk->exists($directoryPath)) {
			try {
				$disk->deleteDirectory($directoryPath);
				Log::info("Successfully deleted directory: {$directoryPath} for gallery {$gallery->id}");
			} catch (Exception $e) {
				Log::error("Failed to delete directory {$directoryPath} for gallery {$gallery->id}: " . $e->getMessage(), ['exception' => $e]);
			}
		} else {
			Log::warning("Directory {$directoryPath} does not exist for gallery {$gallery->id}, skipping deletion.");
		}

		return response()->json(['message' => 'Gallery deleted successfully, including associated files.'], 200); // OK
	}

	/**
	 * Handles the upload of one or more image files sent via multipart/form-data.
	 * Uses UploadGalleryImageRequest for validation and authorization.
	 * Expects files under the input name 'files'. Converts images to webp,
	 * renames existing files sequentially, saves new files, and updates the gallery's image_count.
	 *
	 * @param \App\Http\Requests\UploadGalleryImageRequest $request The validated request object.
	 * @param \App\Models\Gallery $gallery The gallery instance (via route model binding).
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function uploadImage(UploadGalleryImageRequest $request, Gallery $gallery) // <-- Uses Form Request
	{
		// 1. Authorization: Handled by UploadGalleryImageRequest->authorize()

		// 2. Check for GD extension dependency
		if (!extension_loaded('gd')) {
			Log::error('GD extension is not enabled. Image processing is required.');
			return response()->json(['message' => 'Server configuration error: Image processing library (GD) not available.'], 500); // Use 500 directly
		}

		// 3. Get Validated Files: Validation is already done by the Form Request!
		$uploadedFiles = $request->file('files'); // <-- Gets files using file('files')

		// --- Define Target Directory (using gallery's file_path) ---
		$directoryPath = $gallery->file_path ?? 'galleries/' . $gallery->id;
		$disk = Storage::disk('public'); // Use the public disk

		// --- Ensure Directory Exists ---
		try {
			if (!$disk->exists($directoryPath)) {
				$disk->makeDirectory($directoryPath);
				// Ensure the gallery record has the correct path saved if it was null
				if (is_null($gallery->file_path)) {
					$gallery->file_path = $directoryPath;
					// Save path now, count will be updated later
					$gallery->save();
				}
			}
		} catch (Exception $e) {
			Log::error("Failed to create directory: {$directoryPath}. Error: " . $e->getMessage());
			return response()->json(['message' => 'Server failed to prepare storage directory.'], 500); // Use 500 directly
		}

		// --- Rename Existing Files Sequentially (Done ONCE before processing new files) ---
		$nextNumber = 1; // Initialize the starting number for new files
		try {
			$existingFiles = collect($disk->files($directoryPath))
				->filter(fn($file) => strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'webp')
				->map(function ($file) {
					$baseName = pathinfo($file, PATHINFO_FILENAME);
					return ['path' => $file, 'number' => filter_var($baseName, FILTER_VALIDATE_INT)];
				})
				->filter(fn($item) => $item['number'] !== false)
				->sortBy('number')
				->values();

			foreach ($existingFiles as $fileInfo) {
				$oldPath = $fileInfo['path'];
				$newPath = $directoryPath . '/' . $nextNumber . '.webp';

				if ($oldPath !== $newPath) {
					Log::debug("Renaming {$oldPath} to {$newPath} for gallery {$gallery->id}");
					$disk->move($oldPath, $newPath);
				}
				$nextNumber++; // Increment for the next existing file (or the first new file)
			}
			// $nextNumber now holds the number for the *first* new file to be saved

		} catch (Exception $e) {
			Log::error("Error during renaming files in {$directoryPath} for gallery {$gallery->id}: " . $e->getMessage(), ['exception' => $e]);
			return response()->json(['message' => 'Error processing existing files in storage.'], 500); // Use 500 directly
		}

		// --- Process and Save Each Uploaded File ---
		$processedFiles = [];
		$errors = [];
		$successfullyUploadedCount = 0;

		foreach ($uploadedFiles as $index => $file) {
			$imageResource = null;
			$newFilePath = $directoryPath . '/' . $nextNumber . '.webp';

			try {
				// Get file content from the UploadedFile object
				$fileContent = $file->get();

				// --- Convert Image to WebP using GD ---
				$imageResource = @imagecreatefromstring($fileContent);
				if ($imageResource === false) {
					Log::warning("Failed to create image resource from uploaded file: {$file->getClientOriginalName()}. Gallery ID: {$gallery->id}");
					$errors[] = "File '{$file->getClientOriginalName()}' could not be processed as an image.";
					continue; // Skip to the next file
				}

				// --- Save the New Image as WebP ---
				ob_start();
				$success = imagewebp($imageResource);
				$webpContent = ob_get_clean();
				imagedestroy($imageResource); // Free memory

				if (!$success || empty($webpContent)) {
					throw new Exception('imagewebp conversion failed or produced empty content.');
				}

				$stored = $disk->put($newFilePath, $webpContent);

				if (!$stored) {
					Log::error("Failed to store converted webp file using Storage::put for path: {$newFilePath}");
					$errors[] = "Server failed to store the file '{$file->getClientOriginalName()}'.";
					continue; // Skip to the next file
				}

				// If successful:
				$processedFiles[] = $newFilePath;
				$successfullyUploadedCount++;
				$nextNumber++; // Increment number for the next file in this batch

			} catch (Exception $e) {
				if (isset($imageResource) && $imageResource instanceof GdImage) {
					if ($imageResource instanceof \GdImage || is_resource($imageResource)) {
						imagedestroy($imageResource);
					}
				}
				Log::error("Exception processing file '{$file->getClientOriginalName()}' for gallery {$gallery->id}: " . $e->getMessage(), ['exception' => $e]);
				$errors[] = "An error occurred processing file '{$file->getClientOriginalName()}'.";
				// Continue to next file
			}
		} // End loop through uploaded files

		// --- Update Gallery Record ---
		$finalImageCount = $nextNumber - 1; // The last successful number used
		try {
			$gallery->image_count = $finalImageCount;
			// Ensure file_path is saved if it was set earlier
			if ($gallery->isDirty('file_path')) { // Only save if it was changed
				$gallery->save();
			} else {
				// Only update image_count if file_path was already set
				$gallery->update(['image_count' => $finalImageCount]);
			}
		} catch (Exception $e) {
			Log::error("Failed to update gallery (ID: {$gallery->id}) with final image count: " . $e->getMessage(), ['exception' => $e]);
			$errors[] = 'Images uploaded but failed to update gallery details.';
			// Determine appropriate response status based on whether *any* files succeeded
			$status = $successfullyUploadedCount > 0 ? 207 : 500; // Use 207 or 500 directly
			return response()->json([
				'message' => 'Partial success: Some operations failed.',
				'processedFiles' => $processedFiles,
				'errors' => $errors,
				'gallery' => $gallery->refresh()
			], $status);
		}

		// --- Determine Final Response ---
		if (empty($errors)) {
			// All files processed successfully
			Log::info("{$successfullyUploadedCount} image(s) successfully processed and stored for Gallery ID: {$gallery->id}. Total images: {$finalImageCount}");
			return response()->json([
				'message' => 'Image(s) uploaded successfully.',
				'processedFiles' => $processedFiles,
				'imageCount' => $finalImageCount,
				'gallery' => $gallery->refresh()
			], 200); // Use 200 directly
		} else {
			// Some errors occurred
			Log::warning("Partial success uploading images for Gallery ID: {$gallery->id}. Success: {$successfullyUploadedCount}, Failures: " . count($errors));
			return response()->json([
				'message' => 'Upload complete with some errors.',
				'processedFiles' => $processedFiles, // List successfully processed files
				'errors' => $errors, // List errors for failed files
				'imageCount' => $finalImageCount,
				'gallery' => $gallery->refresh()
			], 207); // Use 207 directly
		}
	}

	/**
	 * Deletes a specific image file from a gallery's storage directory,
	 * renames remaining files sequentially, and updates the image count.
	 * Expects the base filename (e.g., '1', '2') as a route parameter.
	 *
	 * @param \App\Models\Gallery $gallery The gallery instance.
	 * @param string $filename The base name of the file to delete (e.g., '1', '2').
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteImage(Gallery $gallery, string $filename)
	{
		// 1. Authorization: Check if the user can update/delete from this gallery
		// Example Policy Check:
		// if (auth()->user()->cannot('update', $gallery)) {
		//     return response()->json(['message' => 'Unauthorized'], 403);
		// }

		// 2. Validate filename parameter (ensure it's numeric)
		if (!ctype_digit($filename) || (int) $filename <= 0) {
			return response()->json(['message' => 'Invalid filename format provided.'], 400); // Bad Request
		}

		// 3. Construct the target file path
		$directoryPath = $gallery->file_path ?? 'galleries/' . $gallery->id;
		$targetFilePath = $directoryPath . '/' . $filename . '.webp'; // Append .webp extension
		$disk = Storage::disk('public');

		// 4. Check if the file exists
		if (!$disk->exists($targetFilePath)) {
			Log::warning("Attempted to delete non-existent file: {$targetFilePath} for gallery {$gallery->id}");
			return response()->json(['message' => 'File not found.'], 404); // Not Found
		}

		// 5. Delete the file
		$deleted = false;
		try {
			$deleted = $disk->delete($targetFilePath);
			if (!$deleted) {
				// This might happen due to permissions or other filesystem issues
				Log::error("Failed to delete file from storage: {$targetFilePath}");
				return response()->json(['message' => 'Server failed to delete the specified file.'], 500);
			}
			Log::info("Successfully deleted file: {$targetFilePath}");
		} catch (Exception $e) {
			Log::error("Error deleting file {$targetFilePath}: " . $e->getMessage(), ['exception' => $e]);
			return response()->json(['message' => 'An error occurred while deleting the file.'], 500);
		}

		// 6. Rename Remaining Files Sequentially
		$nextNumber = 1;
		$finalImageCount = 0;
		try {
			// Get remaining .webp files, sort them by current numeric name
			$remainingFiles = collect($disk->files($directoryPath))
				->filter(fn($file) => strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'webp')
				->map(function ($file) {
					$baseName = pathinfo($file, PATHINFO_FILENAME);
					return ['path' => $file, 'number' => filter_var($baseName, FILTER_VALIDATE_INT)];
				})
				->filter(fn($item) => $item['number'] !== false)
				->sortBy('number')
				->values();

			foreach ($remainingFiles as $fileInfo) {
				$oldPath = $fileInfo['path'];
				$newPath = $directoryPath . '/' . $nextNumber . '.webp';

				if ($oldPath !== $newPath) {
					Log::debug("Renaming after delete: {$oldPath} to {$newPath} for gallery {$gallery->id}");
					$disk->move($oldPath, $newPath);
				}
				$nextNumber++; // Increment for the next file
			}
			$finalImageCount = $nextNumber - 1; // The last number used after renaming

		} catch (Exception $e) {
			Log::error("Error during renaming files after delete in {$directoryPath} for gallery {$gallery->id}: " . $e->getMessage(), ['exception' => $e]);
			// File was deleted, but renaming failed. Update count anyway? Or return error?
			// Let's update count but report a partial success/warning.
			$gallery->image_count = count($disk->files($directoryPath)); // Update with actual count
			$gallery->save();
			return response()->json([
				'message' => 'File deleted, but an error occurred while reorganizing remaining files.',
				'imageCount' => $gallery->image_count,
				'gallery' => $gallery->refresh()
			], 500); // Internal Server Error or maybe 207 Multi-Status
		}

		// 7. Update Gallery Record with the new count
		try {
			$gallery->image_count = $finalImageCount;
			$gallery->save();
		} catch (Exception $e) {
			Log::error("Failed to update gallery (ID: {$gallery->id}) with image count after delete: " . $e->getMessage(), ['exception' => $e]);
			// File deleted & renamed, but DB update failed. Critical state?
			return response()->json(['message' => 'File deleted but failed to update gallery details.'], 500);
		}

		// 8. Return Success Response
		return response()->json([
			'message' => 'Image deleted successfully.',
			'imageCount' => $finalImageCount,
			'gallery' => $gallery->refresh()
		], 200); // OK
	}
}
