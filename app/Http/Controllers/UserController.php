<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Libraries\SOAP;

class UserController extends Controller
{
    public function login(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                "email" => ["required","email"],
                'password' => ['required', 'string'],
            ]);

            if($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'errors' => $validator->errors()], 500);
            }
            $validate = $request->all();
            $service =  new SOAP();
            return response()->json($service->call('login', [$validate["email"],$validate["password"]]), 200);
        } catch (\Throwable $e) {
            \Log::info($e);
            return response()->json(['status' => false, 'message' => 'Error', 'errors' => $e->getMessage()], 500);
        }
    }

    public function getUser($id){
        try {
            $service =  new SOAP();
            return response()->json($service->call('getUser', [$id]), 200);
        } catch (\Throwable $e) {
            \Log::info($e);
            return response()->json(['status' => false, 'message' => 'Error', 'errors' => $e->getMessage()], 500);
        }
    }

    public function createUser(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                "document" => ["required","numeric", 'min:4'],
                "first_name" => ["required","string"],
                "last_name" => ["required","string"],
                "phone_number" => ["required","string"],
                "email" => ["required","email"],
                'password' => ['required', 'string', 'confirmed'],
            ]);

            if($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'errors' => $validator->errors()], 500);
            }
            $validate = $request->all();
            unset($validate["password_confirmation"]);
            $service =  new SOAP();
            return response()->json($service->call('createUser', [$validate]), 201);
        } catch (\Throwable $e) {
            \Log::info($e);
            return response()->json(['status' => false, 'message' => 'Error', 'errors' => $e->getMessage()], 500);
        }
    }
}
