<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadAnnouncementImageRequest;
use App\Models\Announcement;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use Carbon\Carbon;
use Exception;
use Log;
use Request;
use Storage;

class AnnouncementController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		return Announcement::all()->sortByDesc('start')->values();
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
	public function store(StoreAnnouncementRequest $request)
	{
		// 1. Authorization is typically handled by StoreAnnouncementRequest's authorize() method
		//    or by middleware.

		// 2. Get validated data for the specified fields
		//    Ensure StoreAnnouncementRequest only validates title, description, start, end
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

		// 3. Prepare data for announcement creation
		$announcementData = $validatedData; // Contains only the validated fields

		// Optionally associate with the authenticated user
		// $announcementData['user_id'] = Auth::id(); // Or $request->user()->id;

		// 4. Create the Announcement model instance
		// Ensure 'title', 'description', 'start', 'end', 'user_id' (if used) are in $fillable
		$announcement = Announcement::create($announcementData);

		// 5. File Upload Logic Removed For Now

		// 6. Return a success response
		return response()->json([
			'message' => 'Announcement created successfully.',
			'announcement' => $announcement // Return the newly created announcement data
		], 201); // HTTP 201 Created status code
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Announcement $announcement)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Announcement $announcement)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateAnnouncementRequest $request)
	{
		$validatedData = $request->validated(); // Get only validated fields

		$announcement = Announcement::find($validatedData['id']);
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

		Log::info(json_encode($announcement));

		$announcement->update($validatedData); // Update the model in the database

		return response()->json([ // Send success response
			'message' => 'Announcement updated successfully.',
			'announcement' => $announcement
		], 200);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id)
	{
		$announcement = Announcement::find($id);
		// 1. Authorization Check (Implement proper policy or Gate check)
		// Example:
		// if (auth()->user()->cannot('delete', $announcement)) {
		//     return response()->json(['message' => 'Unauthorized to delete this announcement.'], 403);
		// }

		// 2. Define the directory path
		// Assuming you store files directly under announcement/{id}
		// Adjust if you have a 'file_path' column storing the path differently
		$directoryPath = 'announcement/' . $announcement->id;
		$disk = Storage::disk('public'); // Use the public disk

		// 3. Delete the database record first
		// This prevents orphaned files if the DB deletion fails for some reason.
		try {
			$announcement->delete();
			Log::info(message: "Successfully deleted announcement record ID: {$announcement->id}");
		} catch (Exception $e) {
			Log::error("Failed to delete announcement record ID {$announcement->id}: " . $e->getMessage(), ['exception' => $e]);
			// If DB deletion fails, we definitely shouldn't delete the files.
			return response()->json(['message' => 'Failed to delete announcement record from database.'], 500);
		}

		// 4. Delete the associated directory from storage if it exists
		if ($disk->exists($directoryPath)) {
			try {
				// Use deleteDirectory for recursive deletion of the folder and its contents
				$deleted = $disk->deleteDirectory($directoryPath);
				if ($deleted) {
					Log::info("Successfully deleted associated storage directory: {$directoryPath}");
				} else {
					// This might happen due to permissions or other filesystem issues
					Log::warning("Storage::deleteDirectory failed for path: {$directoryPath}, possibly due to permissions or it being empty/already gone.");
					// Depending on requirements, you might still return success here as the DB record is gone,
					// or return a specific warning/error. Let's return success but log the warning.
				}
			} catch (Exception $e) {
				// Log an error if directory deletion fails. The DB record is already gone.
				Log::error("Error deleting directory from storage after deleting DB record: {$directoryPath}. Error: " . $e->getMessage(), ['exception' => $e]);
				// Consider notifying admin about potential orphaned directory.
				// Still return success as the primary resource (DB record) is gone.
			}
		} else {
			Log::info("Storage directory not found, skipping deletion: {$directoryPath}");
		}

		// 5. Return Success Response
		return response()->json(['message' => 'Announcement deleted successfully.'], 200); // OK
	}


	/**
	 * Fetch announcements that are currently active.
	 * An announcement is active if the current time is between its start and end datetime.
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getActiveAnnouncements(Request $request)
	{
		// Get the current date and time
		// Consider the server's timezone or specify one if needed, e.g., Carbon::now('Asia/Manila')
		$now = Carbon::now();

		Log::info("Fetching active announcements based on current time: " . $now->toDateTimeString());

		try {
			// Query the Announcement model
			$activeAnnouncements = Announcement::where('start', '<=', $now) // Start date is on or before now
				->where('end', '>=', $now)     // End date is on or after now
				->orderBy('start', 'desc')    // Optional: Order by start date descending (newest first)
				// ->with('schoolYear')       // Optional: Eager load related school year if needed
				->get();

			return response()->json($activeAnnouncements, 200); // Return the results with HTTP 200 OK

		} catch (\Exception $e) {
			Log::error("Error fetching active announcements: " . $e->getMessage(), ['exception' => $e]);
			return response()->json(['message' => 'Failed to retrieve active announcements.'], 500); // Internal Server Error
		}
	}

	/**
	 * Handles the upload of one or more image files sent via multipart/form-data.
	 * Uses UploadAnnouncementImageRequest for validation and authorization.
	 * Expects files under the input name 'files'. Converts images to webp,
	 * renames existing files sequentially, saves new files, and updates the announcement's image_count.
	 *
	 * @param \App\Http\Requests\UploadAnnouncementImageRequest $request The validated request object.
	 * @param \App\Models\Announcement $announcement The announcement instance (via route model binding).
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function uploadImage(UploadAnnouncementImageRequest $request, Announcement $announcement) // <-- Uses Form Request
	{
		// 1. Authorization: Handled by UploadAnnouncementImageRequest->authorize()

		// 2. Check for GD extension dependency
		if (!extension_loaded('gd')) {
			Log::error('GD extension is not enabled. Image processing is required.');
			return response()->json(['message' => 'Server configuration error: Image processing library (GD) not available.'], 500);
		}

		// 3. Get Validated Files: Validation is done by the Form Request (expects an array).
		// The input name must be 'image'.
		$uploadedFiles = $request->file('files'); // This is now an array of UploadedFile objects

		// --- Define Target Path (same path for all files in the loop, causing overwrite) ---
		$directoryPath = 'announcements/' . $announcement->id;
		$filename = $announcement->id . '.webp'; // Filename is the announcement ID + .webp extension
		$targetFilePath = $directoryPath . '/' . $filename; // <-- Single target path
		$disk = Storage::disk('public'); // Use the public disk

		// --- Ensure Directory Exists ---
		try {
			$disk->makeDirectory($directoryPath);
		} catch (Exception $e) {
			Log::error("Failed to create directory: {$directoryPath}. Error: " . $e->getMessage());
			return response()->json(['message' => 'Server failed to prepare storage directory.'], 500);
		}

		// --- Process and Save Each Uploaded File (Overwriting the previous one) ---
		$errors = [];
		$lastProcessedFilePath = null;

		foreach ($uploadedFiles as $index => $uploadedFile) {
			$imageResource = null;
			$originalFilename = $uploadedFile->getClientOriginalName(); // Get original name for logging

			try {
				// Get file content from the UploadedFile object
				$fileContent = $uploadedFile->get();

				// --- Convert Image to WebP using GD ---
				$imageResource = @imagecreatefromstring($fileContent);
				if ($imageResource === false) {
					Log::warning("Failed to create image resource from uploaded file: {$originalFilename}. Announcement ID: {$announcement->id}");
					$errors[$originalFilename] = "File could not be processed as an image."; // Store error by original filename
					continue; // Skip to the next file
				}

				// --- Save the New Image as WebP (Overwrites if exists) ---
				ob_start();
				$success = imagewebp($imageResource); // Convert and output to buffer
				$webpContent = ob_get_clean(); // Get buffer content
				imagedestroy($imageResource); // Free memory

				if (!$success || empty($webpContent)) {
					throw new Exception('imagewebp conversion failed or produced empty content.');
				}

				// Store the converted content, overwriting $targetFilePath
				$stored = $disk->put($targetFilePath, $webpContent);

				if (!$stored) {
					Log::error("Failed to store converted webp file using Storage::put for path: {$targetFilePath}. Check filesystem permissions.");
					$errors[$originalFilename] = 'Server failed to store the uploaded file.';
					// If storage fails for one, maybe stop processing others? Or continue? Let's continue.
					continue; // Skip to next file
				}

				// Track the path of the last successfully processed file
				$lastProcessedFilePath = $targetFilePath;
				Log::info("Processed '{$originalFilename}' and saved/overwrote: {$targetFilePath}");


			} catch (Exception $e) {
				// Clean up GD resource if it exists
				if (isset($imageResource) && ($imageResource instanceof GdImage || is_resource($imageResource))) {
					imagedestroy($imageResource);
				}
				Log::error("Exception processing file '{$originalFilename}' for announcement {$announcement->id}: " . $e->getMessage(), ['exception' => $e]);
				$errors[$originalFilename] = 'An error occurred during file conversion or storage.';
				// Continue to next file
			}
		} // End foreach loop

		// --- Determine Final Response ---
		if ($lastProcessedFilePath && empty($errors)) {
			// At least one file was processed successfully, and no errors occurred for any file.
			return response()->json([
				'message' => 'Announcement image(s) processed successfully. Last image saved.',
				'filePath' => $lastProcessedFilePath // Path of the final saved image
			], 200); // OK
		} elseif ($lastProcessedFilePath && !empty($errors)) {
			// At least one file succeeded, but others failed.
			return response()->json([
				'message' => 'Processing complete with some errors. Last successful image saved.',
				'filePath' => $lastProcessedFilePath,
				'errors' => $errors // Report errors for specific original filenames
			], 207); // Multi-Status
		} elseif (empty($lastProcessedFilePath) && !empty($errors)) {
			// All files failed to process or save.
			return response()->json([
				'message' => 'Failed to process or save any uploaded images.',
				'errors' => $errors
			], 422); // Unprocessable Entity (as likely input file issues) or 500 if server errors dominated
		} else {
			// Should not happen if validation requires at least one file, but handle edge case.
			return response()->json(['message' => 'No image files were processed.'], 400); // Bad Request
		}
	}
}
