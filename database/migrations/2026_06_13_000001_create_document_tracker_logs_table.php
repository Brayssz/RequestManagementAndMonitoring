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
        Schema::create('document_tracker_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_tracker_id');
            $table->unsignedBigInteger('from_office_id')->nullable();
            $table->unsignedBigInteger('to_office_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('document_tracker_id')
                ->references('id')
                ->on('document_trackers')
                ->cascadeOnDelete();

            $table->foreign('from_office_id')
                ->references('requesting_office_id')
                ->on('requesting_offices')
                ->nullOnDelete();

            $table->foreign('to_office_id')
                ->references('requesting_office_id')
                ->on('requesting_offices')
                ->nullOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_tracker_logs');
    }
};
