<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('product_name');
            $table->string('brand_name')->nullable();
            $table->unsignedInteger('price');
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'sold'])->default('available');
            $table->enum('category', ['Fashions', 'Electronics', 'Interiors', 'Women', 'Men','Cosmetics','Books','Games','Sports','Kitchens','handmaids','Accessories','Toys','Babies Kids']);
            $table->enum('condition', ['Good', 'No Scratches', 'Scratches', 'Bad']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
