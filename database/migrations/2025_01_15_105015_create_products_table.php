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
            // Delivery Fields
            $table->boolean('is_preorder')->default(false);
            
            // Product Information for Delivery
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            
            // Customs Information
            $table->string('customs_chinese_name', 200)->nullable();
            $table->string('customs_english_name', 200)->nullable();
            $table->string('hs_code', 200)->nullable();
            $table->decimal('invoice_amount', 15, 2)->nullable();
            $table->decimal('gross_weight', 10, 2)->nullable();
            
            // Cost Information
            $table->string('source_url', 150)->nullable();
            $table->string('purchase_duration')->nullable();
            $table->decimal('sales_tax_amount', 15, 2)->nullable();
            
            // Other Information
            $table->string('remarks1', 50)->nullable();
            $table->string('remarks2', 50)->nullable();
            $table->string('remarks3', 50)->nullable();
            $table->integer('sold_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->timestamps();
        });

        Schema::create('variation_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->json('values')->nullable();
            $table->integer('order')->nullable();
            $table->timestamps();
        });

        Schema::create('variation_type_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variation_type_id')->constrained()->onDelete('cascade');
            $table->string('value');
            $table->integer('order')->nullable();
            $table->timestamps();
        });

        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->string('msku');
            $table->string('barcode')->nullable();
            $table->json('combinations')->nullable();
            $table->string('variant_image_path')->nullable();
            $table->integer('sold_count')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
        
        Schema::dropIfExists('product_images');
    
        Schema::dropIfExists('variation_types');


        Schema::dropIfExists('variation_type_values');


        Schema::dropIfExists('product_variations');

    }
}

