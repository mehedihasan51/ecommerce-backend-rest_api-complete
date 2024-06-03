<?php

namespace App\Http\Controllers;

use App\Helpar\ResponseHelper;
use App\Models\Customer_profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerProfilesController extends Controller
{
   

    public function CreateProfile(Request $request): JsonResponse
    {
       $user_id = $request->header('id');

       $request->merge(['user_id'=>$user_id]);

       $data = Customer_profile::updateOrCreate(
        ['user_id'=>$user_id],
        $request->input()
       );

       return ResponseHelper::Out('success', $data,200);
    }

    public function ReadProfile(Request $request): JsonResponse
    {
        $user_id = $request->header('id');
        $data = Customer_profile::where('user_id',$user_id)->with('user')->first();

        return ResponseHelper::Out('success', $data,200);
    }
}
