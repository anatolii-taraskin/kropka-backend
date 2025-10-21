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
            $table->string('name_ru')->nullable()->after('id');
            $table->string('name_en')->nullable()->after('name_ru');
            $table->text('description_ru')->nullable()->after('name_en');
            $table->text('description_en')->nullable()->after('description_ru');
        });

        DB::table('equipment')
            ->select(['id', 'name', 'description'])
            ->orderBy('id')
            ->each(function ($item): void {
                DB::table('equipment')
                    ->where('id', $item->id)
                    ->update([
                        'name_ru' => $item->name,
                        'description_ru' => $item->description,
                    ]);
            });

        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn(['name', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->text('description')->nullable()->after('name');
        });

        DB::table('equipment')
            ->select(['id', 'name_ru', 'description_ru'])
            ->orderBy('id')
            ->each(function ($item): void {
                DB::table('equipment')
                    ->where('id', $item->id)
                    ->update([
                        'name' => $item->name_ru,
                        'description' => $item->description_ru,
                    ]);
            });

        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn(['name_ru', 'name_en', 'description_ru', 'description_en']);
        });
    }
};
