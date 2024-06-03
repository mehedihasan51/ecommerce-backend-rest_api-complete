<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use App\Helpar\ResponseHelper;
use App\Models\Customer_profile;
use App\Models\Product_cart;
use App\Models\Product_detail;
use App\Models\Product_slider;
use App\Models\Product_wishe;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Casts\Json;

class ProductsController extends Controller
{
   

    public function ListProductByCategory(Request $request):JsonResponse{


        $data = Product::where("category_id",$request->id)->with('brand','category')->get();

        return ResponseHelper::Out('Success',$data,200);
    }

    public function ListProductByRemark(Request $request):JsonResponse
    {

        $data = Product::where('remark',$request->id)->with('brand','category')->get();

        return ResponseHelper::Out('Success',$data,200);
    }


    public function ListProductByBrand(Request $request):JsonResponse{

        $data = Product::where('brand_id',$request->id)->with('brand','category')->get();

        return ResponseHelper::Out('Success',$data,200);
    }

    public function ListProductBySlider(Request $request):JsonResponse{
        $data = Product_slider::all();

        return ResponseHelper::Out('Success',$data,200);
    }

    public function ListProductByDetails(Request $request):JsonResponse{
        $data = Product_detail::where('product_id',$request->id)->with('product','product.brand','product.cutegory')->get();

        return ResponseHelper::Out('Success',$data,200);
    }

    public function ListReviewByProduct(Request $request):JsonResponse{

        $data = Review::where('product_id',$request->id)->with(['profile'=>function($query){
            $query->select('id','cus_num');
        }])->get();

        return ResponseHelper::Out('Success',$data,200);
    }

    public function CreateProductReview(Request $request): JsonResponse
{
    $user_id = $request->header('id');

    // Check if customer profile exists
    $profile = Customer_profile::where('user_id', $user_id)->first();

    if ($profile) {
        $request->merge(['customer_id' => $profile->id]);

        $product_id = $request->input('product_id');

        // Validate if product_id exists in products table
        $product = Product::find($product_id);

        if (!$product) {
            return ResponseHelper::Out('fail', 'Product does not exist', 400);
        }

        // Validate request data
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'description' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'customer_id' => 'required|integer'
        ]);

        // Update or create the review
        $data = Review::updateOrCreate(
            ['customer_id' => $profile->id, 'product_id' => $product_id],
            $validatedData
        );

        return ResponseHelper::Out('success', $data, 200);
    } else {
        return ResponseHelper::Out('fail', 'Customer profile does not exist', 400);
    }
}



public function ProductWishList(Request $request): JsonResponse{
    $user_id = $request->header('id');

    $data=Product_wishe::where('user_id', $user_id)->with('product')->get();

    return ResponseHelper::Out('success', $data,200);
}

public function CreateWishList(Request $request): JsonResponse{

    $user_id = $request->header('id');

    $data=Product_wishe::updateOrCreate(
        ['user_id'=> $user_id,'product_id'=> $request->input('product_id')],
        ['user_id'=> $user_id,'product_id'=> $request->input('product_id')],
    );

    return ResponseHelper::Out('success', $data,200);
}

public function RemoveWishList(Request $request): JsonResponse{
    $user_id = $request->header('id');

    $data=Product_wishe::where(['user_id' => $user_id,'product_id'=>$request->product_id])->delete();

    return ResponseHelper::Out('success', $data,200);
}

public function CreateCartList(Request $request): JsonResponse{

    $user_id = $request->header('id');
    $product_id = $request->input('product_id');
    $color = $request->input('color');
    $size = $request->input('size');
    $qty = $request->input('qty');

    $UnitPrice = 0;

    $productDetails = Product::where('id','=', $product_id)->first();

    if (!$productDetails) {
        return ResponseHelper::Out('error', 'Product not found', 404);
    }

    if($productDetails->discount==1){

        $UnitPrice=$productDetails->discount_price;
    }else{

        $UnitPrice=$productDetails->price;
    }

    $totalPrice=$qty*$UnitPrice;

    $data=Product_cart::updateOrCreate(
        ['user_id'=> $user_id,'product_id'=> $product_id],
        [

            'user_id'=> $user_id,
            'product_id'=> $product_id,
            'color'=> $color,
            'size'=> $size,
            'qty'=> $qty,
            'price'=> $totalPrice

        ]
        );

        return ResponseHelper::Out('success', $data,200);

}

public function CartList(Request $request): JsonResponse{

    $user_id = $request->header('id');

    $data= Product_cart::where('user_id','=', $user_id)->with('product')->get();
    return ResponseHelper::Out('success', $data,200);
}

public function DeleteCartList(Request $request): JsonResponse{

    $user_id = $request->header('id');
    $data= Product_cart::where('user_id','=', $user_id)->where('product_id','=', $request->product_id)->delete();

    return ResponseHelper::Out('success', $data,200);

}
}
