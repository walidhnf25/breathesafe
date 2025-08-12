<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\DetectionImage;
use Illuminate\Support\Facades\Validator;

class DetectionImageController extends Controller
{
    public function location(Request $request)
    {
        $location = Location::all(); // Ambil semua data lokasi
        return view('detection-image-location', compact('location'));
    }

    public function index(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        $query = DetectionImage::whereHas('camera', function ($query) use ($id) {
            $query->where('location_id', $id);
        });

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $images = $query->get();

        return view('detection-image', compact('location', 'images'));
    }

    public function getAll()
    {
        $detectionImages = DetectionImage::with('camera')->get();

        return response()->json([
            'success' => true,
            'message' => 'All detection images retrieved successfully.',
            'data' => $detectionImages
        ]);
    }

    public function getById($id)
    {
        $detectionImage = DetectionImage::with('camera')->where('camera_id' , $id)->get();

        if (!$detectionImage) {
            return response()->json([
                'success' => false,
                'message' => 'Detection image not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detection image retrieved successfully.',
            'data' => $detectionImage
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'detection_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'camera_id' => 'required|exists:camera,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        if ($request->hasFile('detection_image')) {
            $image = $request->file('detection_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/detection_image', $imageName);
        }

        $detectionImage = new DetectionImage();
        $detectionImage->detection_image = $imageName;
        $detectionImage->camera_id = $request->camera_id;
        $detectionImage->save();

        return response()->json([
            'success' => true,
            'message' => 'Detection image saved successfully!',
            'data' => $detectionImage
        ], 201);
    }
}
