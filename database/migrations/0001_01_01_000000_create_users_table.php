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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('position');
            $table->string('password');
            $table->string('status')->default('active'); // Added status column
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('requestors', function (Blueprint $table) {
            $table->id('requestor_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('position');
            $table->string('status')->default('active'); 
            $table->timestamps();
        });

        Schema::create('requesting_offices', function (Blueprint $table) {
            $table->id('requesting_office_id');
            $table->string('name');
            $table->string('type')->nullable();
            $table->unsignedBigInteger('requestor')->nullable();
            $table->string('status')->default('active'); 
            $table->timestamps();

            $table->foreign('requestor')->references('requestor_id')->on('requestors')->onDelete('set null');
        });

        Schema::create('fund_sources', function (Blueprint $table) {
            $table->id('fund_source_id');
            $table->string('name');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('requests', function (Blueprint $table) {
            $table->id('request_id');
        
            $table->date('dts_date')->nullable();
            $table->string('dts_tracker_number')->nullable();
            $table->date('sgod_date_received')->nullable();
        
            $table->unsignedBigInteger('requesting_office_id')->nullable();
        
            $table->decimal('amount', 15, 2)->nullable();

            $table->unsignedBigInteger('fund_source_id')->nullable();

            $table->year('allotment_year')->nullable(); 

            $table->unsignedBigInteger('transmitted_office_id')->nullable(); 
        
            $table->text('nature_of_request')->nullable();
            $table->date('signed_chief_date')->nullable();
            $table->date('date_transmitted')->nullable();
        
            $table->string('remarks')->nullable();
            $table->string('status')->default('pending'); 
        
            $table->timestamps();
        
            $table->foreign('requesting_office_id')->references('requesting_office_id')->on('requesting_offices')->onDelete('set null');
            $table->foreign('fund_source_id')->references('fund_source_id')->on('fund_sources')->onDelete('set null'); 
            $table->foreign('transmitted_office_id')->references('requesting_office_id')->on('requesting_offices')->onDelete('set null'); // Foreign key for transmitted office
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_sources');
        Schema::dropIfExists('requestors');
        Schema::dropIfExists('requesting_offices');
        Schema::dropIfExists('users');
        Schema::dropIfExists('requests');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
