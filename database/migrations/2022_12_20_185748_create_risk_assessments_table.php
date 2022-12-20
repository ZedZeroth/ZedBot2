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
        Schema::create('risk_assessments', function (Blueprint $table) {
            $table->id(); // Laravel
            $table->string('state'); // e.g. // Lower, Standard, MitigatedHigher, NoData, UnmitigatedHigher
            $table->string('identifier'); // type::familyname::givenname
            $table->tinyText('type'); // volume, velocity... etc. (11 types)
            $table->string('action')->nullable(); // Mitigatory action taken
            $table->string('notes')->nullable(); // Additional notes
            $table->foreignId('customer_id'); // Risk assessment customer
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
        Schema::dropIfExists('risk_assessments');
    }
};
