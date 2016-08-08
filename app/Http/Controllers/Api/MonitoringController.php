<?php namespace App\Http\Controllers\Api;

use App\DevicesModel;
use App\NmapAllScanModel;
use App\SettingsModel;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;

class MonitoringController extends BaseController
{

    public function list_status()
    {
        $pc = NmapAllScanModel::all()->toArray();
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

    public function scan_ports()
    {
        if (Input::has('ip')) {
            $ip = Input::get('ip');
            $list = shell_exec('nmap ' . $ip);
            $list = explode(PHP_EOL, $list);

            $ports = array();
            for ($index = 6; $index < sizeof($list) - 4; $index++) {
                $first_line = preg_replace('/\s\s+/', ' ', $list[$index]);
                $first_line = explode(' ', $first_line);
                //  print_r($first_line);
                $port = explode('/', $first_line [0], 2)[0];
                $type = explode('/', $first_line [0], 2)[1];
                $status = $first_line[1];
                $service = $first_line[2];
                array_push($ports, [
                    'port' => $port,
                    'type' => $type,
                    'status' => $status,
                    'service' => $service,
                ]);

            }
            return Response::create($ports);
        } else {
            return Response::create([]);
        }

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
