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
            $table->text('value_ru')->nullable()->after('property');
            $table->text('value_en')->nullable()->after('value_ru');
        });

        DB::table('studio_rules')
            ->orderBy('id')
            ->get()
            ->each(function ($rule) {
                DB::table('studio_rules')
                    ->where('id', $rule->id)
                    ->update([
                        'value_ru' => $rule->value,
                        'value_en' => $rule->value,
                    ]);
            });

        Schema::table('studio_rules', function (Blueprint $table) {
            $table->dropColumn('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('studio_rules', function (Blueprint $table) {
            $table->text('value')->nullable()->after('property');
        });

        DB::table('studio_rules')
            ->orderBy('id')
            ->get()
            ->each(function ($rule) {
                DB::table('studio_rules')
                    ->where('id', $rule->id)
                    ->update([
                        'value' => $rule->value_ru,
                    ]);
            });

        Schema::table('studio_rules', function (Blueprint $table) {
            $table->dropColumn(['value_ru', 'value_en']);
        });
    }
};
