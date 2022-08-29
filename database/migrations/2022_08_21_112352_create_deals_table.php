<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->referances('id')->constrained('venues');
            $table->text('title');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
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
        Schema::dropIfExists('deals');
    }
}
