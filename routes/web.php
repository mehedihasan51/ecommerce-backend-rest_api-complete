<?php

use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CustomerProfilesController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenAuthenticate;
use App\Models\Invoice;
use Doctrine\Common\Lexer\Token;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


//Brand List
Route::get('/BrandList', [BrandsController::class,'BrandList']);

//Category List
Route::get('/CategoryList', [CategoriesController::class,'CategoryList']);

Route::get('/ListProductByCategory/{id}', [ProductsController::class,'ListProductByCategory']);

Route::get('/ListProductByBrand', [ProductsController::class,'ListProductByBrand']);

Route::get('ListProductByRemark/{remark}', [ProductsController::class,'ListProductByRemark']);

Route::get('ListProductBySlider', [ProductsController::class,'ListProductBySlider']);

Route::get('ProductDetailsById/{id}', [ProductsController::class,'ProductDetailsById']);

Route::get('/ListReviewByProduct/{product_id}', [ProductsController::class,'ListReviewByProduct']);

Route::get('/PolicyByType/{type}', [ProductsController::class,'PolicyByType']);


//User Auth

Route::get('/UserLogin', [UserController::class,'UserLogin']);
Route::get('/VerifyLogin/{UserEmail}/{OTP}', [UserController::class,'Verifylogin']);
Route::get('/logut', [UserController::class,'UserLogout']);


//User Profile

Route::post('/CreateProfile', [CustomerProfilesController::class,'CreateProfile'])->middleware([TokenAuthenticate::class]);
Route::get('/CreateProfile/ReadProfile', [CustomerProfilesController::class,'ReadProfile'])->middleware([TokenAuthenticate::class]);

//Product Review

Route::post('/CreateProductReview',[ProductsController::class,'CreateProductReview'])->middleware([TokenAuthenticate::class]);

//Wishe List

Route::get('/ProductWishList', [ProductsController::class,'ProductWishList'])->middleware([TokenAuthenticate::class]);
Route::get('/CreateWishList/{product_id}', [ProductsController::class,'CreateWishList'])->middleware([TokenAuthenticate::class]);
Route::get('/RemoveWishList/{product_id}', [ProductsController::class,'RemoveWishList'])->middleware([TokenAuthenticate::class]);

//Product Cart

Route::post('/CreateCartList', [ProductsController::class,'CreateCartList'])->middleware([TokenAuthenticate::class]);
Route::get('/CartList', [ProductsController::class,'CartList'])->middleware([TokenAuthenticate::class]);
Route::get('/DeleteCartList/{product_id}', [ProductsController::class,'DeleteCartList'])->middleware([TokenAuthenticate::class]);

//Invoice and payment

Route::get('/InvoiceCreate', [InvoicesController::class,'InvoiceCreate'])->middleware([TokenAuthenticate::class]);
Route::get('/InvoiceList', [InvoicesController::class,'InvoiceList'])->middleware([TokenAuthenticate::class]);
Route::get('/InvoiceProductList/{invoice_id}', [InvoicesController::class,'InvoiceProductList'])->middleware([TokenAuthenticate::class]);


//payment

Route::post('/PaymentSuccess', [InvoicesController::class,'PaymentSuccess']);
Route::post('/PaymentFail', [InvoicesController::class,'PaymentFail']);
Route::post('/PaymentCancel', [InvoicesController::class,'PaymentCancel']);





