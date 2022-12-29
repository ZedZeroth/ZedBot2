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
            $table->tinyText('type'); // individual, company, bank, vasp, self, personal
            $table->string('familyName'); // "last name" / "surname"
            $table->string('givenName1'); // first name
            $table->string('givenName2')->nullable(); // first middle name
            $table->string('companyName')->nullable();
            $table->string('preferredName')->nullable(); // If preferred over givenName1
            $table->date('dateOfBirth')->nullable(); // nullable while importing
            $table->tinyText('placeOfBirth')->nullable(); // 2-character country ISO  // nullable while importing
            $table->tinyText('residency')->nullable(); // 2-character country ISO  // nullable while importing
            $table->tinyText('nationality')->nullable(); // 2-character country ISO
            $table->integer('volumeSnapshot')->nullable(); // Usually a monthly in+out GBP volume on bank statement
            $table->tinyText('sourceOfFiatFundsType')->nullable(); // verifiedSalary, salary, save, foreign, trading, gift, other
            $table->text('sourceOfFiatFundsQuote')->nullable();
            $table->tinyText('sourceOfCvcFundsType')->nullable(); // salary, invest, exchange, wallet, foreign, trading?, gift, other
            $table->text('sourceOfCvcFundsQuote')->nullable();
            $table->tinyText('destinationOfFiatFundsType')->nullable(); // spend, save
            $table->text('destinationOfFiatFundsQuote')->nullable();
            $table->tinyText('destinationOfCvcFundsType')->nullable(); // invest, foreign, spend, trading
            $table->text('destinationOfCvcFundsQuote')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
