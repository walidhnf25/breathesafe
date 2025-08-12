<?php

namespace App\Http\Controllers;

use App\Models\DetectionImage;
use Illuminate\Http\Request;

class LiveStreamController extends Controller
{
    public function index(Request $request)
    {
        $detections = DetectionImage::all();
        return view('live-stream', compact('detections'));
    }
}
