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
        Schema::table('equipment', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('description');
            $table->unsignedTinyInteger('sort')->default(0)->after('is_active');
        });

        $ids = DB::table('equipment')
            ->orderBy('id')
            ->pluck('id');

        $position = 1;

        foreach ($ids as $id) {
            DB::table('equipment')
                ->where('id', $id)
                ->update(['sort' => $position++]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'sort']);
        });
    }
};
