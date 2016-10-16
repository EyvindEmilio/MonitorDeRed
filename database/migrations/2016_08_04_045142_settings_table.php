<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('network_address');
            $table->string('gateway');
            $table->integer('mask');
            $table->string('interface');
            $table->integer('time_interval_for_sending_monitoring_data')->default(1);
            $table->integer('time_interval_for_scan_ports')->default(30);
            $table->integer('dos_time_for_check_attacks')->default(30);
            $table->integer('dos_max_packets_received')->default(10000);
            $table->integer('interval_snmp_scan')->default(30);
            $table->integer('interval_send_saturation')->default(600);//y
            $table->integer('max_bandwidth_saturation')->default(30000);//y

            $table->char('send_mail_saturation',1)->default('N');//k
            $table->char('send_mail_dos',1)->default('N');//Y
            $table->char('send_mail_backdoor',1)->default('N');//y
            $table->char('send_mail_inactive_pc', 1)->default('N');
            $table->char('active_system', 1)->default('Y');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('settings');
    }
}
