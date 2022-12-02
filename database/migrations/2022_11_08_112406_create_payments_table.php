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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Laravel
            $table->string('state'); // Spatie's state system
            $table->string('network'); // e.g. FPS, ethereum, LBC...
            $table->string('identifier'); // For FPS this is currently ENM-specific
            $table->bigInteger('amount'); // Wei amounts make exceed 2^63...?
            $table->integer('currency_id'); // Currency
            $table->integer('originator_id'); // Account
            $table->integer('beneficiary_id'); // Account
            $table->string('memo'); // e.g. Public payment reference
            $table->timestamp('timestamp'); // On network
            $table->timestamps(); // Laravel
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
