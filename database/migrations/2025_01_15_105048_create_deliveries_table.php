<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('length_unit')->nullable();
            $table->string('weight_unit')->nullable();
            $table->decimal('declare_amount', 10, 2)->nullable();
            $table->decimal('declare_weight', 10, 2)->nullable();
            $table->string('declare_currency')->nullable();
            $table->string('declare_hs_code')->nullable();
            $table->string('declare_zh_name')->nullable();
            $table->string('declare_en_name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
