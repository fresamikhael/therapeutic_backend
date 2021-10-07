<?php

namespace App\Http\Controllers\API;

use App\Models\Hospital;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HospitalDataController extends Controller
{
    public function create(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                ],
                'address' => [
                    'required'
                ],
            ]
        );

        if ($validate->fails()) {
            return response()->json(
                [
                    'meta' => [
                        'status' => 'error',
                        'message' => 'Validation Error',
                    ],
                    'data' => [
                        'validation_errors' => $validate->errors()
                    ],
                ]
            );
        } else {
            $check_hospital = Hospital::where('name', $request->name)
                ->where('address', $request->address)
                ->first();
            if ($check_hospital) {
                return response()->json(
                    [
                        'meta' => [
                            'code' => 500,
                            'status' => 'error',
                            'message' => 'Validation Error',
                        ],
                        'data' => [
                            'message' => 'Rumah Sakit sudah ada.'
                        ],
                    ]
                );
            }

            $check_id = Hospital::count();

            if ($check_id == 0) {
                $hospital_id = 'RS' . date('dmy') . '0001';
            } else {
                $id = $check_id + 1;
                if ($id < 10) {
                    $hospital_id = 'RS' . date('dmy') . '000' . $id;
                } elseif ($id >= 10 && $id <= 99) {
                    $hospital_id = 'RS' . date('dmy') . '00' . $id;
                } elseif ($id >= 100 && $id <= 999) {
                    $hospital_id = 'RS' . date('dmy') . '0' . $id;
                } elseif ($id >= 1000 && $id <= 9999) {
                    $hospital_id = 'RS' . date('dmy') . $id;
                }
            }

            $hospital_data = Hospital::create([
                'hospital_id' => $hospital_id,
                'name' => $request->name,
                'address' => $request->address,
            ]);

            return response()->json(
                [
                    'meta' => [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Success Create Hospital!',
                    ],
                    'data' => [
                        'hospital' => $hospital_data
                    ],
                ]
            );
        }
    }

    public function show()
    {
        $hospital_data = Hospital::get();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Show Data Hospital!',
                ],
                'data' => [
                    'hospital' => $hospital_data,
                ],
            ]
        );
    }
}
