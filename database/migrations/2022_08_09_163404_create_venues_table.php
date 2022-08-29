<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->referances('id')->constrained('users');
            $table->string('customer_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->text('description')->nullable();
            $table->text('category')->nullable();
            $table->text('place_name')->nullable();
            $table->string('website');
            $table->string('phone_number');
            $table->string('address');
            $table->string('image_path');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('opening_time');
            $table->string('closing_time');
            $table->string('coupon_code')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('venues');
    }
}
