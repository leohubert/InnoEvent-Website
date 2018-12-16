<?php

namespace App\Http\Controllers;

use App\Place;
use Illuminate\Http\Request;
use Braintree_Transaction;

class PaymentsController extends Controller
{
    public function process(Request $request)
    {

        $place = null;


        $places = $request->input('placesIds', false);
        $total = 0;

        foreach ($places as $place) {
            $placeDB = Place::find($place);
            if ($placeDB) {
                $total += $placeDB->price;
            }
        }

        $payload = $request->input('payload', false);
        $nonce = $payload['nonce'];


        $status = Braintree_Transaction::sale([
            'amount' => number_format($total, 2, '.', ''),
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => True
            ]
        ]);

        if ($status->success) {
            foreach ($places as $place) {
                $placeDB = Place::find($place);
                if ($placeDB) {
                    $placeDB->buyer_id = $request->user()->id;
                    $placeDB->save();
                }
            }
        }

        return response()->json($status);
    }
}
