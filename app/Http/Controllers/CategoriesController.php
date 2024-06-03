<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Helpar\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesController extends Controller
{

    public function CategoryList():JsonResponse{

        $data = Categorie::all();

        return ResponseHelper::success('success',$data,200);

    }
}
