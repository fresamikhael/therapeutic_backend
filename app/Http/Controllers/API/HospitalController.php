<?php

namespace App\Http\Controllers\API;

use App\Models\Hospital;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class HospitalController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $address = $request->input('address');
        $show_hospital = $request->input('show_hospital');

        if($id)
        {
            $hospital = Hospital::with(['doctors'])->find($id);

            if($hospital) {
                return ResponseFormatter::success(
                    $hospital,
                    'Data klinik berhasil diambil'
                );
            }
            else {
                return ResponseFormatter::error(
                    null,
                    'Data klinik tidak ada',
                    404
                );
            }
        }

        $hospital = Hospital::query();

        if($name) {
            $hospital->where('name', 'like', '%' . $name . '%');
        }

        if($address) {
            $hospital->where('address', 'like', '%' . $address . '%');
        }

        if($show_hospital){
            $hospital->with('doctors');
        }

        return ResponseFormatter::success(
            $hospital->paginate($limit),
            'Data list klinik berhasil diambil'
        );
    }
}
