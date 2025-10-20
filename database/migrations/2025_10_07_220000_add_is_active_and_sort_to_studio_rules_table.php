<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('studio_rules', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('value');
            $table->unsignedTinyInteger('sort')->default(0)->after('is_active');
        });

        $ids = DB::table('studio_rules')
            ->orderBy('id')
            ->pluck('id');

        $position = 1;

        foreach ($ids as $id) {
            DB::table('studio_rules')
                ->where('id', $id)
                ->update(['sort' => $position++]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('studio_rules', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'sort']);
        });
    }
};
