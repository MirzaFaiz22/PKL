<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationTypeValuesTable extends Migration
{
    public function up()
    {
        Schema::create('variation_type_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variation_type_id')->constrained()->onDelete('cascade');
            $table->string('value');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('variation_type_values');
    }
}