<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Libraries\SOAP;

class WalletController extends Controller{

    public function getWallet($userId){
        try {
            $service =  new SOAP();
            return response()->json($service->call('getWallet', [$userId]), 200);
        } catch (\Throwable $e) {
            \Log::info($e);
            return response()->json(['status' => false, 'message' => 'Error', 'errors' => $e->getMessage()], 500);
        }
    }
    public function getBalance($walletUuid){
        try {
            $service =  new SOAP();
            return response()->json($service->call('getBalance', [$walletUuid]), 200);
        } catch (\Throwable $e) {
            \Log::info($e);
            return response()->json(['status' => false, 'message' => 'Error', 'errors' => $e->getMessage()], 500);
        }
    }

    public function chargeWallet(Request $request,$walletUuid){
        try {
            $validator = Validator::make($request->all(), [
                "amount" => ["required","numeric","min:1"],
            ]);

            if($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'errors' => $validator->errors()], 500);
            }
            $service =  new SOAP();
            return response()->json($service->call('chargeWallet', [$walletUuid, $request->amount]), 200);
        } catch (\Throwable $e) {
            \Log::info($e);
            return response()->json(['status' => false, 'message' => 'Error', 'errors' => $e->getMessage()], 500);
        }
    }

    
}
