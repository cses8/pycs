<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SchoolCalendarController;
use App\Http\Controllers\SchoolUpdateController;
use App\Http\Controllers\SchoolYearController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/school-years', [SchoolYearController::class, 'index']);
Route::get('/galleries', [GalleryController::class, 'index']);
Route::get('/announcements/active', [AnnouncementController::class, 'getActiveAnnouncements']);
Route::get('/announcements', [AnnouncementController::class, 'index']);
Route::get('/school-calendars', [SchoolCalendarController::class, 'index']);
Route::get('/school-updates', [SchoolUpdateController::class, 'index'])->name('school-updates.index');

Route::middleware('auth:sanctum')->group(function () {
	Route::get('/user', function (Request $request) {
		return $request->user();
	});

	Route::middleware(['admin', 'throttle:api-writes'])->group(function () {
		Route::put('/galleries/{gallery}', [GalleryController::class, 'update'])->name('galleries.update');
		Route::post('/galleries', [GalleryController::class, 'store'])->name('galleries.store');
		Route::patch('/galleries/{gallery}', [GalleryController::class, 'update']);
		Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');
		Route::post('/upload/gallery/{gallery}', [GalleryController::class, 'uploadImage'])->middleware('throttle:api-uploads')->name('galleries.uploadImage');
		Route::delete('/upload/gallery/{gallery}/{filename}', [GalleryController::class, 'deleteImage'])->middleware('throttle:api-uploads')->name('galleries.deleteImage');

		Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
		Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
		Route::patch('/announcements/{announcement}', [AnnouncementController::class, 'update']);
		Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
		Route::post('/upload/announcements/{announcement}', [AnnouncementController::class, 'uploadImage'])->middleware('throttle:api-uploads')->name('announcements.uploadImage');

		Route::put('/school-years/{schoolYear}', [SchoolYearController::class, 'update'])->name('school-years.update');
		Route::post('/school-years', [SchoolYearController::class, 'store'])->name('school-years.store');
		Route::patch('/school-years/{schoolYear}', [SchoolYearController::class, 'update']);
		Route::delete('/school-years/{schoolYear}', [SchoolYearController::class, 'destroy'])->name('school-years.destroy');

		Route::post('/school-calendars', [SchoolCalendarController::class, 'store'])->name('school-calendars.store');
		Route::put('/school-calendars/{schoolCalendar}', [SchoolCalendarController::class, 'update'])->name('school-calendars.update');
		Route::patch('/school-calendars/{schoolCalendar}', [SchoolCalendarController::class, 'update']);
		Route::delete('/school-calendars/{schoolCalendar}', [SchoolCalendarController::class, 'destroy'])->name('school-calendars.destroy');

		Route::get('/school-updates/manage', [SchoolUpdateController::class, 'manage'])->name('school-updates.manage');
		Route::post('/school-updates', [SchoolUpdateController::class, 'store'])->name('school-updates.store');
		Route::put('/school-updates/{schoolUpdate:id}', [SchoolUpdateController::class, 'update'])->name('school-updates.update');
		Route::patch('/school-updates/{schoolUpdate:id}', [SchoolUpdateController::class, 'update']);
		Route::delete('/school-updates/{schoolUpdate:id}', [SchoolUpdateController::class, 'destroy'])->name('school-updates.destroy');
		Route::post('/upload/school-updates/{schoolUpdate:id}/featured-image', [SchoolUpdateController::class, 'uploadFeaturedImage'])->middleware('throttle:api-uploads')->name('school-updates.uploadFeaturedImage');
		Route::post('/upload/school-updates/{schoolUpdate:id}/content-image', [SchoolUpdateController::class, 'uploadContentImage'])->middleware('throttle:api-uploads')->name('school-updates.uploadContentImage');
	});
});

Route::get('/school-updates/{schoolUpdate:slug}', [SchoolUpdateController::class, 'show'])->name('school-updates.show');
