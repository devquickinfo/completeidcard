<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_custom_fields', function (Blueprint $table) {
    $table->id();

    $table->foreignId('event_id')
        ->constrained('manage_events')
        ->cascadeOnDelete();

    $table->string('label');
    $table->string('field_name');
    $table->string('input_type');

    $table->string('html_id')->nullable();
    $table->string('html_class')->nullable();

    // For dropdown, checkbox and radio
    $table->text('options')->nullable();

    $table->boolean('is_required')->default(false);
    $table->boolean('is_deleted')->default(false);

    $table->integer('sort_order')->default(0);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_custom_fields');
    }
};
