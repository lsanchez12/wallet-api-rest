<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Libraries\SOAP;
use Mail;
use App\Mail\SendMail;

class TransactionController extends Controller{

    public function createTransaction(Request $request, $userId){
        try {
            $validator = Validator::make($request->all(), [
                "amount" => ["required","numeric","min:1"],
            ]);

            if($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'errors' => $validator->errors()], 500);
            }
            $service =  new SOAP();
            $transaction = $service->call('createTransaction', [$request->amount, $userId]);

            $token = $service->call('getTransaction', [$transaction["data"]["transaction_uuid"]]);
            $user = $service->call('getUser', [$userId]);
            

            Mail::to($user["data"]["email"])->send(new SendMail([
                'title' => 'Token transaction test',
                'transaction' => $transaction["data"]["transaction_uuid"],
                'token' => $token["data"]["token"],
                'body' => "The token for transaction is: "
            ]));

            return response()->json($transaction, 201);
        } catch (\Throwable $e) {
            \Log::info($e);
            return response()->json(['status' => false, 'message' => 'Error', 'errors' => $e->getMessage()], 500);
        }
    }

    public function getTransactions($userId){
        try {
            $service =  new SOAP();
            return response()->json($service->call('getTransactions', [$userId]), 200);
        } catch (\Throwable $e) {
            \Log::info($e);
            return response()->json(['status' => false, 'message' => 'Error', 'errors' => $e->getMessage()], 500);
        }
    }

    public function chargePayment(Request $request, $transactionUuid){
        try {
            $validator = Validator::make($request->all(), [
                "token" => ["required","numeric","digits:6"],
            ]);

            if($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'errors' => $validator->errors()], 500);
            }
            $service =  new SOAP();
            return response()->json($service->call('chargePayment', [$transactionUuid, $request->token]), 201);
        } catch (\Throwable $e) {
            \Log::info($e);
            return response()->json(['status' => false, 'message' => 'Error', 'errors' => $e->getMessage()], 500);
        }
    }

    
}
