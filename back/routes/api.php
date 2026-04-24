<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SchoolCalendarController;
use App\Http\Controllers\SchoolYearController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/school-years', [SchoolYearController::class, 'index']); // Verify this line exists and is correct
Route::get('/galleries', [GalleryController::class, 'index']); // Verify this line exists and is correct
Route::get('/announcements/active', [AnnouncementController::class, 'getActiveAnnouncements']); // Verify this line exists and is correct
Route::get('/announcements', [AnnouncementController::class, 'index']); // Verify this line exists and is correct
Route::get('/school-calendars', [SchoolCalendarController::class, 'index']); // Verify this line exists and is correct



// Group routes that require authentication (e.g., using Sanctum)
Route::middleware('auth:sanctum')->group(function () {

	Route::get('/user', action: function (Request $request) {
		return $request->user();
	});


	// PUT/PATCH /api/galleries/{gallery} - Update an existing gallery
	// Using PUT for full replacement, PATCH for partial updates
	// Route model binding is used here as well
	// DELETE /api/galleries/{gallery} - Delete an existing gallery
	// Route model binding is used here
	// POST /api/galleries - Create a new gallery
	Route::put('/galleries/{gallery}', [GalleryController::class, 'update'])->name('galleries.update');
	Route::post('/galleries', [GalleryController::class, 'store'])->name('galleries.store');
	Route::patch('/galleries/{gallery}', [GalleryController::class, 'update']); // Often points to the same update method
	Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');

	Route::post('/upload/gallery/{gallery}', [GalleryController::class, 'uploadImage'])->name('galleries.uploadImage');
	Route::delete('/upload/gallery/{gallery}/{filename}', [GalleryController::class, 'deleteImage'])->name('galleries.deleteImage');

	Route::put('/announcements/{gallery}', [AnnouncementController::class, 'update'])->name('announcements.update');
	Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
	Route::patch('/announcements/{gallery}', [AnnouncementController::class, 'update']); // Often points to the same update method
	Route::delete('/announcements/{gallery}', [AnnouncementController::class, 'destroy']);

	Route::post('/upload/announcements/{announcement}', [AnnouncementController::class, 'uploadImage'])->name('announcements.uploadImage');
});
