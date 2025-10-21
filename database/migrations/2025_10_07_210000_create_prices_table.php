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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->string('name_ru');
            $table->string('name_en');
            $table->string('col1_ru')->nullable();
            $table->string('col1_en')->nullable();
            $table->string('col2_ru')->nullable();
            $table->string('col2_en')->nullable();
            $table->string('col3_ru')->nullable();
            $table->string('col3_en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('sort');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
