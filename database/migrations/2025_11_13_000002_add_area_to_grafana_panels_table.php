<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('grafana_panels', function (Blueprint $table) {
            $table->string('area')->default('OTT')->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('grafana_panels', function (Blueprint $table) {
            $table->dropColumn('area');
        });
    }
};
