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
        Schema::create('buk_employees', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned();
            $table->primary('id');
            $table->bigInteger('document_number',15);
            $table->string('full_name',60);
            $table->string('marital_status',15);
            $table->string('address',100);
            $table->string('city',30);
            $table->string('mobile_number',15);
            $table->string('eps',60);
            $table->string('afp',25);
            $table->string('status',10);
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
        Schema::dropIfExists('buk_employees');
    }
};




