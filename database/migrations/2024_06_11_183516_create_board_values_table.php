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
        Schema::create('board_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId("board_id")->constrained();
            $table->string('column_id',255);
            $table->foreign("column_id")->references('id')->on('columns');
            $table->unsignedBigInteger('record_id');

            $table->string('value',255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_values');
    }
};
