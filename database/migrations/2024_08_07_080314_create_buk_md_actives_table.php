<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\TextUI\XmlConfiguration\Logging\TeamCity;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buk_md_actives', function (Blueprint $table) {
            $table->integer('id')->unsigned()->primary();
            $table->bigInteger('document_number')->unique();
            $table->string('full_name',60);
            $table->string('status',10);
            $table->string('marital_status',15);
            $table->string('address',100);
            $table->string('neigborhood',80);
            $table->string('City',30);
            $table->string('mobile_number',15);
            $table->string('eps',25);
            $table->string('afp',25);
            $table->string('tribu',20);
            $table->datetime('processed')->useCurrent();
            $table->datetime('synchronized')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buk_md_actives');
    }
};

