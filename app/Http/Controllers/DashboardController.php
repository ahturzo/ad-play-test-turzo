<?php

namespace App\Http\Controllers;

use App\AdtrackTest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(){
        $platforms = AdtrackTest::select('PlatformType')->distinct()->get();
        return view('dashboard')->with('platforms', $platforms);
    }

    public function getData(){
        $all = AdtrackTest::all();
        $dt = '';
        $i = 1;
        $dt .= '<table id="datatable" class="table table-bordered table-hover table-striped" style="width: 100%;">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Advertiser</th>
                            <th>Publisher</th>
                            <th>Platform</th>
                            <th>Vertical</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Landing Page</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach($all as $value)
        {
            $image = "<img src='". $value->ImageUrl ."' width='200' height='200'>";
            $dt .= '<tr><td>'. $i++ .'</td>
                        <td>'. $image . '</td>
                        <td>'. $value->advertiserName . '</td>
                        <td>'. $value->Name . '</td>
                        <td>'. $value->PlatformType . '</td>
                        <td>'. $value->category_name . '</td>
                        <td>'. date('d M Y', strtotime($value->TimeStamp)) . '</td>
                        <td>'. date('h:m A', strtotime($value->TimeStamp)) . '</td>
                        <td><a target="_blank" href="'. $value->DestinationLink . '" class="btn btn-info"><i class="fad fa-link"></i></a></td>
                    </tr>';
        }
        $dt .= '</tbody></table>';
        return json_encode($dt);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $platform = $request->input('platform');
        if(!$platform)
        {
            $platform = array();
            $platforms = AdtrackTest::select('PlatformType')->distinct()->get();
            foreach ($platforms as $key => $value){
                $platform[$key] = $value->PlatformType;
            }
        }
        else
        {
            $platform = array();
            $platform[] = $request->input('platform');
        }

        $startDate = null;
        if($request->input('start_date'))
            $startDate = date('y-m-d', strtotime($request->input('start_date')));

        $endDate = null;
        if($request->input('end_date'))
            $endDate = date('y-m-d', strtotime($request->input('end_date')));

        if($startDate && $endDate)
        {
            if($startDate > $endDate)
            {
                $temp = $endDate;
                $endDate = $startDate;
                $startDate = $temp;
            }
        }

        if($request->input('start_time'))
            $startTime = date('H:m:s', strtotime($request->input('start_time')));
        else
            $startTime = date('H:m:s', strtotime('00:00:00'));

        if($request->input('end_time'))
            $endTime = date('H:m:s', strtotime($request->input('end_time')));
        else
            $endTime = date('H:m:s', strtotime('11:59:59'));

        $all = AdtrackTest::whereIn('PlatformType', $platform)
            ->where('advertiserName', 'like', '%'.$keyword.'%')
            ->orWhere('Name', 'like', '%'.$keyword.'%')
            ->orWhere('PlatformType', 'like', '%'.$keyword.'%')
            ->orWhere('category_name', 'like', '%'.$keyword.'%')
            ->whereBetween('TimeStamp', [$startDate, $endDate])
            ->whereTime('TimeStamp', '>=', $startTime)
            ->whereTime('TimeStamp', '<=', $endTime)
            ->get();

        $dt = '';
        $i = 1;
        $dt .= '<table id="datatable" class="table table-bordered table-hover table-striped" style="width: 100%;">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Advertiser</th>
                            <th>Publisher</th>
                            <th>Platform</th>
                            <th>Vertical</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Landing Page</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach($all as $value)
        {
            $image = "<img src='". $value->ImageUrl ."' width='200' height='200'>";
            $dt .= '<tr><td>'. $i++ .'</td>
                        <td>'. $image . '</td>
                        <td>'. $value->advertiserName . '</td>
                        <td>'. $value->Name . '</td>
                        <td>'. $value->PlatformType . '</td>
                        <td>'. $value->category_name . '</td>
                        <td>'. date('d M Y', strtotime($value->TimeStamp)) . '</td>
                        <td>'. date('h:m A', strtotime($value->TimeStamp)) . '</td>
                        <td><a target="_blank" href="'. $value->DestinationLink . '" class="btn btn-info"><i class="fad fa-link"></i></a></td>
                    </tr>';
        }
        $dt .= '</tbody></table>';
        return json_encode($dt);
    }
}
