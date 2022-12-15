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
        Schema::create('identity_documents', function (Blueprint $table) {
            $table->id(); // Laravel
            $table->string('state'); // e.g. valid, expired
            $table->string('identifier'); // type::familyname::givenname::expirydate
            $table->tinyText('type'); // pp, dl, brp
            $table->date('dateOfExpiry')->nullable(); // nullable while importing
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
        Schema::dropIfExists('identity_documents');
    }
};
