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
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'name')) {
                $table->renameColumn('name', 'name_ru');
            }

            if (Schema::hasColumn('teachers', 'description')) {
                $table->renameColumn('description', 'description_ru');
            }

            if (! Schema::hasColumn('teachers', 'name_en')) {
                $table->string('name_en')->default('')->after('name_ru');
            }

            if (! Schema::hasColumn('teachers', 'description_en')) {
                $table->text('description_en')->nullable()->after('description_ru');
            }
        });

        DB::table('teachers')->update([
            'name_en' => DB::raw("CASE WHEN name_en = '' THEN name_ru ELSE name_en END"),
            'description_en' => DB::raw('COALESCE(description_en, description_ru)'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('teachers')->update([
            'name_ru' => DB::raw("CASE WHEN name_ru = '' THEN name_en ELSE name_ru END"),
            'description_ru' => DB::raw('COALESCE(description_ru, description_en)'),
        ]);

        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'description_en')) {
                $table->dropColumn('description_en');
            }

            if (Schema::hasColumn('teachers', 'name_en')) {
                $table->dropColumn('name_en');
            }

            if (Schema::hasColumn('teachers', 'description_ru')) {
                $table->renameColumn('description_ru', 'description');
            }

            if (Schema::hasColumn('teachers', 'name_ru')) {
                $table->renameColumn('name_ru', 'name');
            }
        });
    }
};
