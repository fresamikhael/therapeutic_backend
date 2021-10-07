<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Appointment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;

class TransactionController extends Controller
{
    public function process(Request $request)
    {
        // Save User Data
        $user = Auth::user();
        // $user->update($request->except('price'));

        // Proses Checkout
        $code = 'RENT-' . mt_rand(0000, 9999);
        // $carts = Cart::with(['car', 'user'])
        //                 ->where('users_id', Auth::user()->id)
        //                 ->get();

        // // Menghitung Selisih Hari
        // $rentdate = $request->rent_date;
        // $returndate = $request->return_date;
        // $datetime1 = new DateTime($rentdate);
        // $datetime2 = new DateTime($returndate);
        // $interval = $datetime1->diff($datetime2);
        // $days = $interval->format('%a');

        // $driver = $request->driver;

        // if($driver == 'PAKAI'){
        //     $driver = 150000;
        // }
        // else {
        //     $driver = 0;
        // }

        // Transaction Create
        Appointment::create([
            'users_id' => $request->user()->id,
            'doctors_id' => $request->doctors_id,
            'price'=> (int) $request->price,
            'code' => $code
        ]);

        // foreach ($carts as $cart){

        //     // Merubah status mobil
        //     Car::where('id', $cart->car->id)->update([
        //         'status' => 'DISEWA'
        //     ]);

        //     $trx = 'TRX-' . mt_rand(0000, 9999);

        //     TransactionDetail::create([
        //         'transactions_id' => $transaction->id ,
        //         'cars_id'=> $cart->car->id,
        //         'price' => $cart->car->price * $days + $driver,
        //         'status' => 'BELUM DIAMBIL',
        //         'penalty' => NULL,
        //         'finish_date' => $request->finish_date,
        //         'code' => $trx,
        //     ]);
        // }


        // // Delete Cart Data
        // Cart::where('users_id', Auth::user()->id)->delete();

        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        // Buat array untuk dikirim ke Midtrans
        $midtrans = [
            'transaction_details' => [
                'order_id' => $code,
                'gross_amount' => (int) $request->price
            ],
            'customer_details' => [
                'first_name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
            'enabled_payments' => [
                'gopay', 'permata_va', 'bca_va', 'bni_va', 'bri_va', 'shopeepay'
            ],
            'vtweb' => []
        ];

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

        try {
            // Get Snap Payment Page URL
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            $snapToken = Snap::getSnapToken($midtrans);

            // Redirect to Snap Payment Page
            return response()->json(['token' => $snapToken, 'payment_url' => $paymentUrl]);
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }

    }
}
