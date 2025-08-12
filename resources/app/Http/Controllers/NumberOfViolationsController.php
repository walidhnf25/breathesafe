<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NumberOfViolationsController extends Controller
{
    public function index()
    {
        $locations = Location::all();

        $startDate = request('start_date');
        $endDate = request('end_date');

        // Ambil data detection_image berdasarkan camera_id dan jam
        $groupedDetections = DB::table('detection_image')
            ->join('camera', 'camera.id', '=', 'detection_image.camera_id')
            ->select(
                'camera.location_id',
                DB::raw('camera_id'),
                DB::raw('DATE_FORMAT(detection_image.created_at, "%Y-%m-%d %H") as hour_group')
            )
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('detection_image.created_at', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            })
            ->groupBy('camera_id', 'hour_group', 'camera.location_id')
            ->get();

        // Hitung jumlah per lokasi
        $detectionCounts = $groupedDetections->groupBy('location_id')->map(function ($group) {
            return $group->count();
        });

        foreach ($locations as $location) {
            $location->detection_image_count = $detectionCounts[$location->id] ?? 0;
        }

        return view('number-of-violations', compact('locations'));
    }
}
