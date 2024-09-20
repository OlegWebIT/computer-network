<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Date;
//use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $requestData = $request->all();
        $requestDataSql = '';
        $responseFromFormSql = '';

        if ($requestData) {


            if (isset($requestData['getDataLogs']) && !empty($requestData['getDataLogs'])) {

                $dateM = date('m',time());
                $dateY = date('Y',time());
                $startTime = strtotime("01-$dateM-$dateY");
                $numbersOfMonth = date('t');

                $logs = DB::table('logs')
                    ->whereBetween('time', [$startTime,time()])
                    ->get();

                $arrForGraphic = [];
                $checkDay = '';
                $i = 0;
                foreach ($logs as $log) {
                    $day = date('d',$log->time);
                    if ($day != $checkDay) {
                        $i = 1;
                        $checkDay = $day;
                    } else {
                        $i++;
                        $arrForGraphic[$day] = $i;
                    }
                }

                $arrForGraphicEnd = [];
                for ($i = 1; $i < $numbersOfMonth+1; $i++) {

                    if (strlen($i) == 1) {
                        $icr = '0'.$i;
                    } else {
                        $icr = $i;
                    }

                    if (!array_key_exists($icr,$arrForGraphic)) {
                        $arrForGraphicEnd[$icr] = 0;
                    } else {
                        $arrForGraphicEnd[$icr] = $arrForGraphic[$icr];
                    }
                }


                $arrX = [];
                $arrY = [];
                foreach ($arrForGraphicEnd as $grKey => $gr) {
                    $arrX[] =  $grKey;
                    $arrY[] =  $gr;
                }

                $responseArr = [$arrX,$arrY];


                echo json_encode($responseArr);
                exit();
//                [
//                    '01' => '5',
//                    '02' => '10',
//                    '03' => '15',
//                    '04' => '16',
//                ];
            }


            if (isset($requestData['delete']) && !empty($requestData['delete'])) {
                DB::table('device')->where('id', '=', $request->all()['delete'])->delete();
                return redirect()->to('/');
            }

            if ($requestData['sql']) {
                if ($requestData['sql']["is_active"] && $requestData['sql']['sqlRequest']) {

                    $requestDataSql = $requestData['sql']["sqlRequest"];
                    if (str_starts_with($requestDataSql, 'select')) {
                        $responseFromFormSql = DB::select($requestDataSql);
                    } else {
                        $responseFromFormSql = DB::statement($requestDataSql);
                    }

                }

            }

        }
        $device = DB::table('device')->get();
        $address = DB::table('address')->get();
        $connections = DB::table('connections')->get();

        $domains = DB::table('domains')
            ->select([
                'domains.id',
                'domains.name',
                'address.*',
                'resourses.name as resourses_name'
            ])
            ->join('address', function ($join) {
                $join->on('address.id', '=', 'address_id');
                    })->join('resourses', function ($join) {
                            $join->on('resourses.id', '=', 'resourse_id');
                        })
                        ->get();


        $services = DB::table('services')->get();
        return view('home')
            ->with('device', $device)
            ->with('address', $address)
            ->with('connections', $connections)
            ->with('domains', $domains)
            ->with('services', $services)
            ->with('sqlRequest', $requestDataSql)
            ->with('responseFromFormSql', $responseFromFormSql)
            ;
    }
}
