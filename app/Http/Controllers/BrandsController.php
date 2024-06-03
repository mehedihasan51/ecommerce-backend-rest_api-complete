<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use App\Helpar\ResponseHelper;

class BrandsController extends Controller
{
 public function BrandList(Request $request) {

    $data = Brand::all();

    return ResponseHelper::success('success',$data,200);

 }
  
}
