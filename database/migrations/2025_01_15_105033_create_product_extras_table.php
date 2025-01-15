<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductExtrasTable extends Migration
{
    public function up()
    {
        Schema::create('product_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->json('pre_order')->nullable();
            $table->boolean('has_shelf_life')->default(false);
            $table->integer('shelf_life_period')->nullable();
            $table->json('addition_info')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_extras');
    }
}
