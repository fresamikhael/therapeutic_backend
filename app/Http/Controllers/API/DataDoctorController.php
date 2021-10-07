<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\DoctorCategory;
use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class DataDoctorController extends Controller
{
        public function create(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required'
                ],
                'username' => [
                    'required',
                    Rule::unique(User::class),
                ],
                'email' => [
                    'required',
                    Rule::unique(User::class),
                ],
                'phone_number' => [
                    'required'
                ],
                'hospital' => [
                    'required'
                ],
                'category' => [
                    'required'
                ],
                'price' => [
                    'required'
                ],
                'password' => [
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
            $check_id = Doctor::count();

            if ($check_id == 0) {
                $doctor_id = 'D' . date('dmy') . '0001';
            } else {
                $id = $check_id + 1;
                if ($id < 10) {
                    $doctor_id = 'D' . date('dmy') . '000' . $id;
                } elseif ($id >= 10 && $id <= 99) {
                    $doctor_id = 'D' . date('dmy') . '00' . $id;
                } elseif ($id >= 100 && $id <= 999) {
                    $doctor_id = 'D' . date('dmy') . '0' . $id;
                } elseif ($id >= 1000 && $id <= 9999) {
                    $doctor_id = 'D' . date('dmy') . $id;
                }
            }

            $user = User::create([
                'username' => $request->username,
                'role' => 'doctor',
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $doctor_profile = Doctor::create([
                'token_id' => $user->id,
                'doctor_id' => $doctor_id,
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'hospital_id' => $request->hospital,
                'price' => $request->price,
                'category_id' => $request->category,
                'avatar' => 'https://ui-avatars.com/api/?name=' . $request->name . '&color=7F9CF5&background=EBF4FF',
            ]);

            $category = DoctorCategory::where('category_id', $request->category)
                ->first();

            $total_category = $category->total + 1;

            DoctorCategory::where('category_id', $request->category)
                ->update([
                    'total' => $total_category
                ]);

            return response()->json(
                [
                    'meta' => [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Success Create Doctor!',
                    ],
                    'data' => [
                        'doctor' => $doctor_profile,
                    ],
                ]
            );
        }
    }

    public function show()
    {
        $doctor_profile = Doctor::get();

        $doctor_data = [];

        foreach ($doctor_profile as $row) {
            $find_hospital = Hospital::where('hospital_id', $row->hospital_id)->first();
            $find_category = DoctorCategory::where('category_id', $row->category_id)->first();

            $data = [
                'id' => $row->token_id,
                'doctor_id' => $row->doctor_id,
                'name' => $row->name,
                'avatar' => $row->avatar,
                'hospital' => $find_hospital->name . ' - ' . $find_hospital->address,
                'category' => $find_category->name,
            ];

            array_push($doctor_data, $data);
        }

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Show Data Doctor!',
                ],
                'data' => [
                    'doctor' => $doctor_data,
                ],
            ]
        );
    }

    public function delete($id)
    {
        $doctor_profile = Doctor::where('doctor_id', $id)->first();
        $doctor_account = User::where('id', $doctor_profile->token_id)->first();

        $doctor_profile->delete();
        $doctor_account->delete();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Success!',
                ],
                'data' => [
                    'message' => 'Success Delete Doctor!',
                ],
            ]
        );
    }
}
