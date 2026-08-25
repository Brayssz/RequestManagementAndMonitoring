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
            $table->unsignedBigInteger('requesting_office_id')->nullable()->after('requestor_email');

            $table->foreign('requesting_office_id')
                ->references('requesting_office_id')
                ->on('requesting_offices')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_trackers', function (Blueprint $table) {
            $table->dropForeign(['requesting_office_id']);
            $table->dropColumn('requesting_office_id');
        });
    }
};
