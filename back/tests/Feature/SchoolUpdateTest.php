<?php

use App\Models\SchoolUpdate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

function schoolUpdatePayload(array $overrides = []): array
{
	return array_merge([
		'title' => 'Campus Science Fair Opens',
		'summary' => 'Students present research projects to the school community.',
		'content' => '<p>Students from different grade levels presented their projects.</p>',
		'type' => 'news',
		'category' => 'Campus Life',
		'tags' => ['students', 'science'],
		'status' => 'published',
		'published_at' => now()->subDay()->toISOString(),
		'event_start_at' => null,
		'event_end_at' => null,
	], $overrides);
}

test('public school updates only include currently published content', function () {
	SchoolUpdate::factory()->create([
		'title' => 'Published Update',
		'status' => 'published',
		'published_at' => now()->subDay(),
	]);
	SchoolUpdate::factory()->create([
		'title' => 'Draft Update',
		'status' => 'draft',
		'published_at' => null,
	]);
	SchoolUpdate::factory()->create([
		'title' => 'Future Update',
		'status' => 'published',
		'published_at' => now()->addDay(),
	]);

	$this->getJson('/api/school-updates')
		->assertOk()
		->assertJsonCount(1, 'data')
		->assertJsonPath('data.0.title', 'Published Update');
});

test('authenticated users can manage school updates', function () {
$user = User::factory()->admin()->create();
	Sanctum::actingAs($user);

	$createResponse = $this->postJson('/api/school-updates', schoolUpdatePayload())
		->assertCreated()
		->assertJsonPath('schoolUpdate.title', 'Campus Science Fair Opens');

	$schoolUpdateId = $createResponse->json('schoolUpdate.id');

	$this->putJson("/api/school-updates/{$schoolUpdateId}", schoolUpdatePayload([
		'title' => 'Updated Science Fair',
		'status' => 'draft',
		'published_at' => null,
	]))
		->assertOk()
		->assertJsonPath('schoolUpdate.title', 'Updated Science Fair')
		->assertJsonPath('schoolUpdate.status', 'draft');

	$this->getJson('/api/school-updates/manage?status=draft')
		->assertOk()
		->assertJsonCount(1, 'data')
		->assertJsonPath('data.0.title', 'Updated Science Fair');

	$this->deleteJson("/api/school-updates/{$schoolUpdateId}")
		->assertOk();

	expect(SchoolUpdate::query()->count())->toBe(0);
});

test('unauthenticated users cannot mutate school updates', function () {
	$schoolUpdate = SchoolUpdate::factory()->create();

	$this->postJson('/api/school-updates', schoolUpdatePayload())->assertUnauthorized();
	$this->putJson("/api/school-updates/{$schoolUpdate->id}", schoolUpdatePayload())->assertUnauthorized();
	$this->deleteJson("/api/school-updates/{$schoolUpdate->id}")->assertUnauthorized();
});

test('school update validation rejects invalid payloads', function () {
	Sanctum::actingAs(User::factory()->admin()->create());

	$this->postJson('/api/school-updates', schoolUpdatePayload([
		'title' => '',
		'type' => 'memo',
		'status' => 'published',
		'event_start_at' => now()->addDay()->toISOString(),
		'event_end_at' => now()->subDay()->toISOString(),
	]))
		->assertUnprocessable()
		->assertJsonValidationErrors(['title', 'type', 'event_end_at']);
});

test('school updates support search type category and tag filtering', function () {
	SchoolUpdate::factory()->create([
		'title' => 'Robotics Club Wins',
		'type' => 'news',
		'category' => 'Academics',
		'tags' => ['robotics', 'students'],
		'status' => 'published',
		'published_at' => now()->subDay(),
	]);
	SchoolUpdate::factory()->create([
		'title' => 'Family Day Schedule',
		'type' => 'event',
		'category' => 'Events',
		'tags' => ['parents'],
		'status' => 'published',
		'published_at' => now()->subDay(),
	]);

	$this->getJson('/api/school-updates?search=robotics&type=news&category=Academics&tag=robotics')
		->assertOk()
		->assertJsonCount(1, 'data')
		->assertJsonPath('data.0.title', 'Robotics Club Wins');
});

test('school update slugs remain unique', function () {
	Sanctum::actingAs(User::factory()->admin()->create());

	$this->postJson('/api/school-updates', schoolUpdatePayload(['title' => 'Shared Title']))->assertCreated();
	$this->postJson('/api/school-updates', schoolUpdatePayload(['title' => 'Shared Title']))->assertCreated();

	expect(SchoolUpdate::query()->pluck('slug')->all())->toBe(['shared-title', 'shared-title-2']);
});

test('authenticated users can upload featured images', function () {
	Storage::fake('public');
	Sanctum::actingAs(User::factory()->admin()->create());
	$schoolUpdate = SchoolUpdate::factory()->create();

	$response = $this->postJson("/api/upload/school-updates/{$schoolUpdate->id}/featured-image", [
		'file' => UploadedFile::fake()->image('campus.jpg', 1200, 800),
	])
		->assertOk()
		->assertJsonPath('message', 'Featured image uploaded successfully.');

	Storage::disk('public')->assertExists($response->json('path'));
	expect($schoolUpdate->refresh()->featured_image)->toBe($response->json('path'));
});
