<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;


class TransactionController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $fk_food_id = $request->input('fk_food_id');
        $status = $request->input('status');



        if ($id) {
            $transaction = Transaction::with('food', 'user')->find($id);

            if ($transaction) {
                return ResponseFormatter::success(
                    $transaction,
                    'Data Transaction Berhasil Diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data Transaction Tidak ada',
                    404
                );
            }
        }

        $transaction = Transaction::with('user', 'food')->where('fk_user_id', Auth::user()->id);

        if ($fk_food_id) {
            $transaction->where('fk_food_id', $fk_food_id);
        }

        if ($status) {
            $transaction->where('status', $status);
        }


        return ResponseFormatter::success(
            $transaction->paginate($limit),
            'Data Transaction Berhasil diambil'
        );
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->update($request->all());

        return ResponseFormatter::success($transaction, 'Data Berhasil Diupdate');
    }

    public function checkout(Request $request)
    {
        // validasi input
        $validator = Validator::make($request->all(), [
            'fk_food_id' => 'required|exists:food,id',
            'fk_user_id' => 'required|exists:users,id',
            'quantity'   => 'required',
            'total'      => 'required',
            'status'     => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $transaction = Transaction::create([
            'fk_food_id' => $request->fk_food_id,
            'fk_user_id' => $request->fk_user_id,
            'quantity'   => $request->quantity,
            'total'      => $request->total,
            'status'     => $request->status,
            'payment_url' => ''
        ]);

        // Config Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        // Panggil transaction yang tadi dibuat
        $transaction = Transaction::with('food', 'user')->find($transaction->id);

        // Membuat Transaction Midtrans
        $midtrans = [
            'transaction_details' => [
                'order_id' => $transaction->id,
                'grous_amount' => (int) $transaction->total,
            ],
            'customer_details' => [
                'first_name' => $transaction->user->name,
                'email' => $transaction->user->email,
            ],
            'enabled_payments' => ['gopay', 'bank_transfer'],
            'vtweb' => []
        ];

        // Memanggil Midtrans
        try {
            // ambil halaman payment midtrans
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            $transaction->payment_url = $paymentUrl;
            $transaction->save();

            // Mengembalikan data ke api
            return ResponseFormatter::success($transaction, 'Transaction Berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Transaction Gagal');
        }
    }
}
