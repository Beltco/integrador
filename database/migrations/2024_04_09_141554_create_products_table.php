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
        Schema::create('products', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->primary('id');
            $table->foreignId("deal_id")->constrained();
            $table->unsignedBigInteger("product_id")->nullable();
            $table->string("name",255);
            $table->double("item_price",12,2);
            $table->float("duration");
            $table->integer("quantity");
            $table->double("sum",12,2);
            $table->boolean("enabled_flag");
            $table->dateTime("add_time");
            $table->dateTime("processed")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
