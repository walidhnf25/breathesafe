<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Support\Facades\File;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $Location = Location::all();

        return view('location', compact('Location'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name_location' => 'required|string|max:255',
            'location_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar
        ]);

        try {
            // Periksa apakah lokasi sudah ada
            $existingLocation = Location::where('name_location', $request->name_location)->first();

            if ($existingLocation) {
                return redirect()->back()->with('error', 'Location name already exists!');
            }

            // Persiapkan data untuk disimpan
            $newData = $request->only('name_location');

            // Proses unggah gambar
            if ($request->hasFile('location_image')) {
                $file = $request->file('location_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('storage/location_image'); // Folder tujuan
                $file->move($destinationPath, $filename); // Pindahkan file ke folder tujuan
                $newData['location_image'] = $filename; // Simpan path file relatif
            }

            // Simpan data ke database
            Location::create($newData);

            return redirect()->back()->with('success', 'Location added successfully!');
        } catch (\Exception $e) {
            // Tangani error dengan logging dan feedback ke pengguna
            \Log::error('Error storing location: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while saving the data. Please try again.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name_location' => 'required|string|max:255',
            'location_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file opsional
        ]);

        try {
            // Temukan lokasi berdasarkan ID
            $location = Location::findOrFail($id);

            // Persiapkan data yang akan diperbarui
            $updateData = [
                'name_location' => $request->name_location,
            ];

            // Jika ada file gambar baru yang diunggah
            if ($request->hasFile('location_image')) {
                // Hapus file lama jika ada
                if ($location->location_image && file_exists(public_path('storage/location_image/' . basename($location->location_image)))) {
                    unlink(public_path('storage/location_image/' . basename($location->location_image)));
                }

                // Proses file baru
                $file = $request->file('location_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/location_image'), $filename); // Simpan file ke lokasi yang diinginkan
                $updateData['location_image'] = $filename; // Simpan path file
            }

            // Perbarui data di database
            $location->update($updateData);

            return redirect()->back()->with('success', 'Location has been successfully updated!');
        } catch (\Exception $e) {
            \Log::error('Error updating location: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the data. Please try again.');
        }
    }

    public function destroy($id)
    {
        $Location = Location::findOrFail($id);

        // Hapus file gambar jika ada
        if ($Location->location_image && File::exists(public_path($Location->location_image))) {
            File::delete(public_path($Location->location_image));
        }

        $Location->delete();

        return redirect()->back()->with('success', 'Camera name deleted successfully.');
    }
}
