<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\AppointmentDetail;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AppointmentDetailController extends Controller
{
    public function process(Request $request)
    {
        // Save User Data
        $user = Auth::user();

        // Proses Checkout
            $appointment = AppointmentDetail::create([
                'appointments_id' => $request->appointments_id ,
                'doctors_id'=> $request->doctors_id,
                'appointment_date' => $request->appointment_date,
                'status' => 'PENDING',
            ]);

        return ResponseFormatter::success($appointment, 'Appointment Detail Created!');



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
