<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('spu')->unique();
            $table->json('full_category_id')->nullable();
            $table->string('sale_status')->nullable();
            $table->string('condition');
            $table->text('short_description');
            $table->text('description');
            $table->json('variant_options')->nullable();
            $table->json('variations')->nullable();
            $table->json('images')->nullable();
            $table->json('delivery')->nullable();
            $table->json('cost_info')->nullable();
            $table->json('extra_info')->nullable();
            $table->integer('min_purchase')->default(1);
            $table->string('brand');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
