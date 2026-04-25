<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchoolUpdateRequest;
use App\Http\Requests\UpdateSchoolUpdateRequest;
use App\Http\Requests\UploadSchoolUpdateMediaRequest;
use App\Models\SchoolUpdate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolUpdateController extends Controller
{
	public function index(Request $request): JsonResponse
	{
		return $this->paginatedResponse(
			$this->baseQuery($request)
				->whereIn('status', ['published', 'scheduled'])
				->where('published_at', '<=', now()),
			$request
		);
	}

	public function manage(Request $request): JsonResponse
	{
		return $this->paginatedResponse($this->baseQuery($request), $request);
	}

	public function show(SchoolUpdate $schoolUpdate): JsonResponse
	{
		if (
			!in_array($schoolUpdate->status, ['published', 'scheduled'], true) ||
			!$schoolUpdate->published_at ||
			$schoolUpdate->published_at->isFuture()
		) {
			abort(404);
		}

		return response()->json($this->transform($schoolUpdate->load('author')));
	}

	public function store(StoreSchoolUpdateRequest $request): JsonResponse
	{
		$validated = $this->normalizePayload($request->validated());
		$validated['author_id'] = $request->user()->id;
		$validated['slug'] = SchoolUpdate::uniqueSlug($validated['title']);

		$schoolUpdate = SchoolUpdate::query()->create($validated);

		return response()->json([
			'message' => 'School update created successfully.',
			'schoolUpdate' => $this->transform($schoolUpdate->load('author')),
		], 201);
	}

	public function update(UpdateSchoolUpdateRequest $request, SchoolUpdate $schoolUpdate): JsonResponse
	{
		$validated = $this->normalizePayload($request->validated());

		if ($schoolUpdate->title !== $validated['title']) {
			$validated['slug'] = SchoolUpdate::uniqueSlug($validated['title'], $schoolUpdate->id);
		}

		$schoolUpdate->update($validated);

		return response()->json([
			'message' => 'School update updated successfully.',
			'schoolUpdate' => $this->transform($schoolUpdate->refresh()->load('author')),
		]);
	}

	public function destroy(SchoolUpdate $schoolUpdate): JsonResponse
	{
		if ($schoolUpdate->featured_image) {
			Storage::disk('public')->delete($schoolUpdate->featured_image);
		}

		Storage::disk('public')->deleteDirectory("school-updates/{$schoolUpdate->id}/content");
		$schoolUpdate->delete();

		return response()->json([
			'message' => 'School update deleted successfully.',
		]);
	}

	public function uploadFeaturedImage(
		UploadSchoolUpdateMediaRequest $request,
		SchoolUpdate $schoolUpdate
	): JsonResponse {
		$path = $request->file('file')->store("school-updates/{$schoolUpdate->id}", 'public');

		if ($schoolUpdate->featured_image) {
			Storage::disk('public')->delete($schoolUpdate->featured_image);
		}

		$schoolUpdate->update(['featured_image' => $path]);

		return response()->json([
			'message' => 'Featured image uploaded successfully.',
			'path' => $path,
			'url' => Storage::url($path),
			'schoolUpdate' => $this->transform($schoolUpdate->refresh()->load('author')),
		]);
	}

	public function uploadContentImage(
		UploadSchoolUpdateMediaRequest $request,
		SchoolUpdate $schoolUpdate
	): JsonResponse {
		$path = $request->file('file')->store("school-updates/{$schoolUpdate->id}/content", 'public');

		return response()->json([
			'message' => 'Content image uploaded successfully.',
			'path' => $path,
			'url' => Storage::url($path),
		]);
	}

	private function baseQuery(Request $request): Builder
	{
		return SchoolUpdate::query()
			->with('author:id,name,email')
			->when($request->filled('search'), function (Builder $query) use ($request) {
				$search = $request->string('search')->toString();

				$query->where(function (Builder $query) use ($search) {
					$query->where('title', 'like', "%{$search}%")
						->orWhere('summary', 'like', "%{$search}%")
						->orWhere('content', 'like', "%{$search}%")
						->orWhere('category', 'like', "%{$search}%");
				});
			})
			->when($request->filled('type') && $request->input('type') !== 'all', fn (Builder $query) => $query->where('type', $request->input('type')))
			->when($request->filled('category') && $request->input('category') !== 'all', fn (Builder $query) => $query->where('category', $request->input('category')))
			->when($request->filled('status') && $request->input('status') !== 'all', fn (Builder $query) => $query->where('status', $request->input('status')))
			->when($request->filled('tag') && $request->input('tag') !== 'all', fn (Builder $query) => $query->whereJsonContains('tags', $request->input('tag')))
			->orderByDesc('published_at')
			->orderByDesc('created_at');
	}

	private function paginatedResponse(Builder $query, Request $request): JsonResponse
	{
		$perPage = min(max((int) $request->input('per_page', 12), 1), 50);
		$metaQuery = clone $query;
		$paginator = $query->paginate($perPage);

		return response()->json([
			'data' => collect($paginator->items())->map(fn (SchoolUpdate $schoolUpdate) => $this->transform($schoolUpdate))->values(),
			'pagination' => [
				'curPage' => $paginator->currentPage(),
				'from' => $paginator->firstItem(),
				'to' => $paginator->lastItem(),
				'perPage' => $paginator->perPage(),
				'lastPage' => $paginator->lastPage(),
				'total' => $paginator->total(),
			],
			'meta' => [
				'categories' => (clone $metaQuery)->reorder()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category')->values(),
				'tags' => (clone $metaQuery)->reorder()->whereNotNull('tags')->pluck('tags')->flatten()->unique()->sort()->values(),
			],
		]);
	}

	private function normalizePayload(array $payload): array
	{
		$payload['tags'] = collect($payload['tags'] ?? [])
			->filter(fn ($tag) => is_string($tag) && trim($tag) !== '')
			->map(fn ($tag) => trim($tag))
			->unique()
			->values()
			->all();

		if ($payload['status'] === 'published' && empty($payload['published_at'])) {
			$payload['published_at'] = now();
		}

		if ($payload['status'] === 'draft' || $payload['status'] === 'archived') {
			$payload['published_at'] = $payload['published_at'] ?? null;
		}

		if ($payload['type'] !== 'event') {
			$payload['event_start_at'] = null;
			$payload['event_end_at'] = null;
		}

		return $payload;
	}

	private function transform(SchoolUpdate $schoolUpdate): array
	{
		return [
			'id' => $schoolUpdate->id,
			'author_id' => $schoolUpdate->author_id,
			'author_name' => $schoolUpdate->author?->name,
			'title' => $schoolUpdate->title,
			'slug' => $schoolUpdate->slug,
			'summary' => $schoolUpdate->summary,
			'content' => $schoolUpdate->content,
			'type' => $schoolUpdate->type,
			'category' => $schoolUpdate->category,
			'tags' => $schoolUpdate->tags ?? [],
			'status' => $schoolUpdate->status,
			'published_at' => optional($schoolUpdate->published_at)->toISOString(),
			'event_start_at' => optional($schoolUpdate->event_start_at)->toISOString(),
			'event_end_at' => optional($schoolUpdate->event_end_at)->toISOString(),
			'featured_image' => $schoolUpdate->featured_image,
			'featured_image_url' => $schoolUpdate->featured_image ? Storage::url($schoolUpdate->featured_image) : null,
			'created_at' => optional($schoolUpdate->created_at)->toISOString(),
			'updated_at' => optional($schoolUpdate->updated_at)->toISOString(),
		];
	}
}
