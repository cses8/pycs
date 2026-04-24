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
		Schema::create('school_calendars', function (Blueprint $table) {
			$table->id();

			$table->foreignId('school_year_id')->nullable()->index();
			$table->date('start');
			$table->date('end');
			$table->string('image');
			$table->string('title');
			$table->longText('description');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('school_calendars');
	}
};
