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
        Schema::table('board_columns', function (Blueprint $table) {
            $table->foreign('board_id')->references('id')->on('boards');
            $table->foreign('column_id')->references('id')->on('columns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('board_columns', function (Blueprint $table) {
            $table->dropForeign(['board_id']);
            $table->dropForeign(['column_id']);
        });
    }
};
