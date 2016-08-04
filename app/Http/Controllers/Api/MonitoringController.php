<?php namespace App\Http\Controllers\Api;

use App\DevicesModel;
use App\SettingsModel;
use Illuminate\Http\Response;

class MonitoringController extends BaseController
{
    public function list_status()
    {
        $settings = SettingsModel::find(1)->toArray();

        $IP = $settings['network_address'];
        $MASK = $settings['mask'];

        $OCT = explode('.', $IP);
        $ip_scan = '';
        if ($MASK == 8) {
            $ip_scan = $OCT[0] . '.*.*.*';
        } elseif ($MASK == 16) {
            $ip_scan = $OCT[0] . '.' . $OCT[1] . '.*.*';
        } elseif ($MASK == 24) {
            $ip_scan = $OCT[0] . '.' . $OCT[1] . '.' . $OCT[2] . '.*';
        } else {
            $ip_scan = $IP;
        }
        $list = shell_exec('nmap -sP ' . $ip_scan);
        $list = explode(PHP_EOL, $list);

        $pc = array();
        for ($index = 2; $index < sizeof($list) - 4; $index += 3) {
            $first_line = explode(' ', $list[$index + 2], 4);
            $mac = $first_line[2];
            $manufacturer = $first_line[3];

            $second_line = explode(' ', $list[$index]);
            $ip = $second_line[4];

            array_push($pc, ['mac' => $mac, 'ip' => $ip, 'manufacturer' => $manufacturer]);
        }

        $devices = DevicesModel::all()->toArray();

        for ($i = 0; $i < sizeof($devices); $i++) {
            $devices[$i]['status_network'] = 'N';
            $devices[$i]['mac'] = '- No detectado -';
            $devices[$i]['manufacturer'] = '- Desconocido -';
            for ($j = 0; $j < sizeof($pc); $j++) {
                if ($devices[$i]['ip'] == $pc[$j]['ip']) {
                    $devices[$i]['status_network'] = 'Y';
                    $devices[$i]['mac'] = $pc[$j]['mac'];
                    $devices[$i]['manufacturer'] = $pc[$j]['manufacturer'];
                }
            }
        }

        return Response::create($devices);
    }

    public function getListDevices()
    {
        return 2;
    }

    public function getInfo($IP)
    {
        return 2;
    }

    public function scanPorts($IP = null)
    {
        return 2;
    }
}
