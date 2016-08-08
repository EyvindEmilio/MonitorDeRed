<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NmapAllScanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nmap_all_scan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip');
            $table->string('mac');
            $table->string('latency');
            $table->string('manufacturer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('nmap_all_scan');
    }
}
