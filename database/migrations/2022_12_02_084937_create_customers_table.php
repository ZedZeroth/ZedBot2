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
            $table->string('state'); // unverified -> active <-> suspended -> banned
            $table->string('identifier'); // "customer"::customer_id::surname::surname_collision_increment::given_name_1::given_name_2
            $table->string('type'); // person, company, bank, vasp, self
            $table->string('familyName'); // "last name" / "surname"
            $table->string('givenName1'); // first name
            $table->string('givenName2'); // first middle name
            $table->string('companyName');
            $table->string('preferredName'); // If preferred over givenName1
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
