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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id(); // Laravel
            $table->string('state'); // type::handle
            $table->string('identifier'); // type::handle
            $table->string('type'); // phone, email, discord etc.
            $table->string('handle'); // e.g. the actual phone number or email address
            $table->foreignId('customer_id')->nullable(); // Contact owner
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
        Schema::dropIfExists('contacts');
    }
};
