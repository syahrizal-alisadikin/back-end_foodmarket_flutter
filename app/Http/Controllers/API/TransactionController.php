<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
