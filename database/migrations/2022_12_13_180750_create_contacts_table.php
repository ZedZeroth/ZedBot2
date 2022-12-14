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
            $table->string('state'); // e.g. primary, active, disabled
            $table->string('identifier'); // type::handle
            $table->tinyText('type'); // phone, email, discord etc.
            $table->string('handle'); // e.g. the actual phone number or email address
            $table->foreignId('customer_id'); // Contact owner
            $table->timestamps(); // Laravel
            $table->softDeletes(); // Allow softDeletes
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
