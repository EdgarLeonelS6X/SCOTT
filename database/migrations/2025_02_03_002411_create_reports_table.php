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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('category');
            $table->date('report_date');
            $table->time('start_time')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->time('end_time')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->time('duration')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('reported_by');
            $table->string('attended_by')->nullable();
            $table->string('status')->nullable();
            $table->string('solution')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
