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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            //$table->string('state'); // active, inactive, unverified, blocked
            $table->string('network');
            $table->string('identifier');
            $table->integer('customer_id')->nullable();
            $table->string('networkAccountName')->nullable();
            $table->string('label');
            $table->integer('currency_id');
            $table->bigInteger('balance')->nullable();
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
        Schema::dropIfExists('accounts');
    }
};
