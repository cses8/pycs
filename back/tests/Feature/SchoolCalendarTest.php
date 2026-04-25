<?php

use App\Models\SchoolCalendar;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

function schoolCalendarPayload(array $overrides = []): array
{
	$schoolYear = SchoolYear::query()->first() ?? SchoolYear::query()->create([
		'description' => '2026-2027',
	]);

	return array_merge([
		'school_year_id' => $schoolYear->id,
		'start' => '2026-06-01',
		'end' => '2026-06-03',
		'image' => '/images/school_calendar.webp',
		'title' => 'Opening Week',
		'description' => 'Students return for the first week of classes.',
	], $overrides);
}

test('public users can read school calendar events', function () {
	SchoolCalendar::query()->create(schoolCalendarPayload([
		'title' => 'Foundation Day',
	]));

	$this->getJson('/api/school-calendars')
		->assertOk()
		->assertJsonCount(1)
		->assertJsonPath('0.title', 'Foundation Day');
});

test('authenticated users can manage school calendar events', function () {
	Sanctum::actingAs(User::factory()->create());

	$createResponse = $this->postJson('/api/school-calendars', schoolCalendarPayload())
		->assertCreated()
		->assertJsonPath('schoolCalendar.title', 'Opening Week');

	$schoolCalendarId = $createResponse->json('schoolCalendar.id');

	$this->putJson("/api/school-calendars/{$schoolCalendarId}", schoolCalendarPayload([
		'title' => 'Updated Opening Week',
		'start' => '2026-06-02',
		'end' => '2026-06-04',
		'image' => '',
	]))
		->assertOk()
		->assertJsonPath('schoolCalendar.title', 'Updated Opening Week')
		->assertJsonPath('schoolCalendar.image', '/images/school_calendar.webp');

	$this->deleteJson("/api/school-calendars/{$schoolCalendarId}")
		->assertOk();

	expect(SchoolCalendar::query()->count())->toBe(0);
});

test('unauthenticated users cannot mutate school calendar events', function () {
	$schoolCalendar = SchoolCalendar::query()->create(schoolCalendarPayload());

	$this->postJson('/api/school-calendars', schoolCalendarPayload())->assertUnauthorized();
	$this->putJson("/api/school-calendars/{$schoolCalendar->id}", schoolCalendarPayload())->assertUnauthorized();
	$this->deleteJson("/api/school-calendars/{$schoolCalendar->id}")->assertUnauthorized();
});

test('school calendar validation rejects invalid payloads', function () {
	Sanctum::actingAs(User::factory()->create());

	$this->postJson('/api/school-calendars', schoolCalendarPayload([
		'title' => '',
		'start' => '2026-06-05',
		'end' => '2026-06-01',
		'description' => '',
	]))
		->assertUnprocessable()
		->assertJsonValidationErrors(['title', 'start', 'end', 'description']);
});

test('school years with calendar records cannot be deleted', function () {
	Sanctum::actingAs(User::factory()->create());

	$schoolYear = SchoolYear::query()->create([
		'description' => '2027-2028',
	]);

	SchoolCalendar::query()->create(schoolCalendarPayload([
		'school_year_id' => $schoolYear->id,
	]));

	$this->deleteJson("/api/school-years/{$schoolYear->id}")
		->assertConflict()
		->assertJsonPath('message', 'This school year is already used by school calendar records and cannot be deleted.');

	expect(SchoolYear::query()->whereKey($schoolYear->id)->exists())->toBeTrue();
});
