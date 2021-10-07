<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DoctorController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $phone_number = $request->input('phone_number');
        $experience = $request->input('experience');
        $lisence_number = $request->input('lisence_number');
        $photo = $request->input('photo');

        $slug = $request->input('slug');

        $hospitals = $request->input('hospitals');

        $categories = $request->input('categories');

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        if($id)
        {
            $doctor = Doctor::with(['category', 'hospital'])->find($id);

            if($doctor) {
                return ResponseFormatter::success(
                    $doctor,
                    'Data dokter berhasil diambil'
                );
            }
            else {
                return ResponseFormatter::error(
                    null,
                    'Data dokter tidak ada',
                    404
                );
            }
        }

        $doctor = Doctor::with(['category', 'hospital']);

        if($name) {
            $doctor->where('name', 'like', '%' . $name . '%');
        }

        if($price_from) {
            $doctor->where('price', '>=', $price_from);
        }

        if($price_to) {
            $doctor->where('price', '<=', $price_to);
        }

        if($categories) {
            $doctor->where('categories', $categories);
        }

        if($slug) {
            $doctor->where('slug', $slug);
        }

        if($hospitals) {
            $doctor->where('hospitals', $hospitals);
        }

        return ResponseFormatter::success(
            $doctor->paginate($limit),
            'Data dokter berhasil diambil'
        );
    }
}
