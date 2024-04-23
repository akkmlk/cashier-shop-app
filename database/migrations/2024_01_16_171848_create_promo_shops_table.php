<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\text;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promo_shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['buyget', 'discount']);
            $table->text('description');
            $table->integer('buy')->nullable();
            $table->integer('get')->nullable();
            // $table->dateTime('start');
            // $table->dateTime('end');
            $table->float('discount')->nullable();
            $table->boolean('active')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_shops');
    }
};
