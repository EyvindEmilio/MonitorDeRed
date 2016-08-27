<?php

use App\SettingsModel;
use Illuminate\Database\Seeder;

class settings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->delete();
        DB::table('settings')->truncate();
        DB::table('settings')->delete();
        SettingsModel::create(['id' => 1,
            'network_address' => '192.168.1.0',
            'gateway' => '192.168.1.1',
            'mask' => 24,
            'time_interval_for_sending_monitoring_data' => 1,
            'time_interval_for_scan_ports' => 30,
            'dos_time_for_check_attacks' => 30,
            'dos_max_packets_received' => 10000,
            'active_system' => 'Y']);
    }
}
