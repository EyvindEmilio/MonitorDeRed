<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SnmpScanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snmp_scan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip');
            $table->string('hardware');
            $table->string('time_ticks');
            $table->string('contact');
            $table->string('machine_name');
            $table->string('location');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('snmp_scan');
    }
}
