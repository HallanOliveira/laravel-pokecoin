<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pokemons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('buy_price', $precision = 8, $scale = 2);
            $table->decimal('sell_price', $precision = 8, $scale = 2)->nullable();
            $table->string('imagem')->nullable();
            $table->integer('base_experience');
            $table->integer('status');
            $table->timestamp('buy_date')->nullable();
            $table->timestamp('sell_date')->nullable();
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
        Schema::dropIfExists('pokemons');
    }
};
