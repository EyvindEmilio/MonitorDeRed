<?php

namespace App\Http\Controllers;


use App\AreasModel;
use App\NetworkUsageModel;
use Dompdf\Dompdf;
use SVGGraph;

class ReportsController extends Controller
{
    public static function convertToMb($value)//KB
    {
        return round($value / 1024.0, 2);
    }

    public static function perAreas()
    {
        date_default_timezone_set('America/La_Paz');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $list_values = array();
        $list = NetworkUsageModel::getConsumo();
        $area_list = $list;

        for ($i = 0; $i < sizeof($list); $i++) {
            $size = ($list[$i]->network_usage == null ? 0 : $list[$i]->network_usage);
            $name = substr($list[$i]->area, 0, 14);
            if (strlen($name) != strlen($list[$i]->area)) $name .= '..';
            $list_values[$name] = ReportsController::convertToMb($size);
            $list[$i]->network_usage = ReportsController::convertToMb($size);
        }

        $settings = array(
            'back_colour' => 'none', 'back_stroke_colour' => 'none',
            'axis_text_space_v' => '40', 'graph_title' => 'Uso de red por Areas',
            'axis_text_callback_y' => function ($val) {
                return $val . " Mb";
            }
        );

        $graph = new SVGGraph(700, 400, $settings);
        $graph->values = $list_values;
        $graph = $graph->Fetch('BarGraph', false);
        $graph64 = 'data:image/svg;base64,' . base64_encode($graph);
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('reports/test', ['area_list' => $area_list, 'graph64' => $graph64]));
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream('Reporte por Area ' . date('Y-m-d'), ['Attachment' => 0]);
    }

    public static function perArea($id, $start, $end)
    {
        date_default_timezone_set('America/La_Paz');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $start_date = $start;//'2016-09-03';
        $end_date = $end;//'2016-10-02';
        $list_values = array();
        $list = NetworkUsageModel::getConsumoAreaPerDate($id, $start_date, $end_date);

        for ($i = 0; $i < sizeof($list); $i++) {
            $list[$i]['size'] = ($list[$i]['size'] == null ? 0 : $list[$i]['size']);
            $list[$i]['size'] = ReportsController::convertToMb($list[$i]['size']);
            $list_values[$list[$i]['date']] = $list[$i]['size'];
        }

        $date_list = $list;

        $settings = array(
            'back_colour' => 'none', 'back_stroke_colour' => 'none',
            'axis_text_space_v' => '40', 'graph_title' => 'Uso de red por Areas',
            'axis_text_callback_y' => function ($val) {
                return $val . " Mb";
            }
        );

        $graph = new SVGGraph(700, 400, $settings);
        $graph->values = $list_values;
        $graph = $graph->Fetch('LineGraph', false);
        $graph64 = 'data:image/svg;base64,' . base64_encode($graph);
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('reports/per_area', ['date_list' => $date_list, 'graph64' => $graph64, 'area' => AreasModel::find($id), 'start_date' => $start_date, 'end_date' => $end_date]));
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream('Reporte de Area ' . AreasModel::find($id)->name, ['Attachment' => 0]);
    }
}