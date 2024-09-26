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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('slug',255);
            $table->boolean('status')->default(0);
            $table->unsignedBigInteger('id_parent')->nullable();
            $table->timestamps();
        });
        if(Schema::hasTable('categories')) {
            Schema::create('products_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_product')->nullable();
                $table->unsignedBigInteger('id_categories')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables_categories');
    }
};
