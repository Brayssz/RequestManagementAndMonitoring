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
        Schema::table('document_trackers', function (Blueprint $table) {
            $table->string('requestor_email')->nullable()->after('requestor_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_trackers', function (Blueprint $table) {
            $table->dropColumn('requestor_email');
        });
    }
};
