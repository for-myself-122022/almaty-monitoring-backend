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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->date('birthday');
            $table->boolean('is_male');
            $table->string('region');
            $table->string('address');
            $table->string('email')->unique();
            $table->string('education');
            $table->string('nationality');
            $table->string('height');
            $table->string('weight');
            $table->boolean('is_served_army');
            $table->string('car_model');
            $table->year('car_year');
            $table->string('car_number');
            $table->string('iban', 20);
            $table->string('identification');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
