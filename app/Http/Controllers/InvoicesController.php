<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\invoices;
use App\Helpar\SSLCommerz;
use App\Models\Product_cart;
use Illuminate\Http\Request;
use PHPUnit\TextUI\Exception;
use App\Helpar\ResponseHelper;
use App\Models\Invoice_product;
use App\Models\Customer_profile;
use Illuminate\Support\Facades\DB;

class InvoicesController extends Controller
{

    public function InvoiceCreate(Request $request)
    {

        DB::beginTransaction();
        try {

            $user_id = $request->header("id");

            $user_email = $request->header("email");

            $tran_id=uniqid();
            $delivery_status = 'Pending';
            $payment_status = 'Pending';

            $Profile=Customer_profile::where('user_id','=',$user_id)->first();
            $cus_details="Name:$Profile->cus_name,Address:$Profile->cus_add,City:$Profile->cus_city,Phone: $Profile->cus_phone";
            $ship_details="Name:$Profile->ship_name,Address:$Profile->ship_add,City:$Profile->ship_city,Phone: $Profile->cus_phone";

            //Payable Calculation

            $total=0;

            $cartList = Product_cart::where("user_id","=",$user_id)->get();

            foreach ($cartList as $cartItem) {

                $total=$total+$cartItem->price;

            }

            $vat=($total*3)/100;
            $payable=$total+$vat;

            $invoice= Invoice::create([

                "total"=> $total,
                "vat"=> $vat,
                "payable"=> $payable,
                "cus_details"=> $cus_details,
                "ship_details"=> $ship_details,
                "tran_id"=> $tran_id,
                "delivery_status"=> $delivery_status,
                "payment_status"=> $payment_status,
                "user_id"=> $user_id

                ]);

                $invoiceID=$invoice->id;

                foreach ($cartList as $EachProduct) {

                    Invoice_product::create([
                        "invoice_id"=> $invoiceID,
                        "product_id" => $EachProduct['product_id'],
                        'user_id'=> $user_id,
                        'qty'=> $EachProduct['qty'],
                        'sale_price'=> $EachProduct['price'],

                    ]);

                    $paymentMethod=SSLCommerz::InitiatePayment($Profile,$payable,$tran_id,$user_email);

                    DB::commit();

                    return ResponseHelper::Out('success', array('paymentMethod' => $paymentMethod, 'payable' => $payable, 'vat' => $vat, 'total' => $total), 200);

                }

        } catch (Exception $e) {

            DB::rollBack();
            return ResponseHelper::Out('fail',$e->getMessage(),200);

        }
    }


    function InvoiceList(Request $request){
        $user_id=$request->header('id');
        return Invoice::where('user_id',$user_id)->get();
    }

    function InvoiceProductList(Request $request){

        $user_id=$request->header('id');
        $invoice_id=$request->invoice_id;
        return Invoice_product::where(['user_id'=>$user_id,'invoice_id'=> $invoice_id])->with('product')->get();
    }

    function PaymentSuccess(Request $request){

        return SSLCommerz::InitiateSuccess($request->query('tran_id'));

    }

   
    function PaymentFail(Request $request){ 
        return SSLCommerz::InitiateFail($request->query('tran_id'));
    }

    function PaymentCancel(Request $request){ 
        return SSLCommerz::InitiateCancel($request->query('tran_id'));
    }
    function PaymentIPN(Request $request){ 
        return SSLCommerz::InitiateIPN($request->input('tran_id'), $request->input('status'), $request->input('val_id'));
    }
    
}
