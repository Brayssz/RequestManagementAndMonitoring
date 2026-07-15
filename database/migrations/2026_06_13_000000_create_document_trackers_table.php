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
        Schema::create('document_trackers', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            $table->string('requestor_name')->nullable();
            $table->unsignedBigInteger('current_office_id')->nullable();
            $table->string('document_type');
            $table->text('details')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('received_by_user_id')->nullable();
            $table->unsignedBigInteger('released_by_user_id')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();

            $table->foreign('current_office_id')
                ->references('requesting_office_id')
                ->on('requesting_offices')
                ->nullOnDelete();

            $table->foreign('received_by_user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('released_by_user_id')
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
        Schema::dropIfExists('document_trackers');
    }
};
