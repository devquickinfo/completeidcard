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
        Schema::create('event_registration_field_values', function (Blueprint $table) {
    $table->id();

    $table->foreignId('event_registration_id')
        ->constrained('event_registrations')
        ->cascadeOnDelete();

    $table->foreignId('event_custom_field_id')
        ->constrained('event_custom_fields')
        ->cascadeOnDelete();

    $table->text('field_value')->nullable();

    $table->timestamps();

    $table->unique([
        'event_registration_id',
        'event_custom_field_id'
    ], 'registration_custom_field_unique');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registration_field_values');
    }
};
