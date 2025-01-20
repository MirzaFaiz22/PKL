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
            $table->string('name', 300);
            $table->string('spu', 200)->unique();
            $table->json('fullCategoryId')->nullable();
            $table->string('brand', 20)->nullable();
            $table->enum('saleStatus', ['FOR_SALE', 'HOT_SALE', 'NEW_ARRIVAL', 'SALE_ENDED']);
            $table->enum('condition', ['NEW', 'USED']);
            $table->boolean('hasSelfLife')->default(false);
            $table->integer('shelfLifeDuration')->nullable();
            $table->integer('inboundLimit')->nullable();
            $table->integer('outboundLimit')->nullable();
            $table->integer('minPurchase')->default(1);
            $table->text('shortDescription');
            $table->text('description');
            $table->boolean('has_variations')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
