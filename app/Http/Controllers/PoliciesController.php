<?php

namespace App\Http\Controllers;

use App\Models\policies;
use App\Models\Policy;
use Illuminate\Http\Request;

class PoliciesController extends Controller
{
 
    public function PolicyType(Request $request){

        return Policy::where("type","=", $request->type)->first();
    }
}
