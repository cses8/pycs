<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('school_updates', function (Blueprint $table) {
			$table->id();
			$table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
			$table->string('title');
			$table->string('slug')->unique();
			$table->text('summary')->nullable();
			$table->longText('content');
			$table->string('type')->default('news')->index();
			$table->string('category')->nullable()->index();
			$table->json('tags')->nullable();
			$table->string('status')->default('draft')->index();
			$table->timestamp('published_at')->nullable()->index();
			$table->timestamp('event_start_at')->nullable();
			$table->timestamp('event_end_at')->nullable();
			$table->string('featured_image')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('school_updates');
	}
};
