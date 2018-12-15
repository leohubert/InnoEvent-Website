<?php

namespace App\Http\Controllers;

use App\Place;
use Illuminate\Http\Request;
use Braintree_Transaction;

class PaymentsController extends Controller
{
    public function process(Request $request, $id)
    {

        $place = Place::findOrFail($id);

        $payload = $request->input('payload', false);
        $nonce = $payload['nonce'];


        $status = Braintree_Transaction::sale([
            'amount' => $place->price,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => True
            ]
        ]);

        if ($status->success) {
            $place->buyer_id = $request->user()->id;
            $place->save();
        }

        return response()->json($status);
    }
}
