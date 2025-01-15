<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostsTable extends Migration
{
    public function up()
    {
        Schema::create('costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('source_url')->nullable();
            $table->date('purchasing_time')->nullable();
            $table->string('purchasing_time_unit')->nullable();
            $table->json('sales_tax')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('costs');
    }
}
