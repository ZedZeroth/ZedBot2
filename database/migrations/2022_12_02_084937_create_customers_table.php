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
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // Laravel
            $table->string('state'); // Spatie's state system
            $table->string('identifier'); // ???
            $table->string('type'); // person, company, bank, vasp, self
            $table->string('familyName'); // e.g. "last name" / "surname"
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
        Schema::dropIfExists('customers');
    }
};
