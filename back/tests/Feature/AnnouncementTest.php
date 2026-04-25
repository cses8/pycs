<?php

use App\Models\Announcement;
use App\Models\User;
use App\Support\HtmlSanitizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

function announcementPayload(array $overrides = []): array
{
	return array_merge([
		'title' => 'Campus Advisory',
		'description' => 'Classes resume on the regular schedule.',
		'start' => '2026-05-01 08:00:00',
		'end' => '2026-05-02 17:00:00',
	], $overrides);
}

test('fortify public registration is disabled', function () {
	$this->postJson('/register', [
		'name' => 'Unapproved User',
		'email' => 'unapproved@example.com',
		'password' => 'password',
		'password_confirmation' => 'password',
	])->assertNotFound();
});

test('announcement update uses the route model instead of a request body id', function () {
	Sanctum::actingAs(User::factory()->admin()->create());

	$announcement = Announcement::query()->create(announcementPayload());
	$otherAnnouncement = Announcement::query()->create(announcementPayload([
		'title' => 'Different Advisory',
		'start' => '2026-05-03 08:00:00',
		'end' => '2026-05-04 17:00:00',
	]));

	$this->putJson("/api/announcements/{$announcement->id}", announcementPayload([
		'id' => $otherAnnouncement->id,
		'title' => 'Updated Campus Advisory',
	]))
		->assertOk()
		->assertJsonPath('announcement.id', $announcement->id)
		->assertJsonPath('announcement.title', 'Updated Campus Advisory');

	expect($announcement->fresh()->title)->toBe('Updated Campus Advisory')
		->and($otherAnnouncement->fresh()->title)->toBe('Different Advisory');
});

test('announcement mutation routes bind the announcement parameter', function () {
	Sanctum::actingAs(User::factory()->admin()->create());

	$announcement = Announcement::query()->create(announcementPayload());

	$updateRoute = Route::getRoutes()->getByName('announcements.update');
	$destroyRoute = Route::getRoutes()->getByName('announcements.destroy');

	expect($updateRoute?->parameterNames())->toBe(['announcement'])
		->and($destroyRoute?->parameterNames())->toBe(['announcement'])
		->and(route('announcements.update', ['announcement' => $announcement], false))
		->toBe("/api/announcements/{$announcement->id}")
		->and(route('announcements.destroy', ['announcement' => $announcement], false))
		->toBe("/api/announcements/{$announcement->id}");

	$this->putJson(route('announcements.update', ['announcement' => $announcement], false), announcementPayload([
		'title' => 'Route Bound Advisory',
	]))
		->assertOk()
		->assertJsonPath('announcement.id', $announcement->id)
		->assertJsonPath('announcement.title', 'Route Bound Advisory');

	$this->deleteJson(route('announcements.destroy', ['announcement' => $announcement], false))
		->assertOk();

	expect(Announcement::query()->find($announcement->id))->toBeNull();
});

test('non admin users cannot mutate announcements', function () {
	Sanctum::actingAs(User::factory()->create());
	$announcement = Announcement::query()->create(announcementPayload());

	$this->postJson('/api/announcements', announcementPayload())->assertForbidden();
	$this->putJson("/api/announcements/{$announcement->id}", announcementPayload([
		'title' => 'Blocked Advisory',
	]))->assertForbidden();
	$this->deleteJson("/api/announcements/{$announcement->id}")->assertForbidden();
});

test('announcement rich text is sanitized before storage', function () {
	Sanctum::actingAs(User::factory()->admin()->create());

	$this->postJson('/api/announcements', announcementPayload([
		'description' => '<p>Allowed</p><script>alert("xss")</script><a href="javascript:alert(1)">bad</a>',
	]))->assertCreated();

	$description = Announcement::query()->firstOrFail()->description;

	expect($description)->toContain('<p>Allowed</p>')
		->not->toContain('<script')
		->not->toContain('javascript:');
});

test('html sanitizer allows the same editor elements and classes as the frontend renderer', function () {
	$cleanHtml = app(HtmlSanitizer::class)->clean(
		'<p class="ql-align-center unsafe" onclick="alert(1)">Aligned</p>'
		. '<span class="ql-size-large">Large text</span>'
		. '<a href="/school-updates" target="_top">Relative link</a>'
		. '<a href="ftp://example.com/file">Blocked link</a>'
	);

	expect($cleanHtml)
		->toContain('<p class="ql-align-center">Aligned</p>')
		->toContain('<span class="ql-size-large">Large text</span>')
		->toContain('href="/school-updates"')
		->toContain('target="_top"')
		->not->toContain('unsafe')
		->not->toContain('onclick')
		->not->toContain('ftp://');
});

test('missing announcement routes return not found', function () {
	Sanctum::actingAs(User::factory()->admin()->create());

	$this->putJson('/api/announcements/999999', announcementPayload([
		'title' => 'Missing Advisory',
	]))->assertNotFound();

	$this->deleteJson('/api/announcements/999999')->assertNotFound();
});
