<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('ip');
            $table->string('mac')->nullable();
            $table->char('status',1)->default('Y');
            $table->string('description');
            $table->integer('area')->unsigned()->index();
            $table->integer('device_type')->unsigned()->index();
            $table->string('notes');
            $table->timestamps();

            $table->foreign('area')->references('id')->on('areas');
            $table->foreign('device_type')->references('id')->on('device_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('devices');
    }
}
