<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camera;
use App\Models\Location;

class CameraController extends Controller
{
    public function index(Request $request)
    {
        $Camera = Camera::all();
        $Location = Location::all();

        return view('camera', compact('Camera', 'Location'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'ip_camera' => 'required|string|max:255',
            'location_id' => 'required|exists:location,id',
        ]);

        try {
            // Periksa apakah IP kamera sudah ada
            $existingCamera = Camera::where('ip_camera', $validatedData['ip_camera'])->first();

            if ($existingCamera) {
                return redirect()->back()->with('error', 'IP Camera sudah terdaftar!');
            }

            // Simpan data kamera baru
            Camera::create([
                'ip_camera' => $validatedData['ip_camera'],
                'location_id' => $validatedData['location_id'],
            ]);

            return redirect()->back()->with('success', 'Camera added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while saving the data. Please try again.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ip_camera' => 'required|string|max:255',
            'location_id' => 'required|exists:location,id',
        ]);

        $Camera = Camera::findOrFail($id);

        $Camera->update([
            'ip_camera' => $request->ip_camera,
            'location_id' => $request->location_id,
        ]);

        return redirect()->back()->with('success', 'Camera Name has been successfully updated!');
    }

    public function destroy($id)
    {
        $Camera = Camera::findOrFail($id);

        $Camera->delete();

        return redirect()->back()->with('success', 'Camera name deleted successfully.');
    }
}
