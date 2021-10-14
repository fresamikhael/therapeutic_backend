<?php

namespace App\Http\Controllers\API;

use Exception;

use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use App\Models\Appointment;
use Illuminate\Http\Request;

use App\Models\AppointmentDetail;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $users_id = $request->input('users_id');
        $doctors_id = $request->input('doctors_id');

        if($id)
        {
            $appointment = Appointment::with(['doctors', 'user'])->find($id);

            if($appointment) {
                return ResponseFormatter::success(
                    $appointment,
                    'Data transaksi berhasil diambil'
                );
            }
            else {
                return ResponseFormatter::error(
                    null,
                    'Data transaksi tidak ada',
                    404
                );
            }
        }

        $appointment = Appointment::with(['doctors', 'user'])->where('users_id', Auth::user()->id);

        if($users_id) {
            $appointment->where('users_id', $users_id);
        }

        if($doctors_id) {
            $appointment->where('doctors_id', $doctors_id);
        }

        return ResponseFormatter::success(
            $appointment->paginate($limit),
            'Data transaksi berhasil diambil'
        );
    }

    public function process(Request $request)
    {
        // Save User Data
        $user = Auth::user();

        // Proses Checkout
        $code = 'RENT-' . mt_rand(0000, 9999);
        // $details = Appointment::with(['doctors', 'user'])
        //                 ->where('users_id', Auth::user()->id)
        //                 ->get();

        // Transaction Create
        $appointment = Appointment::create([
            'users_id' => $request->user()->id,
            'doctors_id' => $request->doctors_id,
            'price'=> (int) $request->price,
            'code' => $code
        ]);

        return ResponseFormatter::success($appointment, 'Transaction Created!');

        // foreach ($details as $detail){

        //     AppointmentDetail::create([
        //         'appointments_id' => $appointment->id ,
        //         'doctors_id'=> $detail->doctor->id,
        //         'appointment_date' => $request->appointment_date,
        //         'status' => 'PENDING',
        //     ]);
        // }

        // // Konfigurasi Midtrans
        // Config::$serverKey = config('services.midtrans.serverKey');
        // Config::$isProduction = config('services.midtrans.isProduction');
        // Config::$isSanitized = config('services.midtrans.isSanitized');
        // Config::$is3ds = config('services.midtrans.is3ds');

        // // Buat array untuk dikirim ke Midtrans
        // $midtrans = [
        //     'transaction_details' => [
        //         'order_id' => $code,
        //         'gross_amount' => (int) $request->price
        //     ],
        //     'customer_details' => [
        //         'first_name' => $request->user()->name,
        //         'email' => $request->user()->email,
        //     ],
        //     'enabled_payments' => [
        //         'gopay', 'permata_va', 'bca_va', 'bni_va', 'bri_va', 'shopeepay'
        //     ],
        //     'vtweb' => []
        // ];

        // $params = array(
        //     'transaction_details' => array(
        //         'order_id' => $code,
        //         'gross_amount' => 10000,
        //     ),
        //     'customer_details' => array(
        //         'first_name' => 'budi',
        //         'last_name' => 'pratama',
        //         'email' => 'budi.pra@example.com',
        //         'phone' => '08111222333',
        //     ),
        // );

        // try {
        //     // Get Snap Payment Page URL
        //     $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
        //     $snapToken = Snap::getSnapToken($midtrans);

        //     // Redirect to Snap Payment Page
        //     return response()->json(['token' => $snapToken, 'payment_url' => $paymentUrl]);
        // }
        // catch (Exception $e) {
        //     echo $e->getMessage();
        // }

    }
}
