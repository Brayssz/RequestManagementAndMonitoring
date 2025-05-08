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
        Schema::create('request_activity_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('transmitted_office_id')->nullable(); 
            $table->timestamp('transmitted_date')->nullable();
            $table->string('remarks')->nullable();
            $table->string('activity');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('request_id')->references('request_id')->on('requests')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('transmitted_office_id')->references('requesting_office_id')->on('requesting_offices')->onDelete('set null'); // Foreign key for transmitted office
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_activity_logs');
    }
};
