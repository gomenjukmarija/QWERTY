<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Khill\Lavacharts\Lavacharts;
use App\Test;

class TestController extends Controller
{

    public function index()
    {
        if (request()->ajax()) {
            return Datatables::of(Test::query())->make(true);
        }

        $lava = new Lavacharts;
        $data = Test::select("bdate as 0", "sex as 1")->where('country', '2')->whereNotNull('bdate')
            ->limit(10000)->get()->toArray();

        $datatable = $lava->DataTable();
        $datatable->addDateColumn('Year')
            ->addNumberColumn('Sex')
            ->addRows($data);

        $lava->ScatterChart('YearSex', $datatable, [
            'width' => 1000,
            'height' => 500,
            'legend' => [
                'position' => ''
            ],
            'hAxis' => [
                'title' => 'Year'
            ],
            'vAxis' => [
                'title' => 'Sex'
            ]
        ]);

        $data2 = DB::table("tests")->select(DB::raw('first_name as "0", COUNT(*) as "1"'))
            ->groupBy(DB::raw("first_name"))
            ->limit(5000)
            ->get();
        $data2 = json_decode(json_encode($data2), true);

        $datatable2 = $lava->DataTable();
        $datatable2->addStringColumn('Name')
            ->addNumberColumn('Names Duplicate')
            ->addRows($data2);

        $pieChart = $lava->PieChart('Donuts', $datatable2, [
            'width' => 400,
            'pieSliceText' => 'value'
        ]);
        $filter = $lava->NumberRangeFilter(1, [
            'ui' => [
                'labelStacking' => 'vertical'
            ]
        ]);
        $control = $lava->ControlWrapper($filter, 'control');
        $chart = $lava->ChartWrapper($pieChart, 'chart');
        $lava->Dashboard('Donuts')->bind($control, $chart);

        return view('pages.test', compact('lava'));
    }

    public function api()
    {
        $token = "fe88637cb0381cec903616518f602399917258fd3fa59c36fab698979cd85215fe19686fd732eddcf14ea";

        $request_params = array(
            'sort' => '0',
            'count' => '1000',
            'fields' => 'bdate, sex, country',
            'city' => '628', // 314, 650 и т.д.
        );
        $get_params = http_build_query($request_params);

        for ($i = 0; $i < 10; $i++) {
            $query = file_get_contents("https://api.vk.com/method/users.search?.$get_params&access_token=" . $token);
            $query = json_decode($query, true);

            foreach ($query['response'] as $value) {
                $user = new Test;
                $user->first_name = $value['first_name'];
                $user->last_name = $value['last_name'];
                if (empty($value['bdate'])) {
                    $user->bdate = NULL;
                } else {
                    $bd = $value['bdate'];
                    $time = strtotime($bd);
                    $bd = date('Y-m-d', $time);
                    $user->bdate = $bd;
                }
                $user->sex = $value['sex'];
                $user->country = $value['country'];
                $user->save();
            }
        }
    }
}
