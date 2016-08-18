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

    public function getDenialOfService()
    {
        $data = file(public_path() . "/capture.txt", FILE_SKIP_EMPTY_LINES);

        $capture = array();

        for ($i = ((sizeof($data) > 100) ? ( sizeof($data) - 100) : 0); $i < sizeof($data); $i++) {
            $info = array();
            $line = explode(' ', $data[$i]);
            $info['date'] = $line[0];
            $info['src'] = array();
            $info['dst'] = array();

            $ip_port = explode('.', $line[2]);
            $info['src']['ip'] = $ip_port[0] . '.' . $ip_port[1] . '' . $ip_port[2] . '' . $ip_port[3];
            $info['src']['port'] = $ip_port[4];

            $ip_port = explode('.', $line[4]);
            $info['dst']['ip'] = $ip_port[0] . '.' . $ip_port[1] . '' . $ip_port[2] . '' . $ip_port[3];;
            $info['dst']['port'] = $ip_port[4];

            $info['size'] = intval($line[6]);
            if ($info['size'] != 0 || $info['size'] != '0' || $info['size'] != '0\n') {
                $info['size'] /= 1024.0;
                $info['size'] = round($info['size'], 3);
                array_push($capture, $info);
            }
        }

        return Response::create($capture);
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
